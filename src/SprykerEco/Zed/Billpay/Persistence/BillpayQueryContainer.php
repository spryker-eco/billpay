<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \SprykerEco\Zed\Billpay\Persistence\BillpayPersistenceFactory getFactory()
 */
class BillpayQueryContainer extends AbstractQueryContainer implements BillpayQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayQuery
     */
    public function queryPaymentById($idPayment)
    {
        return $this
            ->queryPayments()
            ->filterByIdPaymentBillpay($idPayment);
    }

    /**
     * @api
     *
     * @param string $bptid
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayQuery
     */
    public function queryPaymentByBptid($bptid)
    {
        return $this
            ->queryPayments()
            ->filterByBptid($bptid);
    }

    /**
     * @api
     *
     * @param string $reference
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayQuery
     */
    public function queryPaymentByReference($reference)
    {
        return $this
            ->queryPayments()
            ->filterByReference($reference);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayQuery
     */
    public function queryPaymentBySalesOrderId($idSalesOrder)
    {
        return $this
            ->queryPayments()
            ->filterByFkSalesOrder($idSalesOrder);
    }

    // public function queryBillpayOrder

    /**
     * @api
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayQuery
     */
    protected function queryPayments()
    {
        return $this
            ->getFactory()
            ->createPaymentBillpayQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayOrderItemQuery
     */
    protected function queryPaymentOrderItems()
    {
        return $this
            ->getFactory()
            ->createPaymentBillpayOrderItemQuery();
    }
}
