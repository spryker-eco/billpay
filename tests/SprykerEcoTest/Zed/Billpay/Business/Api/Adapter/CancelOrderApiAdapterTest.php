<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Billpay\Business\Api\Adapter;

use Generated\Shared\Transfer\BillpayPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use ipl_cancel_request;
use ipl_xml_request;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\CancelOrderApiAdapter;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayApiException;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceManager;
use SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToCountryBridge;
use SprykerEcoTest\Zed\Billpay\Business\BillpayUnitTest;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Api
 * @group Adapter
 * @group CancelOrderApiAdapterTest
 */
class CancelOrderApiAdapterTest extends BillpayUnitTest
{
    /**
     * @return void
     */
    public function testSendRequest()
    {
        $request = $this->prepareRequest([[], [], []]);

        $invoice = new InvoiceManager(new BillpayConfig(), $this->createCountryMock());
        $cancelOrder = $invoice->buildCancelOrderRequest($this->createOrderTransfer());

        $service = new CancelOrderApiAdapter(new BillpayConfig(), $request);
        $response = $service->sendRequest($cancelOrder);

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
        $cancelOrder = $invoice->buildCancelOrderRequest($this->createOrderTransfer());

        $service = new CancelOrderApiAdapter(new BillpayConfig(), $request);
        $response = $service->sendRequest($cancelOrder);

        $this->assertInstanceOf(ipl_xml_request::class, $response);
    }

    /**
     * @param mixed $returnValue
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function prepareRequest($returnValue)
    {
        $builder = $this->getMockBuilder(ipl_cancel_request::class);
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
    protected function createOrderTransfer(): OrderTransfer
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setBillpayPayment(new BillpayPaymentTransfer());
        $orderTransfer->setTotals(new TotalsTransfer());
        return $orderTransfer;
    }
}
