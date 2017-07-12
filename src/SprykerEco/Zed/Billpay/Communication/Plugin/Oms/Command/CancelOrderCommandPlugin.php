<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \SprykerEco\Zed\Billpay\Business\BillpayFacade getFacade()
 */
class CancelOrderCommandPlugin extends AbstractBillpayCommandPlugin implements CommandByOrderInterface
{

    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $orderEntity->getItems()->setData($salesOrderItems);

        $orderTransfer = $this->getOrderTransfer($orderEntity);
        $this->getFacade()->cancelOrder($orderTransfer);

        return [];
    }

}
