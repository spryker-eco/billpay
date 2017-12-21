<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Persistence;

interface BillpayQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayQuery
     */
    public function queryPaymentById($idPayment);

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayQuery
     */
    public function queryPaymentBySalesOrderId($idSalesOrder);

    /**
     * @api
     *
     * @param string $bptid
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayQuery
     */
    public function queryPaymentByBptid($bptid);

    /**
     * @api
     *
     * @param string $reference
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayQuery
     */
    public function queryPaymentByReference($reference);
}
