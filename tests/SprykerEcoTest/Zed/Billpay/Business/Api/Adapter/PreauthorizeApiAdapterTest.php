<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Billpay\Business\Api\Adapter;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\BillpayPaymentTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use ipl_preauthorize_request;
use ipl_xml_request;
use SprykerEco\Shared\Billpay\BillpayConfig as BillpayConfig1;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\PreauthorizeApiAdapter;
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
 * @group PreauthorizeApiAdapterTest
 */
class PreauthorizeApiAdapterTest extends BillpayUnitTest
{
    use TotalsTransferTrait;

    /**
     * @return void
     */
    public function testSendRequest()
    {
        $request = $this->prepareRequest([[], [], []]);

        $invoice = new InvoiceManager(new BillpayConfig(), $this->createCountryMock());
        $editCartContent = $invoice->buildPreauthorizeOrderRequest($this->createOrderTransfer());

        $service = new PreauthorizeApiAdapter(new BillpayConfig(), $request);
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
        $cancelOrder = $invoice->buildPreauthorizeOrderRequest($this->createOrderTransfer());

        $service = new PreauthorizeApiAdapter(new BillpayConfig(), $request);
        $response = $service->sendRequest($cancelOrder);

        $this->assertInstanceOf(ipl_xml_request::class, $response);
    }

    /**
     * @param mixed $returnValue
     *
     * @return \PHPUnit_Framework_MockObject_MockObject | \ipl_preauthorize_request
     */
    protected function prepareRequest($returnValue)
    {
        $builder = $this->getMockBuilder(ipl_preauthorize_request::class);
        $builder->setMethods(['_send']);
        $builder->setConstructorArgs(['dummy_url', BillpayConfig1::INVOICE_B2C]);

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

        $country = new CountryTransfer();
        $country->setIso3Code($returnValue);

        // Configure the stub.
        $stub->method('getCountryByIso2Code')
            ->willReturn($country);

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
        $orderTransfer->setItems($this->createItems());
        $orderTransfer->setCustomer(new CustomerTransfer());
        $orderTransfer->setBillingAddress(new AddressTransfer());
        $orderTransfer->setShippingAddress(new AddressTransfer());
        $orderTransfer->setShipmentMethods(new ArrayObject([new ShipmentMethodTransfer()]));
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
