<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Billpay\Business;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\BillpayPaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

trait QuoteTransferTrait
{

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setPayment($this->createPaymentTransfer());
        $quoteTransfer->setTotals(new TotalsTransfer());
        $quoteTransfer->setShipment($this->createShipment());
        $quoteTransfer->setCustomer(new CustomerTransfer());
        $quoteTransfer->setBillingAddress($this->createAddressTransfer());
        $quoteTransfer->setShippingAddress($this->createAddressTransfer());
        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function createPaymentTransfer()
    {
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentMethod('INVOICE');
        $paymentTransfer->setBillpay($this->createBillpayPaymentTransfer());
        $paymentTransfer->setBillpayInvoice($this->createBillpayPaymentTransfer());
        return $paymentTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    private function createShipment()
    {
        $shipment = new ShipmentTransfer();
        $shipment->setMethod(new ShipmentMethodTransfer());
        return $shipment;
    }

    /**
     * @return \Generated\Shared\Transfer\BillpayPaymentTransfer
     */
    protected function createBillpayPaymentTransfer()
    {
        $payment = new BillpayPaymentTransfer();
        $payment->setPaymentMethod('INVOICE');
        $payment->setBptid('dummybptid');
        return $payment;
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createAddressTransfer()
    {
        $address = new AddressTransfer();
        $address->setIso2Code('DE');
        return $address;
    }

}
