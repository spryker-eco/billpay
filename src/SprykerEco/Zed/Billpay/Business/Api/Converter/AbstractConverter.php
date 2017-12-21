<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Converter;

use Generated\Shared\Transfer\BillpayResponseHeaderTransfer;
use ipl_xml_request;

abstract class AbstractConverter implements ConverterInterface
{
    /**
     * @param \ipl_xml_request $xmlRequest
     *
     * @return \Generated\Shared\Transfer\BillpayResponseHeaderTransfer
     */
    protected function extractHeader(ipl_xml_request $xmlRequest)
    {
        $header = new BillpayResponseHeaderTransfer();
        $header->setIsSuccess(!$xmlRequest->has_error());
        $header->setCustomerMessage($this->getCustomerErrorMessage($xmlRequest));
        $header->setErrorCode($this->getErrorCode($xmlRequest));
        $header->setMerchantMessage($this->getMerchantErrorMessage($xmlRequest));

        return $header;
    }

    /**
     * @param \ipl_xml_request $xmlRequest
     *
     * @return mixed
     */
    protected function getErrorCode(ipl_xml_request $xmlRequest)
    {
        return $xmlRequest->get_error_code() ? $xmlRequest->get_error_code() : 0;
    }

    /**
     * @param \ipl_xml_request $xmlRequest
     *
     * @return mixed
     */
    protected function getMerchantErrorMessage(ipl_xml_request $xmlRequest)
    {
        return $xmlRequest->get_merchant_error_message() ? $xmlRequest->get_merchant_error_message() : '';
    }

    /**
     * @param \ipl_xml_request $xmlRequest
     *
     * @return mixed
     */
    protected function getCustomerErrorMessage(ipl_xml_request $xmlRequest)
    {
        return $xmlRequest->get_customer_error_message() ? $xmlRequest->get_customer_error_message(): '';
    }
}
