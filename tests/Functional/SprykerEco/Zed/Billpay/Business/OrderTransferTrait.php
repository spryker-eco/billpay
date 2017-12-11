<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Billpay\Business;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\BillpayPaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Billpay\Persistence\SpyPaymentBillpay;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;

trait OrderTransferTrait
{
    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setBillpayPayment($this->createBillpayPaymentTransfer());
        $orderTransfer->setTotals(new TotalsTransfer());
        $orderTransfer->setCustomer(new CustomerTransfer());
        $orderTransfer->setBillingAddress($this->createAddressTransfer());
        $orderTransfer->setShippingAddress($this->createAddressTransfer());
        $orderTransfer->setShipmentMethods($this->createShipmentMethods());
        return $orderTransfer;
    }

    /**
     * @return \ArrayObject
     */
    protected function createShipmentMethods()
    {
        return new ArrayObject([new ShipmentMethodTransfer()]);
    }

    /**
     * @return \Generated\Shared\Transfer\BillpayPaymentTransfer
     */
    protected function createBillpayPaymentTransfer()
    {
        $payment = new BillpayPaymentTransfer();
        $payment->setPaymentMethod('INVOICE');
        $payment->setBptid(PreauthorizeApiAdapterTest::BPTID);
        return $payment;
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createAddressTransfer()
    {
        $address = new AddressTransfer();
        $address->setIso2Code('DE');
        return $address;
    }

    /**
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay
     */
    protected function createBillpayPayment()
    {
        $salesOrder = $this->createSalesOrder();

        $data = new SpyPaymentBillpay();
        $data->setPaymentMethod('INVOICE');
        $data->setBptid(PreauthorizeApiAdapterTest::BPTID);
        $data->setSpySalesOrder($salesOrder);
        $data->setClientIp('10.10.0.1');
        $data->setReference('reference');
        $data->setDateOfBirth('11.01.1980');
        $data->save();

        return $data;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createSalesOrder()
    {
        $data = new SpySalesOrder();
        $data->setBillingAddress($this->createSalesOrderAddress());
        $data->setShippingAddress($this->createSalesOrderAddress());
        $data->setOrderReference('reference');
        $data->save();
        return $data;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    protected function createSalesOrderAddress()
    {
        $data = new SpySalesOrderAddress();
        $data->setFirstName('Test');
        $data->setLastName('Tester');
        $data->setAddress1('Street');
        $data->setCity('Berlin');
        $data->setZipCode('10317');
        $data->setFkCountry(60);
        $data->save();
        return $data;
    }
}
