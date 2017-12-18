<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Billpay\Business;

use Generated\Shared\Transfer\BillpayCancelResponseTransfer;
use SprykerEco\Zed\Billpay\Business\BillpayFacade;
use SprykerEcoTest\Zed\Billpay\Business\Mock\OrderTransferTrait;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Business
 * @group BillpayFacadeCancelOrderTest
 */
class BillpayFacadeCancelOrderTest extends PreauthorizeApiAdapterTest
{
    use OrderTransferTrait;

    /**
     * @return void
     */
    public function testCancelOrder()
    {
        $service = new BillpayFacade();
        $service->setFactory($this->createFactory());

        $response = $service->cancelOrder($this->createOrderTransfer());

        $this->assertInstanceOf(BillpayCancelResponseTransfer::class, $response);
    }
}
