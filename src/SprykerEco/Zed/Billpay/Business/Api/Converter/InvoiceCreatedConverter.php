<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Converter;

use Generated\Shared\Transfer\BillpayInvoiceBankAccountTransfer;
use Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer;
use ipl_invoice_created_request;
use ipl_xml_request;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayConverterException;

class InvoiceCreatedConverter extends AbstractConverter
{
    /**
     * @param \ipl_xml_request|\ipl_invoice_created_request $xmlRequest
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayConverterException
     *
     * @return \Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer
     */
    public function toTransactionResponseTransfer(ipl_xml_request $xmlRequest)
    {
        if (!$xmlRequest instanceof ipl_invoice_created_request) {
            throw new BillpayConverterException();
        }

        $invoiceBankAccountTransfer = new BillpayInvoiceCreatedResponseTransfer();
        $invoiceBankAccountTransfer->setHeader($this->extractHeader($xmlRequest));
        $invoiceBankAccountTransfer->setInvoiceBankAccount($this->extractInvoiceBankAccount($xmlRequest));

        return $invoiceBankAccountTransfer;
    }

    /**
     * @param \ipl_invoice_created_request $xmlRequest
     *
     * @return \Generated\Shared\Transfer\BillpayInvoiceBankAccountTransfer
     */
    protected function extractInvoiceBankAccount(ipl_invoice_created_request $xmlRequest)
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
