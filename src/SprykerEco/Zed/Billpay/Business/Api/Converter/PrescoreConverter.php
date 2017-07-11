<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Converter;

use Generated\Shared\Transfer\BillpayCorrectedAddressTransfer;
use Generated\Shared\Transfer\BillpayPaymentMethodTransfer;
use Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer;
use ipl_prescore_request;
use ipl_xml_request;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayConverterException;

class PrescoreConverter extends AbstractConverter
{

    /**
     * @param \ipl_xml_request|\ipl_prescore_request $xmlRequest
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayConverterException
     *
     * @return \Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer
     */
    public function toTransactionResponseTransfer(ipl_xml_request $xmlRequest)
    {
        if (!$xmlRequest instanceof ipl_prescore_request) {
            throw new BillpayConverterException();
        }

        $prescoringTransactionResponseTransfer = new BillpayPrescoringTransactionResponseTransfer();
        $prescoringTransactionResponseTransfer->setHeader($this->extractHeader($xmlRequest));
        $prescoringTransactionResponseTransfer->setCorrectedAddress($this->extractCorrectedAddress($xmlRequest));
        $prescoringTransactionResponseTransfer->getHeader()->setBptid($xmlRequest->get_bptid());

        foreach ($xmlRequest->get_payments_allowed() as $xmlPaymentMethod) {
            $paymentMethod = new BillpayPaymentMethodTransfer();
            $paymentMethod->setName($xmlPaymentMethod);

            $prescoringTransactionResponseTransfer->addAvailablePaymentMethod($paymentMethod);
        }

        return $prescoringTransactionResponseTransfer;
    }

    /**
     * @param \ipl_prescore_request $xmlRequest
     *
     * @return \Generated\Shared\Transfer\BillpayCorrectedAddressTransfer
     */
    protected function extractCorrectedAddress(ipl_prescore_request $xmlRequest)
    {
        $correctedAddress = new BillpayCorrectedAddressTransfer();
        $correctedAddress->setCity($xmlRequest->get_corrected_city());
        $correctedAddress->setCountry($xmlRequest->get_corrected_country());
        $correctedAddress->setStreet($xmlRequest->get_corrected_street());
        $correctedAddress->setStreetNo($xmlRequest->get_corrected_street_no());
        $correctedAddress->setZip($xmlRequest->get_corrected_zip());

        return $correctedAddress;
    }

}
