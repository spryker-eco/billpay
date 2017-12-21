<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Converter;

use Generated\Shared\Transfer\BillpayCancelResponseTransfer;
use ipl_cancel_request;
use ipl_xml_request;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayConverterException;

class CancelOrderConverter extends AbstractConverter
{
    /**
     * @param \ipl_xml_request|\ipl_cancel_request $xmlRequest
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayConverterException
     *
     * @return \Generated\Shared\Transfer\BillpayCancelResponseTransfer
     */
    public function toTransactionResponseTransfer(ipl_xml_request $xmlRequest)
    {
        if (!$xmlRequest instanceof ipl_cancel_request) {
            throw  new BillpayConverterException();
        }

        $billpayCancelTransfer = new BillpayCancelResponseTransfer();
        $billpayCancelTransfer->setHeader($this->extractHeader($xmlRequest));

        return $billpayCancelTransfer;
    }
}
