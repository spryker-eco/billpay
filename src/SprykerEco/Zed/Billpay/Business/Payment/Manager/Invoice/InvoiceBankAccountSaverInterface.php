<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice;

use Generated\Shared\Transfer\BillpayInvoiceBankAccountTransfer;

interface InvoiceBankAccountSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\BillpayInvoiceBankAccountTransfer $invoiceBankAccountTransfer
     * @param \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayInvoiceBankAccount|null $invoiceBankAccountEntity
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayInvoiceBankAccount
     */
    public function persist(BillpayInvoiceBankAccountTransfer $invoiceBankAccountTransfer, $invoiceBankAccountEntity = null);
}
