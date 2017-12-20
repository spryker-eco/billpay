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
     * @return \SprykerEco\Yves\Billpay\Form\InvoiceBillpaySubFormInterface
     */
    public function createInvoiceForm()
    {
        return new InvoiceBillpaySubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createInvoiceFormDataProvider()
    {
        return new BillpayInvoiceFormDataProvider(
            $this->getBillpayClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Yves\Billpay\Handler\BillpayPaymentHandlerInterface
     */
    public function createBillpayHandler()
    {
        return new BillpayPaymentHandler();
    }

    /**
     * @return \SprykerEco\Client\Billpay\BillpayClientInterface
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
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(BillpayDependencyProvider::STORE);
    }
}
