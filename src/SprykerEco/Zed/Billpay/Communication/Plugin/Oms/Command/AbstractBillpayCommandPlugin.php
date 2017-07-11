<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Communication\Plugin\Oms\Command;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\BillpayPaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerEco\Zed\Billpay\Business\BillpayFacade getFacade()
 * @method \SprykerEco\Zed\Billpay\Communication\BillpayCommunicationFactory getFactory()
 */
abstract class AbstractBillpayCommandPlugin extends AbstractPlugin
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param array $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(SpySalesOrder $orderEntity, array $salesOrderItems = [])
    {
        $paymentTransfer = new BillpayPaymentTransfer();
        $paymentTransfer->fromArray($this->getPaymentEntity($orderEntity)->toArray(), true);

        $customerTransfer = $this->getCustomerTransfer($orderEntity);

        $orderTransfer = $this
            ->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder(
                $orderEntity->getIdSalesOrder()
            );

        if (count($orderEntity->getItems()) != count($salesOrderItems)) {

            $selectedItems = new ArrayObject();
            foreach ($salesOrderItems as $salesOrderItem) {
                $salesOrderItemTransfer = $this->buildItemTransfer($salesOrderItem);
                $selectedItems[] = $salesOrderItemTransfer;
            }

            $paymentTransfer->setItems($selectedItems);
        }

        $orderTransfer->setBillpayPayment($paymentTransfer);
        $orderTransfer->setCustomer($customerTransfer);

        $orderTransfer = $this
            ->getFactory()
            ->getCalculationFacade()
            ->recalculateOrder($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay
     */
    protected function getPaymentEntity(SpySalesOrder $orderEntity)
    {
        return $orderEntity->getSpyPaymentBillpays()->getFirst();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    protected function getCustomerEntity(SpySalesOrder $orderEntity)
    {
        return $orderEntity->getCustomer();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected  function getCustomerTransfer(SpySalesOrder $orderEntity)
    {
        $customerTransfer = new CustomerTransfer();

        $addressTransfer = new AddressTransfer();
        $addressTransfer->fromArray($orderEntity->getBillingAddress()->toArray(), true);

        $customerTransfer->addBillingAddress($addressTransfer);
        $customerTransfer->addShippingAddress($addressTransfer);
        $customerTransfer->setIdCustomer($orderEntity->getFkCustomer());

        $customerTransfer->setEmail($orderEntity->getEmail());
        $customerTransfer->setFirstName($orderEntity->getFirstName());
        $customerTransfer->setLastName($orderEntity->getLastName());
        $customerTransfer->setSalutation($orderEntity->getSalutation());

        return $customerTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function buildItemTransfer(SpySalesOrderItem $salesOrderItem)
    {
        $salesOrderItemTransfer = new ItemTransfer();
        $salesOrderItemTransfer->fromArray($salesOrderItem->toArray(), true);
        $salesOrderItemTransfer->setUnitGrossPrice($salesOrderItem->getGrossPrice());
        return $salesOrderItemTransfer;
    }

}
