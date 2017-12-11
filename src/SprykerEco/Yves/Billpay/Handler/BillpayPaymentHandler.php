<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay\Handler;

use Generated\Shared\Transfer\BillpayPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Yves\Billpay\Exception\PaymentMethodNotFoundException;

class BillpayPaymentHandler
{
    /**
     * @var array
     */
    protected static $paymentMethods = [
        BillpayConstants::PAYMENT_METHOD_INVOICE => BillpayConstants::INVOICE,
    ];

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(QuoteTransfer $quoteTransfer)
    {
        $paymentSelection = $quoteTransfer
            ->getPayment()
            ->getPaymentSelection();

        $this->setPaymentProviderAndMethod($quoteTransfer, $paymentSelection);
        $this->setBillpay($quoteTransfer, $paymentSelection);

        return $quoteTransfer;
    }

    /**
     * @param string $clientIp
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addClientIpToQuote($clientIp, QuoteTransfer $quoteTransfer)
    {
        $billpayTransfer = $quoteTransfer->getPayment()->getBillpay();
        $billpayTransfer->setClientIp($clientIp);

        $quoteTransfer->getPayment()->setBillpay($billpayTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setPaymentProviderAndMethod(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $quoteTransfer
            ->getPayment()
            ->setPaymentProvider(BillpayConstants::PAYMENT_PROVIDER)
            ->setPaymentMethod(self::$paymentMethods[$paymentSelection]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setBillpay(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $billpayTransfer = $this->getBillpayTransfer($quoteTransfer, $paymentSelection);

        $this->addPrescoringResponseToPaymentTransfer($quoteTransfer, $billpayTransfer);

        $quoteTransfer
            ->getPayment()
            ->setBillpay($billpayTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @throws \SprykerEco\Yves\Billpay\Exception\PaymentMethodNotFoundException
     *
     * @return \Generated\Shared\Transfer\BillpayPaymentTransfer
     */
    protected function getBillpayTransfer(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $paymentMethod = ucfirst($paymentSelection);
        $method = 'get' . $paymentMethod;
        $paymentTransfer = $quoteTransfer->getPayment();
        if (!method_exists($paymentTransfer, $method) || ($quoteTransfer->getPayment()->$method() === null)) {
            throw new PaymentMethodNotFoundException(
                sprintf('Selected payment method "%s" not found in PaymentTransfer', $paymentMethod)
            );
        }
        $billpayTransfer = $quoteTransfer->getPayment()->$method();

        return $billpayTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\BillpayPaymentTransfer $billpayPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayPaymentTransfer
     */
    protected function addPrescoringResponseToPaymentTransfer(
        QuoteTransfer $quoteTransfer,
        BillpayPaymentTransfer $billpayPaymentTransfer
    ) {
        $billpayPaymentTransfer->setBillpayPrescoringTransactionResponse(
            $quoteTransfer->getPayment()->getBillpay()->getBillpayPrescoringTransactionResponse()
        );

        return $billpayPaymentTransfer;
    }
}
