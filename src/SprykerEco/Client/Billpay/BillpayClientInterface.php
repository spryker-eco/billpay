<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Billpay;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \SprykerEco\Client\Billpay\BillpayFactory getFactory()
 */
interface BillpayClientInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prescorePayment(QuoteTransfer $quoteTransfer);

    /**
     * Returns country based on iso2code country code (de ...)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountry($countryTransfer);

    /**
     * Retrieves the session hash from server
     *
     * @api
     *
     * @return string
     */
    public function getSessionId();
}
