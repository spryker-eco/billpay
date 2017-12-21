<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Zed\Billpay\Business\Payment\Handler;

use Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface PreauthorizeResponseHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function handle(BillpayPreauthorizeTransactionResponseTransfer $responseTransfer, OrderTransfer $orderTransfer);
}
