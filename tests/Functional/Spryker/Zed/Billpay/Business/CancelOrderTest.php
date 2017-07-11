<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Billpay\Business;

use Generated\Shared\Transfer\BillpayCancelResponseTransfer;
use SprykerEco\Zed\Billpay\Business\BillpayFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Business
 * @group CancelOrderTest
 */
class CancelOrderTest extends PreauthorizeApiAdapterTest
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
