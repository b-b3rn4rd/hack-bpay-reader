<?hh
namespace BPAY;

class FiltersTest extends \PHPUnit_Framework_TestCase
{
    
    public function testObjectCanBeConstructedWithoutArguments()
    {
        $this->assertInstanceOf('BPAY\Filters', new Filters());
    }
    
    /**
     * @dataProvider transactionsProvider
     */
    public function testFilterByCrnFiltersCollectionByCrn($collection)
    {
        $filtered = (new Filters())->filterByCrn($collection, '12345');
        
        $this->assertEquals(2, count($filtered));
    }
    
    /**
     * @dataProvider transactionsProvider
     */
    public function testFilterPaidByVISAFiltersCollectionByVISA($collection)
    {
        $filtered = (new Filters())->filterPaidByVISA($collection);
        
        $this->assertEquals(1, count($filtered));
    }
    
    /**
     * @dataProvider transactionsProvider
     */
    public function testFilterPaidByMasterCardFiltersCollectionByMasterCard($collection)
    {
        $filtered = (new Filters())->filterPaidByMasterCard($collection);
        
        $this->assertEquals(1, count($filtered));
    }
    
    /**
     * @dataProvider transactionsProvider
     */
    public function testFilterPaidByDebitAccountFiltersCollectionByDebitAccount($collection)
    {
        $filtered = (new Filters())->filterPaidByDebitAccount($collection);
        
        $this->assertEquals(1, count($filtered));
    }
    
    /**
     * @dataProvider transactionsProvider
     */
    public function testFilterPaidByOtherCardFiltersCollectionByOtherCard($collection)
    {
        $filtered = (new Filters())->filterPaidByOtherCard($collection);
        
        $this->assertEquals(1, count($filtered));
    }
    
    /**
     * @dataProvider transactionsProvider
     */
    public function testFilterIsPaymentFiltersCollectionByPayment($collection)
    {
        $filtered = (new Filters())->filterIsPayment($collection);
        
        $this->assertEquals(2, count($filtered));
    }
    
    /**
     * @dataProvider transactionsProvider
     */
    public function testFilterIsErrorCorrectionFiltersCollectionByErrorCorrection($collection)
    {
        $filtered = (new Filters())->filterIsErrorCorrection($collection);
        
        $this->assertEquals(1, count($filtered));
    }
    
    /**
     * @dataProvider transactionsProvider
     */
    public function testFilterIsReversalFiltersCollectionByReversal($collection)
    {
        $filtered = (new Filters())->filterIsReversal($collection);
        
        $this->assertEquals(1, count($filtered));
    }
    
    /**
     * @dataProvider transactionsProvider
     */
    public function testFilterByFilenameFiltersCollectionByFilename($collection)
    {
        $filtered = (new Filters())->filterByFilename($collection, 'file1.txt.gpg');
        
        $this->assertEquals(2, count($filtered));
    }
    
    public function transactionsProvider()
    {
        $collection = Vector{};
        
        $shapes = array(
            shape(
                'crn'               => '12345',
                'paymentType'       => Transaction::PAYMENT_TYPE_PAYMENT,
                'amount'            => 50.00,
                'date'              => new \DateTime('31.08.2012'),
                'paymentMethod'     => Transaction::PAYMENT_METHOD_VISA,
                'transactionNumber' => '123456789',
                'filename'          => 'file1.txt.gpg'
            ),
            shape(
                'crn'               => '22222',
                'paymentType'       => Transaction::PAYMENT_TYPE_REVERSAL,
                'amount'            => 30.00,
                'date'              => new \DateTime('31.08.2012'),
                'paymentMethod'     => Transaction::PAYMENT_METHOD_MASTERCARD,
                'transactionNumber' => '222222222',
                'filename'          => 'file1.txt.gpg'
            ),
             shape(
                'crn'               => '12345',
                'paymentType'       => Transaction::PAYMENT_TYPE_ERROR_CORRECTION,
                'amount'            => 30.00,
                'date'              => new \DateTime('31.08.2012'),
                'paymentMethod'     => Transaction::PAYMENT_METHOD_DEBIT_ACCOUNT,
                'transactionNumber' => '222222222',
                'filename'          => 'file2.txt.gpg'
            ), 
            shape(
                'crn'               => '44444',
                'paymentType'       => Transaction::PAYMENT_TYPE_PAYMENT
,
                'amount'            => 100.00,
                'date'              => new \DateTime('31.08.2012'),
                'paymentMethod'     => Transaction::PAYMENT_METHOD_OTHER_CARD,
                'transactionNumber' => '4444444444',
                'filename'          => 'file2.txt.gpg'
            ),
        );

        foreach ($shapes as $shape) {
            $transaction = new Transaction($shape);
            $collection[] = $transaction;
        }
        
        return array(array($collection));    
    }     
}    
