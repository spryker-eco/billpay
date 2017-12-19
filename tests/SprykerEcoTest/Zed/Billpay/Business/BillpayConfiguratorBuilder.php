<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Billpay\Business;


use SprykerEco\Shared\Billpay\BillpayConstants;

class BillpayConfiguratorBuilder
{
    /**
     * @return array
     */
    public function getBillpayConfigurationOptions()
    {

        $config[BillpayConstants::GATEWAY_URL] = 'https://test-api.billpay.de/xml/offline';
        $config[BillpayConstants::BILLPAY_MERCHANT_ID] = 'merchant';
//
//        $config[BillpayConstants::BILLPAY_PUBLIC_API_KEY] = 'a39eb681636dec360000008635';
//
//        $config[BillpayConstants::BILLPAY_PORTAL_ID] = '8635';//prescore
//        $config[BillpayConstants::BILLPAY_SECURITY_KEY] = 'IzjZ8hUwPQt6';//prescore

//$config[BillpayConstants::BILLPAY_PORTAL_ID] = '8634';//pre-authorize
//$config[BillpayConstants::BILLPAY_SECURITY_KEY] = 'H0s7zIONwsL9';//pre-authorize
//
//        $config[BillpayConstants::BILLPAY_PUBLIC_API_KEY] = 'a39eb681636dec360000008635';

        $config[BillpayConstants::BILLPAY_MAX_DELAY_IN_DAYS] = 20;
//        $config[BillpayConstants::USE_MD5_HASH] = 1;
//        $config[BillpayConstants::USE_PRESCORE] = 1;
//        $config[BillpayConstants::IS_TEST_MODE] = 1;
//        $config[BillpayConstants::CUSTOMER_GROUP] = BillpayConstants::CUSTOMER_GROUP_B2C;
        return $config;
    }
}