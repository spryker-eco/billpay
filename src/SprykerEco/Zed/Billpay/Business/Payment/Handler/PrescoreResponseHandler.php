<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Handler;

use Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class PrescoreResponseHandler extends AbstractResponseHandler implements PrescoreResponseHandlerInterface
{
    const METHOD = 'PRESCORE';

    /**
     * @param \Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function handle(
        BillpayPrescoringTransactionResponseTransfer $responseTransfer,
        QuoteTransfer $quoteTransfer
    ) {
        $this->logHeader($responseTransfer->getHeader(), self::METHOD);

        //we need to set bptid that we get in prescoring result
        $quoteTransfer
            ->getPayment()
            ->getBillpay()
            ->setBptid(
                $responseTransfer->getHeader()->getBptid()
            );
    }
}
