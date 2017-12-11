<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Billpay\Business;

use Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer;
use SprykerEco\Zed\Billpay\Business\BillpayFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Business
 * @group InvoiceCreatedTest
 */
class InvoiceCreatedTest extends PreauthorizeApiAdapterTest
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
