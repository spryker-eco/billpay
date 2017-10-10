<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Billpay\BillpayConstants;

class BillpayConfig extends AbstractBundleConfig
{

    /**
     * @return bool
     */
    public function getUsePrescore()
    {
        return (bool) $this->get(BillpayConstants::USE_PRESCORE);
    }

    /**
     * @return array
     */
    public function getAvailableProviderMethods()
    {
        return $this->get(BillpayConstants::AVAILABLE_PROVIDER_METHODS);
    }

}