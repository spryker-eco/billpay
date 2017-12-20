<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Billpay\Persistence\SpyPaymentBillpay;
use Orm\Zed\Billpay\Persistence\SpyPaymentBillpayOrderItem;

use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Shared\Billpay\BillpayConfig;
use SprykerEco\Shared\Billpay\BillpayConstants;

class OrderManager implements OrderManagerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($quoteTransfer->getPayment()->getPaymentProvider() === BillpayConfig::PAYMENT_PROVIDER) {
            $this->handleDatabaseTransaction(function () use ($quoteTransfer, $checkoutResponseTransfer) {

                $paymentEntity = $this->savePaymentForOrder(
                    $quoteTransfer->getPayment(),
                    $checkoutResponseTransfer->getSaveOrder()
                );

                $this->savePaymentForOrderItems(
                    $checkoutResponseTransfer->getSaveOrder()->getOrderItems(),
                    $paymentEntity->getIdPaymentBillpay()
                );
            });
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay
     */
    protected function savePaymentForOrder(PaymentTransfer $paymentTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $paymentEntity = new SpyPaymentBillpay();
        $paymentEntity->setClientIp($paymentTransfer->getBillpay()->getClientIp());
        $paymentEntity->setPaymentMethod($paymentTransfer->getPaymentMethod());
        $paymentEntity->setReference($saveOrderTransfer->getOrderReference());

        if ($paymentTransfer->getBillpay()->getBillpayPrescoringTransactionResponse() !== null) {
            $paymentEntity->setBptid($paymentTransfer->getBillpay()->getBillpayPrescoringTransactionResponse()->getHeader()->getBptid());
        }
        $paymentEntity->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $paymentEntity->setDateOfBirth($paymentTransfer->getBillpay()->getDateOfBirth());
        $paymentEntity->save();

        return $paymentEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     * @param int $idPayment
     *
     * @return void
     */
    protected function savePaymentForOrderItems($orderItemTransfers, $idPayment)
    {
        foreach ($orderItemTransfers as $orderItemTransfer) {
            $paymentOrderItemEntity = new SpyPaymentBillpayOrderItem();

            $paymentOrderItemEntity
                ->setFkPaymentBillpay($idPayment)
                ->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
            $paymentOrderItemEntity->setStatus(BillpayConfig::BILLPAY_OMS_STATUS_NEW);

            $paymentOrderItemEntity->save();
        }
    }
}
