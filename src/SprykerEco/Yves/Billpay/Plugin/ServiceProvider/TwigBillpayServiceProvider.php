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
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerEco\Shared\Billpay\BillpayConfig;
use SprykerEco\Shared\Billpay\BillpayConstants;
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

                $methodName = 'get' . ucfirst($identifier);
                if (method_exists($this, $methodName)) {
                    return $this->$methodName($quoteClient->getQuote());
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getCurrency(QuoteTransfer $quoteTransfer)
    {
        return Store::getInstance()->getCurrencyIsoCode();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getLanguage(QuoteTransfer $quoteTransfer)
    {
        return Store::getInstance()->getCurrentLanguage();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getCustomerGroup(QuoteTransfer $quoteTransfer)
    {
        return BillpayConfig::CUSTOMER_GROUP_B2C;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return mixed
     */
    protected function getApiKey(QuoteTransfer $quoteTransfer)
    {
        return Config::get(BillpayConstants::BILLPAY_PUBLIC_API_KEY);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getCountryIso3Code(QuoteTransfer $quoteTransfer)
    {
        $countryTransfer = new CountryTransfer();
        $countryTransfer->setIso2Code($this->getCountryIso2Code($quoteTransfer));
        $countryTransfer = $this->getFactory()->getBillpayClient()->getCountry($countryTransfer);
        return $countryTransfer->getIso3Code();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getCountryIso2Code(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->getStore()->getCurrentCountry();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getSessionId(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->getBillpayClient()->getSessionId();
    }
}
