<?hh // strict

namespace BPAY;

use \DateTime;


newtype TransactionValues = shape(
     'crn'               => string,
     'paymentType'       => string,
     'amount'            => float,
     'date'              => \DateTime,
     'paymentMethod'     => string,
     'transactionNumber' => string,
     'filename'          => string);

class Transaction
{
    /**
     * Customer Reference Number 
     *
     * @var string 
     */
    protected ?string $crn = null;
    
    /**
     * Payment Instruction Type 
     *
     * @var string 
     */
    protected ?string $paymentType = null;
    
    /**
     * Amount 
     *
     * @var decimal 
     */
    protected ?float $amount = null;
    
    /**
     * Date & Time of Payment 
     *
     * @var \DateTime 
     */
    protected ?DateTime $date = null;
    
    /**
     * Payment Method 
     *
     * @var int 
     */
    protected ?string $paymentMethod = null;
    
    /**
     * Payment file name
     *
     * @var string
     */    
    protected string $filename = '';

    /**
     * Transaction Receipt Number
     *
     * @var string 
     */
    protected ?string $transactionNumber = null;
    
    /**
     * Payment Instruction Type (05 = Payment)
     */
    const string PAYMENT_TYPE_PAYMENT = '05';
    
    /**
     *  Payment Instruction Type (15 = Error Correction)
     */
    const string PAYMENT_TYPE_ERROR_CORRECTION = '15';
    
    /**
     *  Payment Instruction Type (25 = Reversal)
     */
    const string PAYMENT_TYPE_REVERSAL = '25';
    
    /**
     *  Payment Method (101 = Visa)
     */
    const string PAYMENT_METHOD_VISA = '101';
    
    /**
     *  Payment Method (001 = Debit Account)
     */
    const string PAYMENT_METHOD_DEBIT_ACCOUNT = '001';
    
    /**
     *  Payment Method (201 = MasterCard)
     */
    const string PAYMENT_METHOD_MASTERCARD = '201';
    
    /**
     * Payment Method (301 = Other Credit Card)
     */
    const string PAYMENT_METHOD_OTHER_CARD  = '301';
    
    /**
     * Create new transaction
     * 
     * @param array $properties transaction properties
     */
    public function __construct(TransactionValues $properties)
    {
        $this->setProperties($properties);
    }
    
    /**
     * Get the number by which the Biller identifies 
     * the account that is being paid
     *
     * @return string customer reference number 
     */
    public function getCRN(): ?string
    {
        return $this->crn;
    }
    
    /**
     * Get code indicating the type of instruction
     *
     * @return string payment instruction type 
     */
    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }
    
    /**
     * The amount of the Payment 
     *
     * @return float|null 
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }
    
    /**
     * Get date and time that the Payment or Error Correction 
     * was accepted by the Payer Institution
     *
     * @return \DateTime 
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }
    
    /**
     * Get code indicating the method of Payment
     *
     * @return string payment method 
     */
    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }
    
    /**
     * Get receipt number provided to the customer 
     * on successful completion of a payment
     *
     * @return string transaction receipt number
     */
    public function getTransactionNumber(): ?string
    {
        return $this->transactionNumber;
    }
    
    /**
     * Determine if transaction is payment
     * 
     * @return boolean 
     */
    public function isPayment(): bool
    {
        return ($this->getPaymentType() == self::PAYMENT_TYPE_PAYMENT);
    }
    
    /**
     * Determine if payment is reversal
     * 
     * @return boolean 
     */    
    public function isReversal(): bool
    {
        return ($this->getPaymentType() == self::PAYMENT_TYPE_REVERSAL);
    }
    
    /**
     * Determine if payment is error correction
     * 
     * @return boolean 
     */    
    public function isErrorCorrection(): bool
    {
        return ($this->getPaymentType() == self::PAYMENT_TYPE_ERROR_CORRECTION);
    }
    
    /**
     * Determine if payment method is VISA
     * 
     * @return boolean 
     */
    public function isPaidByVISA(): bool
    {
        return ($this->getPaymentMethod() == self::PAYMENT_METHOD_VISA);
    }

    /**
     * Determine if payment method is Master Card
     * 
     * @return boolean 
     */
    public function isPaidByMasterCard(): bool
    {
        return ($this->getPaymentMethod() == self::PAYMENT_METHOD_MASTERCARD);
    }
    

    /**
     * Determine if payment method is Debit Account
     * 
     * @return boolean 
     */    
    public function isPaidByDebitAccount(): bool
    {
        return ($this->getPaymentMethod() == self::PAYMENT_METHOD_DEBIT_ACCOUNT);
    }
    

    /**
     * Determine if payment method is by other card
     * 
     * @return boolean 
     */    
    public function isPaidByOtherCard(): bool
    {
        return ($this->getPaymentMethod() == self::PAYMENT_METHOD_OTHER_CARD);
    }

    /**
     * Get filename associated with given transaction
     *
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Set the number by which the Biller identifies 
     * the account that is being paid
     *
     * @return string customer reference number 
     */
    public function setCRN(string $crn): \this
    {
        $this->crn = $crn;
        return $this;
    }
    
    /**
     * Set code indicating the type of instruction
     *
     * @return string payment instruction type 
     */
    protected function setPaymentType(string $paymentType): \this
    {
        $this->paymentType = $paymentType;
        return $this;
    }
    
    /**
     * Set the amount of the payment 
     *
     * @return float|null 
     */
    protected function setAmount(float $amount): \this
    {
        $this->amount = $amount;
        return $this;
    }
    
    /**
     * Set date and time that the Payment or Error Correction 
     * was accepted by the Payer Institution
     *
     * @return \DateTime 
     */
    protected function setDate(?DateTime $date): \this
    {
        $this->date = $date;
        return $this;
    }
    
    /**
     * Set code indicating the method of Payment
     *
     * @return string payment method 
     */
    protected function setPaymentMethod(string $paymentMethod): \this
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }
    
    /**
     * Set receipt number provided to the customer 
     * on successful completion of a payment
     *
     * @return string transaction receipt number
     */
    protected function setTransactionNumber(string $transactionNumber): \this
    {
        $this->transactionNumber = $transactionNumber;
        return $this;
    }

    protected function setFilename(string $filename): \this
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Set transaction properties
     * 
     * @param array $properties properties
     * @return null
     */
    protected function setProperties(TransactionValues $properties): void
    {
        foreach ($properties as $property => $value) {

            $camelizedProperty = preg_replace_callback('/_(.{1})/', function(string $matches){
                return strtoupper($matches[0]);
            }, $property);
            
            $method = sprintf('set%s', ucfirst($camelizedProperty));
            
            if (method_exists($this, $method)) {
                call_user_func(array($this, $method), $value);
            }
        }
    }    
}
