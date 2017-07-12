<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Billpay\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class BillpayStub extends ZedRequestStub
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface | \Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer
     */
    public function prescorePayment(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/billpay/gateway/prescore-payment', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface | \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountry($countryTransfer)
    {
        return $this->zedStub->call('/billpay/gateway/get-country', $countryTransfer);
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface | \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getSessionId()
    {
        return $this->zedStub->call('/billpay/gateway/get-session-id', new QuoteTransfer());
    }

}
