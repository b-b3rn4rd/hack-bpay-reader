<?hh // strict

namespace BPAY;

class Filters
{    
    /**
     * Filter transactions by customer reference number
     *
     * @param Vector<Transaction> $collection transactions collection
     * @param string $crn customer reference number 
     * @return Vector<Transaction> transactions filtered by customer reference number 
     */
    public function filterByCrn(Vector<Transaction> $collection, string $crn): Vector<Transaction>
    {
        return $collection->filter($transaction ==> $transaction->getCrn() === $crn);
    }
    
    /**
     * Filter transactions by filename
     *
     * @param Vector<Transaction> $collection transactions collection
     * @param string $filename source filename 
     * @return Vector<Transaction> transactions filtered by source filename 
     */
    public function filterByFilename(Vector<Transaction> $collection, string $filename): Vector<Transaction>
    {
        return $collection->filter($transaction ==> $transaction->getFilename() === $filename);
    }
    
    /**
     * Filter transactions by VISA card
     *
     * @param Vector<Transaction> $collection transactions collection
     * @return Vector<Transaction> transactions filtered by VISA card 
     */
    public function filterPaidByVISA(Vector<Transaction> $collection): Vector<Transaction>
    {
        return $this->filterByPaymentMethod($collection, Transaction::PAYMENT_METHOD_VISA);
    }    

    /**
     * Filter transactions by Master Card card
     *
     * @param Vector<Transaction> $collection transactions collection
     * @return Vector<Transaction> transactions filtered by Master Card card 
     */
    public function filterPaidByMasterCard(Vector<Transaction> $collection): Vector<Transaction>
    {
        return $this->filterByPaymentMethod($collection, Transaction::PAYMENT_METHOD_MASTERCARD);
    }

    /**
     * Filter transactions by debit account 
     *
     * @param Vector<Transaction> $collection transactions collection
     * @return Vector<Transaction> transactions filtered by debit account 
     */
    public function filterPaidByDebitAccount(Vector<Transaction> $collection): Vector<Transaction>
    {
        return $this->filterByPaymentMethod($collection, Transaction::PAYMENT_METHOD_DEBIT_ACCOUNT);
    }

    /**
     * Filter transactions by debit account 
     *
     * @param Vector<Transaction> $collection transactions collection
     * @return Vector<Transaction> transactions filtered by debit account 
     */
    public function filterPaidByOtherCard(Vector<Transaction> $collection): Vector<Transaction>
    {
        return $this->filterByPaymentMethod($collection, Transaction::PAYMENT_METHOD_OTHER_CARD);
    }

   /**
    * Filter transactions by payment type is payment 
    *
    * @param Vector<Transaction> $collection transactions collection
    * @return Vector<Transaction> transactions filtered by payment type is payment 
    */
    public function filterIsPayment(Vector<Transaction> $collection): Vector<Transaction>
    {
        return $this->filterByPaymentType($collection, Transaction::PAYMENT_TYPE_PAYMENT);
    }

   /**
    * Filter transactions by payment type is reversal 
    *
    * @param Vector<Transaction> $collection transactions collection
    * @return Vector<Transaction> transactions filtered by payment type is reversal 
    */
    public function filterIsReversal(Vector<Transaction> $collection): Vector<Transaction>
    {
        return $this->filterByPaymentType($collection, Transaction::PAYMENT_TYPE_REVERSAL);
    }

   /**
    * Filter transactions by payment type is error correction
    *
    * @param Vector<Transaction> $collection transactions collection
    * @return Vector<Transaction> transactions filtered by payment type is error correction 
    */
    public function filterIsErrorCorrection(Vector<Transaction> $collection): Vector<Transaction>
    {
        return $this->filterByPaymentType($collection, Transaction::PAYMENT_TYPE_ERROR_CORRECTION);
    }

    /**
     * Filter transactions by payment instruction type
     *
     * @param Vector<Transaction> $collection transactions collection
     * @param string $paymentType payment instruction type 
     * @return Vector<Transaction> transactions filtered by payment type 
     */
    protected function filterByPaymentType(Vector<Transaction> $collection, string $paymentType): Vector<Transaction>
    {
        return $collection->filter($transaction ==> $transaction->getPaymentType() === $paymentType);
    }
    
    /**
     * Filter transactions by payment method
     *
     * @param Vector<Transaction> $collection transactions collection
     * @param string $paymentMethod payment method
     * @return Vector<Transaction> transactions filtered by payment method 
     */
    protected function filterByPaymentMethod(Vector<Transaction> $collection, string $paymentMethod): Vector<Transaction>
    {
        return $collection->filter($transaction ==> $transaction->getPaymentMethod() === $paymentMethod);
    }
}    

