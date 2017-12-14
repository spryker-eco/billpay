<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Dependency\Facade;

use Generated\Shared\Transfer\MessageTransfer;

class BillpayToMessengerBridge implements BillpayToMessengerInterface
{
    /**
     * @var \SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToMessengerInterface $messengerFacade
     */
    public function __construct($messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $message)
    {
        $this->messengerFacade->addErrorMessage($message);
    }
}
