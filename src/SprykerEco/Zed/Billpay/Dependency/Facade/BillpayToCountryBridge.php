<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Dependency\Facade;

use Spryker\Zed\Country\Business\CountryFacadeInterface;

class BillpayToCountryBridge implements BillpayToCountryBridgeInterface
{

    /** @var \Spryker\Zed\Country\Business\CountryFacadeInterface */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\Country\Business\CountryFacadeInterface $countryFacade
     */
    public function __construct(CountryFacadeInterface $countryFacade)
    {
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param string $iso2code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code($iso2code)
    {
        return $this->countryFacade->getCountryByIso2Code($iso2code);
    }

}
