<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerEco\Client\Billpay\Zed;

use Generated\Shared\Transfer\QuoteTransfer;

interface BillpayStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface | \Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer
     */
    public function prescorePayment(QuoteTransfer $quoteTransfer);

    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface | \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountry($countryTransfer);

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface | \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getSessionId();
}
