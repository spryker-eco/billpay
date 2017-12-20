<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerEco\Shared\Billpay\BillpayConfig;
use SprykerEco\Shared\Billpay\BillpayConstants;

class IsCancelledConditionPlugin implements ConditionInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        /** @var \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayOrderItem $billpaymentOrderItem */
        $billpaymentOrderItem = $orderItem->getSpyPaymentBillpayOrderItems()->getLast();

        return ($billpaymentOrderItem->getStatus() === BillpayConfig::BILLPAY_OMS_STATUS_CANCELLED);
    }
}
