<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Billpay;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerEco\Client\Billpay\BillpayFactory getFactory()
 */
class BillpayClient extends AbstractClient implements BillpayClientInterface
{
    /**
     * Recalculates the given quote and returns an updated one.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prescorePayment(QuoteTransfer $quoteTransfer)
    {
        $prescoringTransfer = $this->getFactory()->createZedStub()->prescorePayment($quoteTransfer);
        $quoteTransfer
            ->getPayment()
            ->getBillpay()->setBillpayPrescoringTransactionResponse($prescoringTransfer);

        return $quoteTransfer;
    }

    /**
     * Returns country based on iso2code country code (de ...)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountry($countryTransfer)
    {
        $countryTransfer = $this->getFactory()->createZedStub()->getCountry($countryTransfer);
        return $countryTransfer;
    }

    /**
     * Retrieves the session hash from server
     *
     * @api
     *
     * @return string
     */
    public function getSessionId()
    {
        $quoteTransfer = $this->getFactory()->createZedStub()->getSessionId();
        return $quoteTransfer->getBillpaySessionId();
    }
}
