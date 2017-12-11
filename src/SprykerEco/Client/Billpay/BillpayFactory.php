<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Billpay;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerEco\Client\Billpay\Zed\BillpayStub;

class BillpayFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Client\Billpay\Zed\BillpayStub
     */
    public function createZedStub()
    {
        return new BillpayStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(BillpayDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
