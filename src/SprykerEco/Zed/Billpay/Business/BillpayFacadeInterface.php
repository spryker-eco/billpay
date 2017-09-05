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

/**
 * @method \SprykerEco\Zed\Billpay\Business\BillpayBusinessFactory getFactory()
 */
interface BillpayFacadeInterface
{

    /**
     * Specification:
     * - Sends pre-score payment request to Billpay gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer
     */
    public function prescorePayment(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Sends pre-authorize payment request to Billpay gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer
     */
    public function preauthorizePayment(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Sends invoice created request to Billpay gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer
     */
    public function invoiceCreated(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Sends cancel order request to Billpay gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayCancelResponseTransfer
     */
    public function cancelOrder(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Sends edit cart content request to Billpay gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayEditCartResponseTransfer
     */
    public function editCartContent(OrderTransfer $orderTransfer, ItemTransfer $itemTransfer);

    /**
     * Specification:
     * - Saves order payment method data according to quote and checkout response transfer data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

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
    public function getCountry($iso2code);

}
