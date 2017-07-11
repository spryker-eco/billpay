<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\AbstractManager;

class InvoiceManager extends AbstractManager implements InvoiceManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildInvoiceCreatedOrderRequest(OrderTransfer $orderTransfer)
    {
        return [
            BillpayConstants::PARAM_GROUP_INVOICE => $this->prepareInvoiceCreatedData($orderTransfer),
            BillpayConstants::PARAM_GROUP_ARTICLES => $this->prepareCartData(
                $orderTransfer->getBillpayPayment()->getItems()
            ),
        ];
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return BillpayConstants::INVOICE;
    }

}
