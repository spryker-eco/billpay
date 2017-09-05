<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Dependency\Facade;

use Generated\Shared\Transfer\MessageTransfer;

interface BillpayToMessengerInterface
{

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $message);

}
