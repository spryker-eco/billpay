<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Billpay\Business;

use Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer;
use SprykerEco\Zed\Billpay\Business\BillpayFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Business
 * @group PrescorePaymentTest
 */
class PrescorePaymentTest extends PreauthorizeApiAdapterTest
{

    use QuoteTransferTrait;

    /**
     * @return void
     */
    public function testPrescorePayment()
    {
        $service = new BillpayFacade();
        $service->setFactory($this->createFactory());

        $response = $service->prescorePayment($this->createQuoteTransfer());

        $this->assertInstanceOf(BillpayPrescoringTransactionResponseTransfer::class, $response);
    }

}
