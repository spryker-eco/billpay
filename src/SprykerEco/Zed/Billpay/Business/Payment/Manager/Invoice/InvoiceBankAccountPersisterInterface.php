<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice;

use Generated\Shared\Transfer\BillpayInvoiceBankAccountTransfer;

interface InvoiceBankAccountPersisterInterface
{

    /**
     * @param \Generated\Shared\Transfer\BillpayInvoiceBankAccountTransfer $invoiceBankAccountTransfer
     * @param \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayInvoiceBankAccount|null $invoiceBankAccountEntity
     *
     * @return mixed
     */
    public function persist(BillpayInvoiceBankAccountTransfer $invoiceBankAccountTransfer, $invoiceBankAccountEntity = null);

}
