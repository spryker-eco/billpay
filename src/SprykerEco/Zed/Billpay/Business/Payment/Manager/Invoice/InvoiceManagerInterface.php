<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\AbstractManagerInterface;

interface InvoiceManagerInterface extends AbstractManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildInvoiceCreatedOrderRequest(OrderTransfer $orderTransfer);

}
