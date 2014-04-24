<?hh // strict

namespace BPAY;

use \DateTime;
use \SplFileInfo;

class Reader
{
    /**
     * Create new instance of Bpay biller information file reader
     *
     * @param string gpg location
     * @param string $passphrase BPAY passphrase (should be the same as for private key)
     * @param string $filename directory or filename where BPAY transaction summary file(s) are stored 
     */
    public function __construct(protected string $binary, protected string $passphrase, protected string $filename){}
    
    
    /**
     * Get the binary file path
     * 
     * @return string 
     */
    public function getBinary(): string
    {
        return $this->binary;
    }    
    
    /**
     * Get passphrase
     * 
     * @return string 
     */
    public function getPassphrase(): string
    {
        return $this->passphrase;
    }

     /**
     * Get path to gpg files directory 
     * 
     * @return string 
     */
    public function getFilename(): string
    {
        return $this->filename;
    }
   
    /**
     * Read BPAY Biller Information File(s)
     * $pattern can be a path to a single file or a pattern
     * if $callback is specified it will be called after each file is parsed
     * $callback structure:
     * <code>
     * function(SplFileInfo $filename) {
     *     var_dump($filename);
     * }
     * </code>
     *
     * @param \Closure $callback executed after file is read
     * @return Vector of Transaction
     * @throws \InvalidArgumentException 
     */
    public function read(?(function (string):void) $callback = null): Vector<Transaction>
    {
        $collection = Vector{};
        $files = glob($this->getFilename(), \GLOB_BRACE);
        
        if (!is_array($files)) {
            throw new InvalidArgumentException(
                sprintf('`%s` is invalid glob pattern', $this->filename));
        }

        foreach ($files as $filename) {
            $plaintext = $this->decrypt($filename);
            $transactions = $this->parseBillerFile($plaintext, $filename);               
            foreach ($transactions as $transaction) {
                $collection[] = new Transaction($transaction);
            }
            
            if (!is_null($callback)) {
                call_user_func($callback, new SplFileInfo($filename));
            }
        }
        
       return $collection;
    }
    
    /**
     * Parse biller information file plaintext into array
     * 
     * @param string $plaintext biller file in plaintext
     * @return array biller file information
     */
    protected function parseBillerFile(string $plaintext, string $filename): Vector<TransactionValues>
    {
        $return = Vector{};
        
        $handle = fopen('data://text/plain,' . $plaintext, 'r');
        
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if (7 !== sizeof($row)) {
                continue;
            }
            
            $return[] = shape(
                'crn'               => $row[0],
                'paymentType'       => $row[1],
                'amount'            => (float)$row[2],
                'date'              => new DateTime(sprintf('%s %s', $row[3], $row[4])),
                'paymentMethod'     => $row[5],
                'transactionNumber' => $row[6],
                'filename'          => sprintf('%s.gpg', pathinfo($filename, \PATHINFO_FILENAME))
            );
        }
        
        fclose($handle);
        
        return $return;
    }
    
    /**
     * Decrypt given biller information file
     *
     * @param string $filename encrypted filler information file
     * @return string decrypted file content 
     */
    protected function decrypt(string $filename): string
    {
        $pipes = null;
        $plaintext = ''; 
        
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "a")
        );
        
        $command = sprintf('%s  --batch --passphrase-fd 0 -d %s', $this->getBinary(), $filename); 
        $process = proc_open($command, $descriptorspec, $pipes); 
        if (is_resource($process)) { 
            fwrite($pipes[0], $this->getPassphrase()); 
            fclose($pipes[0]); 
            while ($string = fgets($pipes[1], 1024)) {  
                $plaintext .= $string; 
            }

            fclose($pipes[1]); 
            fclose($pipes[2]); 
        }
       
        return $plaintext; 
    }    
}
