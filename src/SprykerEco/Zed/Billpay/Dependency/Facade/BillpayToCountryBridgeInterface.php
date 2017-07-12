<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Dependency\Facade;

interface BillpayToCountryBridgeInterface
{

    /**
     * @param string $iso2code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code($iso2code);

}
