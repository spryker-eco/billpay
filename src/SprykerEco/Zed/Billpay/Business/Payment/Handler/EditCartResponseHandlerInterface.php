<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Zed\Billpay\Business\Payment\Handler;

use Generated\Shared\Transfer\BillpayEditCartResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface EditCartResponseHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\BillpayEditCartResponseTransfer|\Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return void
     */
    public function handle(BillpayEditCartResponseTransfer $responseTransfer, ItemTransfer $item);
}