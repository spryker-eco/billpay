<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Billpay\Business\Mock;


use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

trait TotalsTransferTrait
{
    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function getTotalsTransfer()
    {
        return (new TotalsTransfer())
            ->setGrandTotal(1000)
            ->setTaxTotal($this->getTaxTotalTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\TaxTotalTransfer
     */
    protected function getTaxTotalTransfer()
    {
        return (new TaxTotalTransfer())->setAmount(200);
    }
}