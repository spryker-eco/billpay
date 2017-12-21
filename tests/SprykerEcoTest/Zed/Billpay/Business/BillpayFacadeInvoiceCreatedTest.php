<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Billpay\Business;

use Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer;
use SprykerEco\Zed\Billpay\Business\BillpayFacade;
use SprykerEcoTest\Zed\Billpay\Business\Mock\OrderTransferTrait;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Business
 * @group BillpayFacadeInvoiceCreatedTest
 */
class BillpayFacadeInvoiceCreatedTest extends PreauthorizeApiAdapterTest
{
    use OrderTransferTrait;

    /**
     * @return void
     */
    public function testInvoiceCreated()
    {
        $this->createBillpayPayment();

        $service = new BillpayFacade();
        $service->setFactory($this->createFactory());

        $response = $service->invoiceCreated($this->createOrderTransfer());

        $this->assertInstanceOf(BillpayInvoiceCreatedResponseTransfer::class, $response);
    }
}
