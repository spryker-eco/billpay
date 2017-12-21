<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Communication\Controller;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerEco\Zed\Billpay\Business\BillpayFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer
     */
    public function prescorePaymentAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->prescorePayment($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryAction(CountryTransfer $countryTransfer)
    {
        $countryTransfer = $this->getFacade()->getCountry($countryTransfer->getIso2Code());
        return $countryTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getSessionIdAction()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setBillpaySessionId(md5(session_id()));
        return $quoteTransfer;
    }
}
