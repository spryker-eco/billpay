<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay;

use SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToCalculationBridge;
use SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToCountryBridge;
use SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToOmsBridge;
use SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToRefundBridge;
use SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToSalesBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class BillpayDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_OMS = 'oms facade';
    const FACADE_REFUND = 'refund facade';
    const FACADE_SALES = 'sales facade';
    const FACADE_COUNTRY = 'country facade';
    const FACADE_CALCULATION = 'calculation facade';
    const FACADE_FLASH_MESSENGER = 'flash messenger facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container[self::FACADE_SALES] = function (Container $container) {
            return new BillpayToSalesBridge($container->getLocator()->sales()->facade());
        };

        $container[self::FACADE_CALCULATION] = function (Container $container) {
            return new BillpayToCalculationBridge($container->getLocator()->calculation()->facade());
        };

        $container[self::FACADE_FLASH_MESSENGER] = function (Container $container) {
            return new BillpayToCalculationBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[self::FACADE_OMS] = function (Container $container) {
            return new BillpayToOmsBridge($container->getLocator()->oms()->facade());
        };

        $container[self::FACADE_REFUND] = function (Container $container) {
            return new BillpayToRefundBridge($container->getLocator()->refund()->facade());
        };

        $container[self::FACADE_SALES] = function (Container $container) {
            return new BillpayToSalesBridge($container->getLocator()->sales()->facade());
        };

        $container[self::FACADE_COUNTRY] = function (Container $container) {
            return new BillpayToCountryBridge($container->getLocator()->country()->facade());
        };

        return $container;
    }

}
