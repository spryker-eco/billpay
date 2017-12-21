<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Zed\Billpay\Business\Payment\Handler;

use Generated\Shared\Transfer\BillpayCancelResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface CancelResponseHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\BillpayCancelResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function handle(BillpayCancelResponseTransfer $responseTransfer, OrderTransfer $orderTransfer);
}