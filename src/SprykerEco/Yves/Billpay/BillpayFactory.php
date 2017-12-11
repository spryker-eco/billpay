<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay;

use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Yves\Billpay\Form\DataProvider\BillpayInvoiceFormDataProvider;
use SprykerEco\Yves\Billpay\Form\InvoiceBillpaySubForm;
use SprykerEco\Yves\Billpay\Handler\BillpayPaymentHandler;

/**
 */
class BillpayFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Yves\Billpay\Form\InvoiceBillpaySubForm
     */
    public function createInvoiceForm()
    {
        return new InvoiceBillpaySubForm();
    }

    /**
     * @return \SprykerEco\Yves\Billpay\Form\DataProvider\BillpayInvoiceFormDataProvider
     */
    public function createInvoiceFormDataProvider()
    {
        return new BillpayInvoiceFormDataProvider(
            $this->getBillpayClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Yves\Billpay\Handler\BillpayPaymentHandler
     */
    public function createBillpayHandler()
    {
        return new BillpayPaymentHandler();
    }

    /**
     * @return \SprykerEco\Client\Billpay\BillpayClient
     */
    public function getBillpayClient()
    {
        return $this->getProvidedDependency(
            BillpayDependencyProvider::CLIENT_BILLPAY
        );
    }

    /**
     * @return \Spryker\Client\Customer\CustomerClientInterface
     */
    public function createCustomerClient()
    {
        return $this->getProvidedDependency(
            BillpayDependencyProvider::CLIENT_CUSTOMER
        );
    }

    /**
     * @return \SprykerEco\Yves\Billpay\Dependency\Client\BillpayToQuoteInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(BillpayDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCurrentCountry(CountryTransfer $countryTransfer)
    {
        return $this->getBillpayClient()->getCountry($countryTransfer);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(BillpayDependencyProvider::STORE);
    }
}
