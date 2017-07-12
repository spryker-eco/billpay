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
     * @var \Spryker\Zed\Calculation\Business\BillpayToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\Calculation\Business\BillpayToMessengerInterface $messengerFacade
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
        return $this->messengerFacade->addErrorMessage($message);
    }

}
