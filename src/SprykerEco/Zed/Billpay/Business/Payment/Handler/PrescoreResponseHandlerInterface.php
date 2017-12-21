<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Zed\Billpay\Business\Payment\Handler;

use Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PrescoreResponseHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function handle(BillpayPrescoringTransactionResponseTransfer $responseTransfer, QuoteTransfer $quoteTransfer);
}