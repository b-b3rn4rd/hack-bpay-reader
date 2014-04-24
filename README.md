# ABOUT
----
Hack BPAY Reader is an experemental library written in [hack][2]. It decrypts and parses BPAY biller information file(s).

#REQUIREMENTS
---
Hack BPAY Reader requires hhvm version 3 or greater, **valid** public and private key pair and installed gnupg.

#INSTALLATION
---
Install Hack BPAY Reader using Composer:
```json
{
    "require": {
        "b-b3rn4rd/hack-bpay-reader": "dev-master"
    }
}
```

#USAGE
---
###Basic usage
```php
<?hh 

require 'vendor/autoload.php';

use BPAY\Transaction;
use BPAY\Reader;
use BPAY\Filters;

function main():void {
    // passphrase used to decrypt the BPAY files. It's important that it match the passphrase provided to the bank.
    $passphrase = 'testing';
    
    // absolute path to GnuPG binary
    $gpgbinary = '/usr/bin/gpg';
    
    // path where BPAY biller information file(s) are stored
    $filespath = '/home/bernard/gpg/*.gpg';

    $reader = new Reader($gpgbinary, $passphrase, $filespath);
    $transactions = $reader->read();


    foreach ($transactions as $transaction) {
        /* ... */
    }
}
main();
```

### Using optional callback
```php
// move processed files to another folder
$processedDir = '/home/bernard/gpg/processed';
$transactions = $reader->read($filename ==> {
    rename($filename, sprintf('%s/%s', $processedDir, $filename->getFilename()));
});
```

### Using predefined filters
```php
$filteredTransactions = (new Filters())->filterIsPayment($transactions);
```

#### Available filters
```php
class Filters
{    
    /**
     * Filter transactions by customer reference number
     *
     * @param Vector<Transaction> $collection transactions collection
     * @param string $crn customer reference number 
     * @return Vector<Transaction> transactions filtered by customer reference number 
     */
    public function filterByCrn(Vector<Transaction> $collection, string $crn): Vector<Transaction>;
    
    /**
     * Filter transactions by filename
     *
     * @param Vector<Transaction> $collection transactions collection
     * @param string $filename source filename 
     * @return Vector<Transaction> transactions filtered by source filename 
     */
    public function filterByFilename(Vector<Transaction> $collection, string $filename): Vector<Transaction>;
    
    /**
     * Filter transactions by VISA card
     *
     * @param Vector<Transaction> $collection transactions collection
     * @return Vector<Transaction> transactions filtered by VISA card 
     */
    public function filterPaidByVISA(Vector<Transaction> $collection): Vector<Transaction>;

    /**
     * Filter transactions by Master Card card
     *
     * @param Vector<Transaction> $collection transactions collection
     * @return Vector<Transaction> transactions filtered by Master Card card 
     */
    public function filterPaidByMasterCard(Vector<Transaction> $collection): Vector<Transaction>;

    /**
     * Filter transactions by debit account 
     *
     * @param Vector<Transaction> $collection transactions collection
     * @return Vector<Transaction> transactions filtered by debit account 
     */
    public function filterPaidByDebitAccount(Vector<Transaction> $collection): Vector<Transaction>;

    /**
     * Filter transactions by debit account 
     *
     * @param Vector<Transaction> $collection transactions collection
     * @return Vector<Transaction> transactions filtered by debit account 
     */
    public function filterPaidByOtherCard(Vector<Transaction> $collection): Vector<Transaction>;

   /**
    * Filter transactions by payment type is payment 
    *
    * @param Vector<Transaction> $collection transactions collection
    * @return Vector<Transaction> transactions filtered by payment type is payment 
    */
    public function filterIsPayment(Vector<Transaction> $collection): Vector<Transaction>;

   /**
    * Filter transactions by payment type is reversal 
    *
    * @param Vector<Transaction> $collection transactions collection
    * @return Vector<Transaction> transactions filtered by payment type is reversal 
    */
    public function filterIsReversal(Vector<Transaction> $collection): Vector<Transaction>;

   /**
    * Filter transactions by payment type is error correction
    *
    * @param Vector<Transaction> $collection transactions collection
    * @return Vector<Transaction> transactions filtered by payment type is error correction 
    */
    public function filterIsErrorCorrection(Vector<Transaction> $collection): Vector<Transaction>;
}    
```
#LICENSE
----

[Unlicened][1]



[1]:http://unlicense.org/UNLICENSE
[2]:http://hacklang.org/
