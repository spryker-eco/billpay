<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business;

use ipl_cancel_request;
use ipl_edit_cart_content_request;
use ipl_invoice_created_request;
use ipl_preauthorize_request;
use ipl_prescore_request;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Zed\Billpay\BillpayDependencyProvider;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\CancelOrderApiAdapter;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\EditCartContentApiAdapter;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\InvoiceCreatedApiAdapter;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\PreauthorizeApiAdapter;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\PrescoreApiAdapter;
use SprykerEco\Zed\Billpay\Business\Api\Converter\CancelOrderConverter;
use SprykerEco\Zed\Billpay\Business\Api\Converter\EditCartContentConverter;
use SprykerEco\Zed\Billpay\Business\Api\Converter\InvoiceCreatedConverter;
use SprykerEco\Zed\Billpay\Business\Api\Converter\PreauthorizeConverter;
use SprykerEco\Zed\Billpay\Business\Api\Converter\PrescoreConverter;
use SprykerEco\Zed\Billpay\Business\Order\OrderManager;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\CancelResponseHandler;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\EditCartResponseHandler;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\Invoice\InvoiceCreatedResponseHandler;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger\BillpayResponseLogger;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\PreauthorizeResponseHandler;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\PrescoreResponseHandler;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceBankAccountSaver;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceManager;
use SprykerEco\Zed\Billpay\Business\Payment\Request\CancelOrderPaymentRequest;
use SprykerEco\Zed\Billpay\Business\Payment\Request\EditCartContentPaymentRequest;
use SprykerEco\Zed\Billpay\Business\Payment\Request\Invoice\InvoiceCreatedPaymentRequest;
use SprykerEco\Zed\Billpay\Business\Payment\Request\PreauthorizePaymentRequest;
use SprykerEco\Zed\Billpay\Business\Payment\Request\PrescorePaymentRequest;

/**
 * @method \SprykerEco\Zed\Billpay\BillpayConfig getConfig()
 * @method \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface getQueryContainer()
 */
class BillpayBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Request\QuoteTransactionInterface
     */
    public function createPrescorePaymentRequest()
    {
        $prescorePaymentRequest = new PrescorePaymentRequest(
            $this->createPrescoreAdapter(),
            $this->createPrescoreConverter(),
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->createPrescoreResponseHandler()
        );

        //have no clue what is this
        $prescorePaymentRequest->registerManager(
            $this->createInvoiceManager()
        );

        return $prescorePaymentRequest;
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Request\OrderTransactionInterface
     */
    public function createPreauthorizePaymentRequest($paymentMethod)
    {
        $preauthorizePaymentRequest = new PreauthorizePaymentRequest(
            $this->createPreauthorizeAdapter($paymentMethod),
            $this->createPreauthorizeConverter(),
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->createPreauthorizeResponseHandler()
        );

        $preauthorizePaymentRequest->registerManager(
            $this->createInvoiceManager()
        );

        return $preauthorizePaymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Request\OrderTransactionInterface
     */
    public function createInvoiceCreatedPaymentRequest()
    {
        $invoiceCreatedPaymentRequest = new InvoiceCreatedPaymentRequest(
            $this->createInvoiceCreatedAdapter(),
            $this->createInvoiceCreatedConverter(),
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->createInvoiceCreatedResponseHandler()
        );

        $invoiceCreatedPaymentRequest->registerManager(
            $this->createInvoiceManager()
        );

        return $invoiceCreatedPaymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Request\OrderTransactionInterface
     */
    public function createCancelOrderRequest()
    {
        $cancelOrderPaymentRequest = new CancelOrderPaymentRequest(
            $this->createCancelOrderAdapter(),
            $this->createCancelOrderConverter(),
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->createCancelResponseHandler()
        );

        $cancelOrderPaymentRequest->registerManager(
            $this->createInvoiceManager()
        );

        return $cancelOrderPaymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Request\OrderItemTransactionInterface
     */
    public function createEditCartContentTransactionHandler()
    {
        $paymentTransactionHandler = new EditCartContentPaymentRequest(
            $this->createEditCartContentAdapter(),
            $this->createEditCartContentConverter(),
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->createEditCartResponseHandler()
        );

        $paymentTransactionHandler->registerManager(
            $this->createInvoiceManager()
        );

        return $paymentTransactionHandler;
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Handler\PreauthorizeResponseHandler
     */
    public function createPreauthorizeResponseHandler()
    {
        return new PreauthorizeResponseHandler(
            $this->getQueryContainer(),
            $this->createBillpayLogger(),
            $this->createInvoiceBankAccountSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Handler\PrescoreResponseHandler
     */
    public function createPrescoreResponseHandler()
    {
        return new PrescoreResponseHandler(
            $this->getQueryContainer(),
            $this->createBillpayLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Handler\CancelResponseHandler
     */
    public function createCancelResponseHandler()
    {
        return new CancelResponseHandler(
            $this->getQueryContainer(),
            $this->createBillpayLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Handler\EditCartResponseHandler
     */
    public function createEditCartResponseHandler()
    {
        return new EditCartResponseHandler(
            $this->getQueryContainer(),
            $this->createBillpayLogger()
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Handler\Invoice\InvoiceCreatedResponseHandler
     */
    public function createInvoiceCreatedResponseHandler()
    {
        return new InvoiceCreatedResponseHandler(
            $this->getQueryContainer(),
            $this->createBillpayLogger(),
            $this->createInvoiceBankAccountSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Order\OrderManagerInterface
     */
    public function createOrderSaver()
    {
        return new OrderManager();
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface
     */
    protected function createPrescoreAdapter()
    {
        return new PrescoreApiAdapter(
            $this->getConfig(),
            $this->createPrescoreApiRequest()
        );
    }

    /**
     * @param string $paymentMethod
     *
     * @return \SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface
     */
    protected function createPreauthorizeAdapter($paymentMethod)
    {
        return new PreauthorizeApiAdapter(
            $this->getConfig(),
            $this->createPreauthorizeApiRequest($paymentMethod)
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface
     */
    protected function createInvoiceCreatedAdapter()
    {
        return new InvoiceCreatedApiAdapter(
            $this->getConfig(),
            $this->createInvoiceCreatedApiRequest()
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface
     */
    protected function createCancelOrderAdapter()
    {
        return new CancelOrderApiAdapter(
            $this->getConfig(),
            $this->createCancelApiRequest()
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface
     */
    protected function createPrescoreConverter()
    {
        return new PrescoreConverter();
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface
     */
    protected function createPreauthorizeConverter()
    {
        return new PreauthorizeConverter();
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface
     */
    protected function createInvoiceCreatedConverter()
    {
        return new InvoiceCreatedConverter();
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface
     */
    protected function createCancelOrderConverter()
    {
        return new CancelOrderConverter();
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface
     */
    protected function createEditCartContentConverter()
    {
        return new EditCartContentConverter();
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface
     */
    protected function createEditCartContentAdapter()
    {
        return new EditCartContentApiAdapter(
            $this->getConfig(),
            $this->createEditCartContentApiRequest()
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceManagerInterface
     */
    protected function createInvoiceManager()
    {
        return new InvoiceManager(
            $this->getConfig(),
            $this->getCountry()
        );
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger\BillpayResponseLoggerInterface
     */
    protected function createBillpayLogger()
    {
        return new BillpayResponseLogger();
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceBankAccountSaverInterface
     */
    protected function createInvoiceBankAccountSaver()
    {
        return new InvoiceBankAccountSaver();
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToCountryBridge
     */
    public function getCountry()
    {
        return $this->getProvidedDependency(
            BillpayDependencyProvider::FACADE_COUNTRY
        );
    }

    /**
     * @return \ipl_invoice_created_request
     */
    protected function createInvoiceCreatedApiRequest()
    {
        return new ipl_invoice_created_request($this->getConfig()->getGatewayUrl());
    }

    /**
     * @return \ipl_cancel_request
     */
    protected function createCancelApiRequest()
    {
        return new ipl_cancel_request($this->getConfig()->getGatewayUrl());
    }

    /**
     * @param string $paymentMethod
     *
     * @return \ipl_preauthorize_request
     */
    protected function createPreauthorizeApiRequest($paymentMethod)
    {
        return new ipl_preauthorize_request(
            $this->getConfig()->getGatewayUrl(),
            $this->getConfig()->extractPaymentTypeFromMethod($paymentMethod)
        );
    }

    /**
     * @return \ipl_prescore_request
     */
    protected function createPrescoreApiRequest()
    {
        return new ipl_prescore_request($this->getConfig()->getGatewayUrl());
    }

    /**
     * @return \ipl_edit_cart_content_request
     */
    protected function createEditCartContentApiRequest()
    {
        return new ipl_edit_cart_content_request($this->getConfig()->getGatewayUrl());
    }
}
