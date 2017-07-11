<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Communication;

use Generated\Shared\Transfer\MessageTransfer;
use SprykerEco\Zed\Billpay\BillpayDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \SprykerEco\Zed\Billpay\BillpayConfig getConfig()
 * @method \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface getQueryContainer()
 */
class BillpayCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToOmsInterface
     */
    public function getOmsFacade()
    {
        return $this->getProvidedDependency(
            BillpayDependencyProvider::FACADE_OMS
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(
            BillpayDependencyProvider::FACADE_SALES
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToRefundInterface
     */
    public function getRefundFacade()
    {
        return $this->getProvidedDependency(
            BillpayDependencyProvider::FACADE_REFUND
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToCalculationInterface
     */
    public function getCalculationFacade()
    {
        return $this->getProvidedDependency(
            BillpayDependencyProvider::FACADE_CALCULATION
        );
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\MessengerFacadeInterface
     */
    public function getFlashMessengerFacade()
    {
        return $this->getProvidedDependency(
            BillpayDependencyProvider::FACADE_FLASH_MESSENGER
        );
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function createMessage()
    {
         return new MessageTransfer();
    }

}
