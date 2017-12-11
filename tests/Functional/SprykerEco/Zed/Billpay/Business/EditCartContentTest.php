<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Billpay\Business;

use Generated\Shared\Transfer\BillpayEditCartResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use SprykerEco\Zed\Billpay\Business\BillpayFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Business
 * @group EditCartContentTest
 */
class EditCartContentTest extends PreauthorizeApiAdapterTest
{
    use OrderTransferTrait;

    /**
     * @return void
     */
    public function testEditCartContent()
    {
        $service = new BillpayFacade();
        $service->setFactory($this->createFactory());

        $response = $service->editCartContent($this->createOrderTransfer(), $this->createItemTransfer());

        $this->assertInstanceOf(BillpayEditCartResponseTransfer::class, $response);
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }
}
