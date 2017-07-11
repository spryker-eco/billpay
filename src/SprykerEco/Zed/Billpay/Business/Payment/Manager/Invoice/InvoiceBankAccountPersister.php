<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice;

use Generated\Shared\Transfer\BillpayInvoiceBankAccountTransfer;
use Orm\Zed\Billpay\Persistence\SpyPaymentBillpayInvoiceBankAccount;

class InvoiceBankAccountPersister implements InvoiceBankAccountPersisterInterface
{

    /**
     * @param \Generated\Shared\Transfer\BillpayInvoiceBankAccountTransfer $invoiceBankAccountTransfer
     * @param \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayInvoiceBankAccount|null $invoiceBankAccountEntity
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayInvoiceBankAccount
     */
    public function persist(
        BillpayInvoiceBankAccountTransfer $invoiceBankAccountTransfer,
        $invoiceBankAccountEntity = null
    ) {
        if (!$invoiceBankAccountEntity) {
            $invoiceBankAccountEntity = new SpyPaymentBillpayInvoiceBankAccount();
        }

        $invoiceBankAccountEntity->fromArray($invoiceBankAccountTransfer->toArray());
        $invoiceBankAccountEntity->setActivationPerformed(
            !empty($invoiceBankAccountTransfer->getActivationPerformed())
        );
        $invoiceBankAccountEntity->save();

        return $invoiceBankAccountEntity;
    }

}
