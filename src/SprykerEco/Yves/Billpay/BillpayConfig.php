<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay;

use Generated\Shared\Transfer\BillpayPaymentMethodTransfer;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Billpay\BillpayConfig as BillpaySharedConfig;
use SprykerEco\Shared\Billpay\BillpayConstants;

class BillpayConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isPrescoreUsed()
    {
        return (bool)$this->get(BillpayConstants::USE_PRESCORE);
    }

    /**
     * @return array
     */
    public function getAvailableProviderMethods()
    {
        return array_map(
            function ($value) {
                return (new BillpayPaymentMethodTransfer())
                    ->setName($value);
            },
            BillpaySharedConfig::AVAILABLE_PROVIDER_METHODS
        );
    }
}
