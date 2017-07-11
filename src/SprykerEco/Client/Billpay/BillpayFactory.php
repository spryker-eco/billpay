<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Billpay;

use SprykerEco\Client\Billpay\Zed\BillpayStub;
use Spryker\Client\Kernel\AbstractFactory;

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
