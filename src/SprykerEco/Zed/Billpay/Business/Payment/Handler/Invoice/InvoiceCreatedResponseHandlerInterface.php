<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Zed\Billpay\Business\Payment\Handler\Invoice;

use Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface InvoiceCreatedResponseHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function handle(BillpayInvoiceCreatedResponseTransfer $responseTransfer, OrderTransfer $orderTransfer);
}