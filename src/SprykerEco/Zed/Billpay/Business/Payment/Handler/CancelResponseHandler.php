<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Handler;

use Generated\Shared\Transfer\BillpayCancelResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Billpay\BillpayConstants;

class CancelResponseHandler extends AbstractResponseHandler
{

    const METHOD = 'CANCEL';

    /**
     * @param \Generated\Shared\Transfer\BillpayCancelResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function handle(BillpayCancelResponseTransfer $responseTransfer, OrderTransfer $orderTransfer)
    {
        $this->logHeader($responseTransfer->getHeader(), self::METHOD);

        if (!$responseTransfer->getHeader()->getIsSuccess()) {
            return;
        }

        /** @var \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay $paymentEntity */
        $paymentEntity = $this
            ->queryContainer
            ->queryPaymentByBptid($orderTransfer->getBillpayPayment()->getBptid())
            ->findOne();

        foreach ($orderTransfer->getItems() as $selectedItem) {
            foreach ($paymentEntity->getSpyPaymentBillpayOrderItems() as $item) {
                if ($item->getFkSalesOrderItem() === $selectedItem->getIdSalesOrderItem()) {
                    $item->setStatus(BillpayConstants::BILLPAY_OMS_STATUS_CANCELLED);
                    $item->save();
                }
            }
        }
    }

}
