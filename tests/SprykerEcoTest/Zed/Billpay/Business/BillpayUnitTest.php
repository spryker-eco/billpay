<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Billpay\Business;

use Codeception\TestCase\Test;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

class BillpayUnitTest extends Test
{

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();

        $config = $this->getConfigOptions();
        foreach ($config as $key => $value) {
            $this->getModule('\\' . ConfigHelper::class)
                ->setConfig($key, $value);
        }
    }

    /**
     * @return array
     */
    protected function getConfigOptions()
    {
        return (new BillpayConfiguratorBuilder())->getBillpayConfigurationOptions();
    }


}
