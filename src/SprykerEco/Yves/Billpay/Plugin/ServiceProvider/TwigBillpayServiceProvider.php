<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay\Plugin\ServiceProvider;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerEco\Shared\Billpay\BillpayConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_SimpleFunction;

/**
 * @method \SprykerEco\Yves\Billpay\BillpayFactory getFactory()
 */
class TwigBillpayServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    const MONEY_DIVIDER = 100;

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (\Twig_Environment $twig) {
                $twig->addFunction($this->getBillpayFunction());
                return $twig;
            })
        );
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @return \Twig_SimpleFunction
     */
    public function getBillpayFunction()
    {
        $quoteClient = $this->getFactory()->getQuoteClient();

        return
            new Twig_SimpleFunction('billpay', function ($identifier) use ($quoteClient) {

                if ($identifier === 'salutation') {
                    return $this->getSalutation($quoteClient->getQuote());
                }

                if ($identifier === 'firstName') {
                    return $this->getFirstName($quoteClient->getQuote());
                }

                if ($identifier === 'lastName') {
                    return $this->getLastName($quoteClient->getQuote());
                }

                if ($identifier === 'address') {
                    return $this->getAddress($quoteClient->getQuote());
                }

                if ($identifier === 'addressNo') {
                    return $this->getAddressNo($quoteClient->getQuote());
                }

                if ($identifier === 'zip') {
                    return $this->getZip($quoteClient->getQuote());
                }

                if ($identifier === 'city') {
                    return $this->getCity($quoteClient->getQuote());
                }

                if ($identifier === 'phone') {
                    return $this->getPhone($quoteClient->getQuote());
                }

                if ($identifier === 'dateOfBirth') {
                    return $this->getDateOfBirth($quoteClient->getQuote());
                }

                if ($identifier === 'cartAmount') {
                    return $this->getCartAmount($quoteClient->getQuote());
                }

                if ($identifier === 'orderAmount') {
                    return $this->getOrderAmount($quoteClient->getQuote());
                }

                if ($identifier === 'currency') {
                    return $this->getCurrency();
                }

                if ($identifier === 'language') {
                    return $this->getLanguage();
                }

                if ($identifier === 'customerGroup') {
                    return $this->getCustomerGroup();
                }

                if ($identifier === 'countryIso3Code') {
                    return $this->getCountryIso3Code();
                }

                if ($identifier === 'countryIso2Code') {
                    return $this->getCountryIso2Code();
                }

                if ($identifier === 'identifier') {
                    return $this->getSessionId();
                }

                if ($identifier === 'apiKey') {
                    return $this->getApiKey();
                }

                return null;

            });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getSalutation(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getBillingAddress()->getSalutation();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getFirstName(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getBillingAddress()->getFirstName();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getLastName(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getBillingAddress()->getLastName();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getAddress(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getBillingAddress()->getAddress1();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getAddressNo(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getBillingAddress()->getAddress2();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getZip(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getBillingAddress()->getZipCode();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getCity(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getBillingAddress()->getCity();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getPhone(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getCustomer()->getPhone();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getDateOfBirth(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getCustomer()->getDateOfBirth();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getCartAmount(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getTotals()->getGrandTotal() / static::MONEY_DIVIDER;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getOrderAmount(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getTotals()->getGrandTotal() / static::MONEY_DIVIDER;
    }

    /**
     * @return string
     */
    protected function getCurrency()
    {
        return Store::getInstance()->getCurrencyIsoCode();
    }

    /**
     * @return string
     */
    protected function getLanguage()
    {
        return Store::getInstance()->getCurrentLanguage();
    }

    /**
     * @return string
     */
    protected function getCustomerGroup()
    {
        return BillpayConstants::CUSTOMER_GROUP_B2C;
    }

    /**
     * @return mixed
     */
    protected function getApiKey()
    {
        return Config::get(BillpayConstants::BILLPAY_PUBLIC_API_KEY);
    }

    /**
     * @return string
     */
    protected function getCountryIso3Code()
    {
        $countryTransfer = new CountryTransfer();
        $countryTransfer->setIso2Code($this->getCountryIso2Code());
        $countryTransfer = $this->getFactory()->getCurrentCountry($countryTransfer);
        return $countryTransfer->getIso3Code();
    }

    /**
     * @return string
     */
    protected function getCountryIso2Code()
    {
        return $this->getFactory()->getStore()->getCurrentCountry();
    }

    /**
     * @return string
     */
    protected function getSessionId()
    {
        return $this->getFactory()->getBillpayClient()->getSessionId();
    }

}
