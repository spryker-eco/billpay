<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Billpay\Module;

use Codeception\Module;

class Billpay extends Module
{
    /**
     * @param array $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = [])
    {
        $this->addBillpayToConfig();
    }

    /**
     * @return void
     */
    public function _afterSuite()
    {
        $this->removeBillpayFromConfig();
    }

    /**
     * @return void
     */
    protected function addBillpayToConfig()
    {
        $configLocalTest = $this->getPathToConfigLocalTest();
        $billpayConfig = realpath(__DIR__ . '/../../../config/Zed/config.dist.php');

        file_put_contents($configLocalTest, file_get_contents($billpayConfig));
    }

    /**
     * @return string
     */
    protected function getPathToConfigLocalTest()
    {
        return APPLICATION_ROOT_DIR . '/config/Shared/config_local_test.php';
    }

    /**
     * @return void
     */
    protected function removeBillpayFromConfig()
    {
        $configFile = $this->getPathToConfigLocalTest();

        if (file_exists($configFile)) {
            unlink($configFile);
        }
    }
}
