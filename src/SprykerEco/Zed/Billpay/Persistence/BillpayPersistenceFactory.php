<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Persistence;

use Orm\Zed\Billpay\Persistence\SpyPaymentBillpayOrderItemQuery;
use Orm\Zed\Billpay\Persistence\SpyPaymentBillpayQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \SprykerEco\Zed\Billpay\BillpayConfig getConfig()
 * @method \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface getQueryContainer()
 */
class BillpayPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayQuery
     */
    public function createPaymentBillpayQuery()
    {
        return SpyPaymentBillpayQuery::create();
    }

    /**
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayOrderItemQuery
     */
    public function createPaymentBillpayOrderItemQuery()
    {
        return SpyPaymentBillpayOrderItemQuery::create();
    }
}
