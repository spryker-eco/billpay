<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerEco\Zed\Billpay\Business\BillpayBusinessFactory getFactory()
 */
class BillpayFacade extends AbstractFacade implements BillpayFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer
     */
    public function prescorePayment(QuoteTransfer $quoteTransfer)
    {
        $billpayResponseTransfer = $this
            ->getFactory()
            ->createPrescorePaymentRequest()
            ->request($quoteTransfer);

        $this
            ->getFactory()
            ->createPrescoreResponseHandler()
            ->handle($billpayResponseTransfer, $quoteTransfer);

        return $billpayResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer
     */
    public function preauthorizePayment(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $orderTransfer->getBillpayPayment()->getPaymentMethod();

        $billpayResponseTransfer = $this
            ->getFactory()
            ->createPreauthorizePaymentRequest($paymentMethod)
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createPreauthorizeResponseHandler()
            ->handle($billpayResponseTransfer);

        return $billpayResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer
     */
    public function invoiceCreated(OrderTransfer $orderTransfer)
    {
        $billpayResponseTransfer = $this
            ->getFactory()
            ->createInvoiceCreatedPaymentRequest()
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createInvoiceCreatedResponseHandler()
            ->handle($billpayResponseTransfer, $orderTransfer);

        return $billpayResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayCancelResponseTransfer
     */
    public function cancelOrder(OrderTransfer $orderTransfer)
    {
        $billpayResponseTransfer = $this
            ->getFactory()
            ->createCancelOrderRequest()
            ->request($orderTransfer);

        $this
            ->getFactory()
            ->createCancelResponseHandler()
            ->handle($billpayResponseTransfer, $orderTransfer);

        return $billpayResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayEditCartResponseTransfer
     */
    public function editCartContent(OrderTransfer $orderTransfer, ItemTransfer $itemTransfer)
    {
        $billpayResponseTransfer = $this
            ->getFactory()
            ->createEditCartContentTransactionHandler()
            ->request($orderTransfer);

        $this->getFactory()->createEditCartResponseHandler()->handle($billpayResponseTransfer, $itemTransfer);

        return $billpayResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this
            ->getFactory()
            ->createOrderSaver()
            ->saveOrderPayment($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * Specification
     * - gets the country transfer based on iso2code
     *
     * @api
     *
     * @param string $iso2code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountry($iso2code)
    {
        return $this->getFactory()->getCountry()->getCountryByIso2Code($iso2code);
    }
}
