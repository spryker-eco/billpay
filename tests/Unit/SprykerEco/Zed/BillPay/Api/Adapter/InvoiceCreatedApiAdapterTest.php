<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\BillPay\Api\Adapter;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\BillpayPaymentTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use ipl_invoice_created_request;
use ipl_xml_request;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\InvoiceCreatedApiAdapter;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayApiException;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceManager;
use SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToCountryBridge;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Api
 * @group Adapter
 * @group InvoiceCreatedApiAdapterTest
 */
class InvoiceCreatedApiAdapterTest extends Test
{

    /**
     * @return void
     */
    public function testSendRequest()
    {
        $request = $this->prepareRequest([[], [], []]);

        $invoice = new InvoiceManager(new BillpayConfig(), $this->createCountryMock());
        $editCartContent = $invoice->buildInvoiceCreatedOrderRequest($this->createOrderTransfer());

        $service = new InvoiceCreatedApiAdapter(new BillpayConfig(), $request);
        $response = $service->sendRequest($editCartContent);

        $this->assertInstanceOf(ipl_xml_request::class, $response);
    }

    /**
     * @return void
     */
    public function testSendRequestWillThrowAnException()
    {
        $this->expectException(BillpayApiException::class);

        $request = $this->prepareRequest(false);

        $invoice = new InvoiceManager(new BillpayConfig(), $this->createCountryMock());
        $cancelOrder = $invoice->buildInvoiceCreatedOrderRequest($this->createOrderTransfer());

        $service = new InvoiceCreatedApiAdapter(new BillpayConfig(), $request);
        $response = $service->sendRequest($cancelOrder);

        $this->assertInstanceOf(ipl_xml_request::class, $response);
    }

    /**
     * @param mixed $returnValue
     *
     * @return \PHPUnit_Framework_MockObject_MockObject | \ipl_invoice_created_request
     */
    protected function prepareRequest($returnValue)
    {
        $builder = $this->getMockBuilder(ipl_invoice_created_request::class);
        $builder->setMethods(['_send']);
        $builder->setConstructorArgs(['dummy_url']);

        // Create a stub for the SomeClass class.
        $stub = $builder->getMock();

        // Configure the stub.
        $stub->method('_send')
            ->willReturn($returnValue);

        return $stub;
    }

    /**
     * @param string $returnValue
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createCountryMock($returnValue = 'DEU')
    {
        $builder = $this->getMockBuilder(BillpayToCountryBridge::class);
        $builder->disableOriginalConstructor();
        $builder->setMethods(['getCountryByIso2Code']);

        // Create a stub for the SomeClass class.
        $stub = $builder->getMock();

        // Configure the stub.
        $stub->method('getCountryByIso2Code')
            ->willReturn($returnValue);

        return $stub;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setBillpayPayment(new BillpayPaymentTransfer());
        $orderTransfer->setTotals(new TotalsTransfer());
        $orderTransfer->setItems($this->createItems());
        return $orderTransfer;
    }

    /**
     * @return \ArrayObject
     */
    protected function createItems()
    {
        $items = new ArrayObject();
        $items->append(new ItemTransfer());
        return $items;
    }

}
