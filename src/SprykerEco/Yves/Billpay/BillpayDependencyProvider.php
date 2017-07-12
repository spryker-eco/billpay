<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay;

use Spryker\Shared\Kernel\Store;
use SprykerEco\Yves\Billpay\Dependency\Client\BillpayToQuoteBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class BillpayDependencyProvider extends AbstractBundleDependencyProvider
{

    const CLIENT_CUSTOMER = 'customer client';
    const CLIENT_BILLPAY = 'billpay_cllient';
    const CLIENT_QUOTE = 'quote_client';
    const STORE = 'STORE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[self::CLIENT_CUSTOMER] = function (Container $container) {
            return $container->getLocator()->customer()->client();
        };

        $container[self::CLIENT_BILLPAY] = function (Container $container) {
            return $container->getLocator()->billpay()->client();
        };

        $container[self::CLIENT_QUOTE] = function () use ($container) {
            return new BillpayToQuoteBridge($container->getLocator()->quote()->client());
        };

        $container[self::STORE] = function () use ($container) {
            return Store::getInstance();
        };

        return $container;
    }

}
