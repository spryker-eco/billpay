<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Billpay\BillpaySharedConfig as BillpaySharedConfig;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayPaymentMethodException;

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
     * @return string
     */
    public function getGatewayUrl()
    {
        return $this->get(BillpayConstants::GATEWAY_URL);
    }

    /**
     * @return int
     */
    public function getMerchantId()
    {
        return $this->get(BillpayConstants::BILLPAY_MERCHANT_ID);
    }

    /**
     * @return int
     */
    public function getPortalId()
    {
        return $this->get(BillpayConstants::BILLPAY_PORTAL_ID);
    }

    /**
     * @return string
     */
    public function getSecurityKey()
    {
        $securityKey = $this->get(BillpayConstants::BILLPAY_SECURITY_KEY);

        if (!$this->get(BillpayConstants::USE_MD5_HASH)) {
            return $securityKey;
        }

        return md5($securityKey);
    }

    /**
     * @return string
     */
    public function getPublicApiKey()
    {
        return $this->get(BillpayConstants::BILLPAY_PUBLIC_API_KEY);
    }

    /**
     * @return int
     */
    public function getMaxDelayInDays()
    {
        return $this->get(BillpayConstants::BILLPAY_MAX_DELAY_IN_DAYS);
    }

    /**
     * @param string $methodName
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayPaymentMethodException
     *
     * @return int
     */
    public function extractPaymentTypeFromMethod($methodName)
    {
        if (!array_key_exists($methodName, BillpaySharedConfig::PAYMENT_METHODS_MAP)) {
            throw new BillpayPaymentMethodException();
        }

        return BillpaySharedConfig::PAYMENT_METHODS_MAP[$methodName];
    }
}
