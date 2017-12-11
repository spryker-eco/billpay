<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByItemInterface;
use SprykerEco\Shared\Billpay\BillpayConstants;

/**
 * @method \SprykerEco\Zed\Billpay\Business\BillpayFacadeInterface getFacade()
 */
class CancelItemCommandPlugin extends AbstractBillpayCommandPlugin implements CommandByItemInterface
{
    /**
     *
     * Command which is executed per order item basis
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data)
    {
        $orderEntity = $orderItem->getOrder();
        $orderItems = $this->getActiveItems($orderEntity, $orderItem);

        if (count($orderItems) === 0) {
            //TODO: add translation
            $message = $this
                ->getFactory()
                ->createMessage()
                ->setValue('In order to cancel the last item in an order, please use the cancel order button.');

            $this->getFactory()
                ->getFlashMessengerFacade()
                ->addErrorMessage($message);

            return []; //we can't cancel the last item
        }

        $orderEntity->getItems()->setData($orderItems->getData());
        $orderTransfer = $this->getOrderTransfer($orderEntity);

        $itemTransfer = $this->buildItemTransfer($orderItem);

        $this->getFacade()->editCartContent($orderTransfer, $itemTransfer);

        return [];
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return array
     */
    protected function getActiveItems(SpySalesOrder $orderEntity, SpySalesOrderItem $orderItem)
    {
        return SpySalesOrderItemQuery::create()
            ->filterByFkSalesOrder($orderEntity->getIdSalesOrder())
            ->filterByIdSalesOrderItem($orderItem->getIdSalesOrderItem(), Criteria::NOT_EQUAL)
                ->useStateQuery()
                    ->filterByName(
                        BillpayConstants::BILLPAY_OMS_STATUS_CANCELLED,
                        Criteria::NOT_EQUAL
                    )
                    ->filterByName(
                        BillpayConstants::BILLPAY_OMS_STATUS_ITEM_CANCELLED,
                        Criteria::NOT_EQUAL
                    )
                ->endUse()
            ->find();
    }
}
