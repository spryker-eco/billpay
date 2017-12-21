<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Converter;

use Generated\Shared\Transfer\BillpayCorrectedAddressTransfer;
use Generated\Shared\Transfer\BillpayInvoiceBankAccountTransfer;
use Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer;
use ipl_preauthorize_request;
use ipl_xml_request;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayConverterException;

class PreauthorizeConverter extends AbstractConverter
{
    /**
     * @param \ipl_xml_request|\ipl_preauthorize_request $xmlRequest
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayConverterException
     *
     * @return \Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer
     */
    public function toTransactionResponseTransfer(ipl_xml_request $xmlRequest)
    {
        if (!$xmlRequest instanceof ipl_preauthorize_request) {
            throw new BillpayConverterException();
        }

        $preauthorizeTransactionResponseTransfer = new BillpayPreauthorizeTransactionResponseTransfer();
        $preauthorizeTransactionResponseTransfer->setHeader($this->extractHeader($xmlRequest));
        $preauthorizeTransactionResponseTransfer->setCorrectedAddress($this->extractCorrectedAddress($xmlRequest));
        $preauthorizeTransactionResponseTransfer->setInvoiceBankAccount($this->extractInvoiceBankAccount($xmlRequest));
        $preauthorizeTransactionResponseTransfer->getHeader()->setBptid($xmlRequest->get_bptid());

        return $preauthorizeTransactionResponseTransfer;
    }

    /**
     * @param \ipl_preauthorize_request $xmlRequest
     *
     * @return \Generated\Shared\Transfer\BillpayCorrectedAddressTransfer
     */
    protected function extractCorrectedAddress(ipl_preauthorize_request $xmlRequest)
    {
        $correctedAddress = new BillpayCorrectedAddressTransfer();
        $correctedAddress->setCity($xmlRequest->get_corrected_city());
        $correctedAddress->setCountry($xmlRequest->get_corrected_country());
        $correctedAddress->setStreet($xmlRequest->get_corrected_street());
        $correctedAddress->setStreetNo($xmlRequest->get_corrected_street_no());
        $correctedAddress->setZip($xmlRequest->get_corrected_zip());

        return $correctedAddress;
    }

    /**
     * @param \ipl_preauthorize_request $xmlRequest
     *
     * @return \Generated\Shared\Transfer\BillpayInvoiceBankAccountTransfer
     */
    protected function extractInvoiceBankAccount(ipl_preauthorize_request $xmlRequest)
    {
        $billpayInvoiceBankAccountTransfer = new BillpayInvoiceBankAccountTransfer();
        $billpayInvoiceBankAccountTransfer->setAccountHolder($xmlRequest->get_account_holder());
        $billpayInvoiceBankAccountTransfer->setAccountNumber($xmlRequest->get_account_number());
        $billpayInvoiceBankAccountTransfer->setActivationPerformed($xmlRequest->get_activation_performed());
        $billpayInvoiceBankAccountTransfer->setBankCode($xmlRequest->get_bank_code());
        $billpayInvoiceBankAccountTransfer->setBankName($xmlRequest->get_bank_name());
        $billpayInvoiceBankAccountTransfer->setInvoiceDuedate($xmlRequest->get_invoice_duedate());
        $billpayInvoiceBankAccountTransfer->setInvoiceReference($xmlRequest->get_invoice_reference());

        return $billpayInvoiceBankAccountTransfer;
    }
}
