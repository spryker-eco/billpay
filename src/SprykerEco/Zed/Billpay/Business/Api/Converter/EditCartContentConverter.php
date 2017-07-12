<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Converter;

use Generated\Shared\Transfer\BillpayEditCartResponseTransfer;
use ipl_edit_cart_content_request;
use ipl_xml_request;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayConverterException;

class EditCartContentConverter extends AbstractConverter
{

    /**
     * @param \ipl_xml_request|\ipl_edit_cart_content_request $xmlRequest
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayConverterException
     *
     * @return \Generated\Shared\Transfer\BillpayEditCartResponseTransfer
     */
    public function toTransactionResponseTransfer(ipl_xml_request $xmlRequest)
    {
        if (!$xmlRequest instanceof ipl_edit_cart_content_request) {
            throw new BillpayConverterException();
        }

        $billpayEditCartResponseTransfer = new BillpayEditCartResponseTransfer();
        $billpayEditCartResponseTransfer->setHeader($this->extractHeader($xmlRequest));

        return $billpayEditCartResponseTransfer;
    }

}
