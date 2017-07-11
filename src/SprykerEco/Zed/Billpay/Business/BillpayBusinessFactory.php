<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business;

use ipl_cancel_request;
use ipl_edit_cart_content_request;
use ipl_invoice_created_request;
use ipl_preauthorize_request;
use ipl_prescore_request;
use SprykerEco\Shared\Billpay\BillpayConstants;
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
use SprykerEco\Zed\Billpay\Business\Exception\BillpayPaymentMethodException;
use SprykerEco\Zed\Billpay\Business\Order\OrderManager;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\CancelResponseHandler;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\EditCartResponseHandler;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\Invoice\InvoiceCreatedResponseHandler;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger\BillpayResponseLogger;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\PreauthorizeResponseHandler;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\PrescoreResponseHandler;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceBankAccountPersister;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceManager;
use SprykerEco\Zed\Billpay\Business\Payment\Request\CancelOrderPaymentRequest;
use SprykerEco\Zed\Billpay\Business\Payment\Request\EditCartContentPaymentRequest;
use SprykerEco\Zed\Billpay\Business\Payment\Request\Invoice\InvoiceCreatedPaymentRequest;
use SprykerEco\Zed\Billpay\Business\Payment\Request\PreauthorizePaymentRequest;
use SprykerEco\Zed\Billpay\Business\Payment\Request\PrescorePaymentRequest;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

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
            $this->getConfig()
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
            $this->getConfig()
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
            $this->getConfig()
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
            $this->getConfig()
        );

        $cancelOrderPaymentRequest->registerManager(
            $this->createInvoiceManager()
        );

        return $cancelOrderPaymentRequest;
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Request\OrderTransactionInterface
     */
    public function createEditCartContentTransactionHandler()
    {
        $paymentTransactionHandler = new EditCartContentPaymentRequest(
            $this->createEditCartContentAdapter(),
            $this->createEditCartContentConverter(),
            $this->getQueryContainer(),
            $this->getConfig()
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
            $this->createInvoiceBankAccountPersister()
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
            $this->createInvoiceBankAccountPersister()
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
     * @param string $methodName
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayPaymentMethodException
     *
     * @return int
     */
    protected function extractPaymentTypeFromMethod($methodName)
    {
        if (!array_key_exists($methodName, BillpayConstants::PAYMENT_METHODS_MAP)) {
            throw new BillpayPaymentMethodException();
        }

        return BillpayConstants::PAYMENT_METHODS_MAP[$methodName];
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
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger\BillpayResponseLogger
     */
    protected function createBillpayLogger()
    {
        return new BillpayResponseLogger();
    }

    /**
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceBankAccountPersister
     */
    protected function createInvoiceBankAccountPersister()
    {
        return new InvoiceBankAccountPersister();
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
    protected function createInvoiceCreatedApiRequest(): \ipl_invoice_created_request
    {
        return new ipl_invoice_created_request($this->getConfig()->getGatewayUrl());
    }

    /**
     * @return \ipl_cancel_request
     */
    protected function createCancelApiRequest(): \ipl_cancel_request
    {
        return new ipl_cancel_request($this->getConfig()->getGatewayUrl());
    }

    /**
     * @param string $paymentMethod
     *
     * @return \ipl_preauthorize_request
     */
    protected function createPreauthorizeApiRequest($paymentMethod): \ipl_preauthorize_request
    {
        return new ipl_preauthorize_request(
            $this->getConfig()->getGatewayUrl(),
            $this->extractPaymentTypeFromMethod($paymentMethod)
        );
    }

    /**
     * @return \ipl_prescore_request
     */
    protected function createPrescoreApiRequest(): \ipl_prescore_request
    {
        return new ipl_prescore_request($this->getConfig()->getGatewayUrl());
    }

    /**
     * @return \ipl_edit_cart_content_request
     */
    protected function createEditCartContentApiRequest(): \ipl_edit_cart_content_request
    {
        return new ipl_edit_cart_content_request($this->getConfig()->getGatewayUrl());
    }

}
