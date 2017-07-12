<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Adapter;

use Exception;
use ipl_xml_request;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayApiException;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayPaymentMethodException;

abstract class AbstractApiAdapter implements AdapterInterface
{

    /**
     * @var \SprykerEco\Zed\Billpay\BillpayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Billpay\BillpayConfig $config
     */
    public function __construct(BillpayConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $data
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayApiException
     *
     * @return \ipl_xml_request
     */
    public function sendRequest(array $data)
    {
        $this->prepareData($data);
        $xmlRequest = $this->getXmlRequest();
        $this->setDefaultParameters($xmlRequest);

        try {
            $xmlRequest->send();
        } catch (Exception $requestException) {
            throw new BillpayApiException($requestException->getMessage());
        }

        return $xmlRequest;
    }

    /**
     * @return string
     */
    protected function getFraudDetection()
    {
        return md5(session_id());
    }

    /**
     * @param \ipl_xml_request $xmlRequest
     *
     * @return \ipl_xml_request
     */
    protected function setDefaultParameters(ipl_xml_request $xmlRequest)
    {
        $xmlRequest->set_default_params(
            $this->config->getMerchantId(),
            $this->config->getPortalId(),
            $this->config->getSecurityKey()
        );

        return $xmlRequest;
    }

    /**
     * @param string $methodName
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayPaymentMethodException
     *
     * @return int
     */
    protected function extractPaymentTypeFromMethod($methodName)
    {
        if (!array_key_exists($methodName, BillpayConstants::PAYMENT_METHODS_MAP)) {
            throw new BillpayPaymentMethodException();
        }

        return BillpayConstants::PAYMENT_METHODS_MAP[$methodName];
    }

    /**
     * @return \ipl_xml_request
     */
    abstract protected function getXmlRequest();

    /**
     * @param array $data
     *
     * @return void
     */
    abstract protected function prepareData(array $data);

}
