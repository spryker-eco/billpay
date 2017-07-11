<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Handler;

use Generated\Shared\Transfer\BillpayEditCartResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Billpay\Persistence\Base\SpyPaymentBillpayOrderItemQuery;
use SprykerEco\Shared\Billpay\BillpayConstants;

class EditCartResponseHandler extends AbstractResponseHandler
{

    const METHOD = 'EDIT_CART';

    /**
     * @param \Generated\Shared\Transfer\BillpayEditCartResponseTransfer|\Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return void
     */
    public function handle(
        BillpayEditCartResponseTransfer $responseTransfer,
        ItemTransfer $item
    ) {
        $this->logHeader($responseTransfer->getHeader(), self::METHOD);

        if (!$responseTransfer->getHeader()->getIsSuccess()) {
            return;
        }

        $orderItemEntities = SpyPaymentBillpayOrderItemQuery::create()->findByFkSalesOrderItem($item->getIdSalesOrderItem());

        foreach ($orderItemEntities as $item) {
            $item->setStatus(BillpayConstants::BILLPAY_OMS_STATUS_CANCELLED);
            $item->save();
        }
    }

}
