<?hh
namespace BPAY;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testExceptionIsRaisedForInvalidConstructorArgument()
    {
        new Reader(null);
    }
    
    
    public function testObjectCanBeConstructedUsingArgument()
    {
        $this->assertInstanceOf('BPAY\Reader', new Reader('root/usr/bin/gpg', 'testing', 'root/tmp/files'));
    }
    
    public function testConstructorImplicitlySetsProperties()
    {
        $binary = 'root/usr/bin/gpg';
        $passphrase = 'testing';
        $filename = 'root/tmp/files';
        
        $reader = new Reader($binary, $passphrase, $filename);
        
        $this->assertEquals($binary, $reader->getBinary());
        $this->assertEquals($passphrase, $reader->getPassphrase());
        $this->assertEquals($filename, $reader->getFilename());
    }
    
    public function testParseBillerFileConvertsCSVIntoVector()
    {
        $filename = 'text.txt.gpg';
        $plaintext = "357006084,05,1.00,03/07/06,22:18:39,001,BBL200607030010712478\n
546317,05,32.00,03/07/06,22:19:16,001,ANZ20060703477748\n
533666,25,20.00,03/07/06,22:20:03,201,STG2006070322335\n";
        
        $reader = new Reader('root/usr/bin/gpg', 'testing', 'root/tmp/files');
        $method = new \ReflectionMethod($reader, 'parseBillerFile');
        $method->setAccessible(true);
        
        $transactions = $method->invoke($reader, $plaintext, $filename);
        
        $this->assertEquals(3, count($transactions));
        
        $transaction = $transactions[0];
        
        $this->assertEquals($transaction['crn'], '357006084');
        $this->assertEquals($transaction['paymentType'], '05');
        $this->assertEquals($transaction['amount'], 1.00);
        $this->assertEquals($transaction['date'], new \DateTime('07.03.2006 22:18:39'));
        $this->assertEquals($transaction['paymentMethod'], '001');
        $this->assertEquals($transaction['transactionNumber'], 'BBL200607030010712478');
        $this->assertEquals($transaction['filename'], $filename);
    }
}    
