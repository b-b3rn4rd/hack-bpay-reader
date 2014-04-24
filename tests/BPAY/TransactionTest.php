<?hh
namespace BPAY;

error_reporting(E_ALL);
   
class TransactionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testExceptionIsRaisedForInvalidConstructorArgument()
    {
        new Transaction(null);
    }
    
    
    public function testObjectCanBeConstructedUsingShapeArgument()
    {
        $this->assertInstanceOf('BPAY\Transaction', new Transaction(shape()));
    }
    
    /*
    public function testConstructorInvokesSetPropertiesMethod()
    {
        $this->markTestSkipped("Can't mock hack method");

        $transaction = $this->getMockBuilder('\BPAY\Transaction')
            ->disableOriginalConstructor()
            ->setMethods(array('setProperties'))
            ->getMock();
       
       $transaction->expects($this->once())
           ->method('setProperties'); 
        
        $transaction->__construct(shape());
    }*/
    

    public function testSetPropertiesSetTransactionProperties()
    {
        $shape = shape(
            'crn'               => 'testCrn',
            'paymentType'       => 'testPaymentType',
            'amount'            => 10.00,
            'date'              => new \DateTime('31.08.2012'),
            'paymentMethod'     => 'testPaymentMethod',
            'transactionNumber' => 'testTransactionNumber',
            'filename'          => 'abc.gpg'
        );
        
        $transaction = new Transaction($shape);

        $this->assertEquals($transaction->getCRN(), $shape['crn']);
        $this->assertEquals($transaction->getPaymentType(), $shape['paymentType']);
        $this->assertEquals($transaction->getAmount(), $shape['amount']);
        $this->assertEquals($transaction->getDate(), $shape['date']);
        $this->assertEquals($transaction->getPaymentMethod(), $shape['paymentMethod']);
        $this->assertEquals($transaction->getTransactionNumber(), $shape['transactionNumber']);
        $this->assertEquals($transaction->getFilename(), $shape['filename']);
        
    }

    public function testIsPaymentDeterminePaymentType()
    {
        $transaction = new Transaction(shape(
            'paymentType' => Transaction::PAYMENT_TYPE_PAYMENT
        ));
        $this->assertTrue($transaction->isPayment());

    }
    
    public function testIsReversalDetermineReversalPaymentType()
    {
        $transaction = new Transaction(shape(
            'paymentType' => Transaction::PAYMENT_TYPE_REVERSAL
        ));
        $this->assertTrue($transaction->isReversal());
    }
    
    public function testIsErrorCorrectionDetermineErrorCorrection()
    {
        $transaction = new Transaction(shape(
            'paymentType' => Transaction::PAYMENT_TYPE_ERROR_CORRECTION
        ));
        $this->assertTrue($transaction->isErrorCorrection());
    }
    
    public function testIsPaidByVISADetermineVISAPaymentMethod()
    {
        $transaction = new Transaction(shape(
            'paymentMethod' => Transaction::PAYMENT_METHOD_VISA
        ));

        $this->assertTrue($transaction->isPaidByVISA());
    }

    public function testIsPaidByMasterCardDetermineMasterCardPaymentMethod()
    {
        $transaction = new Transaction(shape(
            'paymentMethod' => Transaction::PAYMENT_METHOD_MASTERCARD
        ));

        $this->assertTrue($transaction->isPaidByMasterCard());
    }

    public function testIsPaidByDebitAccountDetermineDebitAccountPaymentMethod()
    {
        $transaction = new Transaction(shape(
            'paymentMethod' => Transaction::PAYMENT_METHOD_DEBIT_ACCOUNT
        ));

        $this->assertTrue($transaction->isPaidByDebitAccount());
    }
}    
