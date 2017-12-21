<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Billpay\Business\Api\Adapter;

use ArrayObject;
use Generated\Shared\Transfer\BillpayPaymentTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use ipl_edit_cart_content_request;
use ipl_xml_request;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\EditCartContentApiAdapter;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayApiException;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceManager;
use SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToCountryBridge;
use SprykerEcoTest\Zed\Billpay\Business\BillpayUnitTest;
use SprykerEcoTest\Zed\Billpay\Business\Mock\TotalsTransferTrait;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Api
 * @group Adapter
 * @group EditCartContentApiAdapterTest
 */
class EditCartContentApiAdapterTest extends BillpayUnitTest
{
    use TotalsTransferTrait;

    /**
     * @return void
     */
    public function testSendRequest()
    {
        $request = $this->prepareRequest([[], [], []]);

        $invoice = new InvoiceManager(new BillpayConfig(), $this->createCountryMock());
        $editCartContent = $invoice->buildEditCartContentRequest($this->createOrderTransfer());

        $service = new EditCartContentApiAdapter(new BillpayConfig(), $request);
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
        $cancelOrder = $invoice->buildEditCartContentRequest($this->createOrderTransfer());

        $service = new EditCartContentApiAdapter(new BillpayConfig(), $request);
        $response = $service->sendRequest($cancelOrder);

        $this->assertInstanceOf(ipl_xml_request::class, $response);
    }

    /**
     * @param mixed $returnValue
     *
     * @return \PHPUnit_Framework_MockObject_MockObject | \ipl_edit_cart_content_request
     */
    protected function prepareRequest($returnValue)
    {
        $builder = $this->getMockBuilder(ipl_edit_cart_content_request::class);
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
        $orderTransfer->setTotals($this->getTotalsTransfer());
        $orderTransfer->setShipmentMethods(new ArrayObject([new ShipmentMethodTransfer()]));
        $orderTransfer->setItems($this->createItems());
        return $orderTransfer;
    }

    /**
     * @return \ArrayObject
     */
    protected function createItems()
    {
        $item = $this->createItem();
        $items = new ArrayObject();
        $items->append($item);
        return $items;
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItem()
    {
        $item = new ItemTransfer();
        $item->setQuantity(1);
        return $item;
    }
}
