<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Manager;

use ArrayObject;
use Generated\Shared\Transfer\BillpayPaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Billpay\Persistence\SpyPaymentBillpay;
use SprykerEco\Shared\Billpay\BillpayConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Shipment\ShipmentConstants;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToCountryBridgeInterface;

abstract class AbstractManager implements AbstractManagerInterface
{

    /**
     * @var \SprykerEco\Zed\Billpay\BillpayConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToCountryBridgeInterface
     */
    protected $country;

    /**
     * AbstractManager constructor.
     *
     * @param \SprykerEco\Zed\Billpay\BillpayConfig $config
     * @param \SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToCountryBridgeInterface $country
     */
    public function __construct(BillpayConfig $config, BillpayToCountryBridgeInterface $country)
    {
        $this->config = $config;
        $this->country = $country;
    }

    /**
     * @return \SprykerEco\Zed\Billpay\BillpayConfig
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function buildPrescoreRequest(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $paymentTransfer->setBillpay(new BillpayPaymentTransfer());
            $paymentTransfer->setBillpayInvoice(new BillpayPaymentTransfer());

            $quoteTransfer->setPayment($paymentTransfer);
        }

        return [
            BillpayConstants::PARAM_GROUP_CUSTOMER => $this->prepareCustomerFromQuoteData($quoteTransfer),
            BillpayConstants::PARAM_GROUP_SHIPPING => [self::USE_BILLING_ADDRESS => $quoteTransfer->getBillingSameAsShipping()],
            BillpayConstants::PARAM_GROUP_ARTICLES => $this->prepareCartData($quoteTransfer->getItems()),
            BillpayConstants::PARAM_GROUP_TOTALS => $this->prepareTotalsData(
                $quoteTransfer->getTotals(),
                $this->getShippingExpense($quoteTransfer->getExpenses()),
                $quoteTransfer->getShipment()->getMethod()->getName()
            ),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildPreauthorizeOrderRequest(OrderTransfer $orderTransfer)
    {
        return [
            BillpayConstants::PARAM_GROUP_CUSTOMER => $this->prepareCustomerFromOrderData(
                $orderTransfer
            ),
            BillpayConstants::PARAM_GROUP_SHIPPING => [self::USE_BILLING_ADDRESS => 1],
            BillpayConstants::PARAM_GROUP_ARTICLES => $this->prepareCartData($orderTransfer->getItems()),
            BillpayConstants::PARAM_GROUP_TOTALS => $this->prepareTotalsData(
                $orderTransfer->getTotals(),
                $this->getShippingExpense($orderTransfer->getExpenses()),
                $this->getShipmentCarrierName($orderTransfer),
                $orderTransfer->getOrderReference()
            ),
            BillpayConstants::PARAM_GROUP_PRESCORE => $this->preparePrescoreData(
                $orderTransfer->getBillpayPayment()->getBptid()
            ),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildCancelOrderRequest(OrderTransfer $orderTransfer)
    {
        return [
            BillpayConstants::PARAM_GROUP_CANCEL => $this->prepareCancelOrderData($orderTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildEditCartContentRequest(OrderTransfer $orderTransfer)
    {
        return [
            BillpayConstants::PARAM_GROUP_ARTICLES => $this->prepareCartData($orderTransfer->getItems()),
            BillpayConstants::PARAM_GROUP_TOTALS => $this->prepareTotalsData(
                $orderTransfer->getTotals(),
                $this->getShippingExpense($orderTransfer->getExpenses()),
                $this->getShipmentCarrierName($orderTransfer),
                $orderTransfer->getOrderReference()
            ),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $shippingExpenseTransfer
     * @param string $shippingName
     * @param string $reference
     *
     * @return array
     */
    protected function prepareTotalsData(
        TotalsTransfer $totalsTransfer,
        ExpenseTransfer $shippingExpenseTransfer,
        $shippingName,
        $reference = ''
    ) {
        $data = [
            self::REBATE => $totalsTransfer->getDiscountTotal(),
            self::REBATE_GROSS => $totalsTransfer->getDiscountTotal(),
            self::SHIPPING_NAME => $shippingName,
            self::SHIPPING_PRICE => $shippingExpenseTransfer->getUnitGrossPrice(),
            self::SHIPPING_PRICE_GROSS => $shippingExpenseTransfer->getUnitGrossPrice(),
            self::CART_TOTAL_PRICE => $totalsTransfer->getExpenseTotal(),
            self::CART_TOTAL_PRICE_GROSS => $totalsTransfer->getGrandTotal(),
            self::CURRENCY => Store::getInstance()->getCurrencyIsoCode(),
        ];

        $data[self::REFERENCE] = $reference;

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function prepareCancelOrderData(OrderTransfer $orderTransfer)
    {
        return [
            self::REFERENCE => $orderTransfer->getBillpayPayment()->getReference(),
            self::CART_TOTAL_PRICE_GROSS => $orderTransfer->getTotals()->getGrandTotal(),
            self::CURRENCY => Store::getInstance()->getCurrencyIsoCode(),
        ];
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function getShippingExpense(ArrayObject $expenses)
    {
        foreach ($expenses as $expenseTransfer) {
            if ($expenseTransfer->getType() === ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                return $expenseTransfer;
            }
        }

        return new ExpenseTransfer();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    protected function prepareCartData(ArrayObject $items)
    {
        $cartData = [];
        foreach ($items as $item) {
            $cartData[] = $this->prepareCartItemData($item);
        }

        return $cartData;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function prepareCartItemData(ItemTransfer $itemTransfer)
    {
        return [
            self::ARTICLE_ID => $itemTransfer->getSku(),
            self::ARTICLE_QUANTITY => $itemTransfer->getQuantity(),
            self::ARTICLE_NAME => $itemTransfer->getName(),
            self::ARTICLE_DESCRIPTION => $itemTransfer->getDescription(),
            self::ARTICLE_PRICE => $itemTransfer->getUnitGrossPrice(),
            self::ARTICLE_PRICE_GROSS => $itemTransfer->getUnitGrossPrice(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return string
     */
    protected function mapCustomerType(CustomerTransfer $customer)
    {
        if ($customer->getIdCustomer()) {
            return BillpayConstants::CUSTOMER_TYPE_EXISTING_CUSTOMER;
        }

        return BillpayConstants::CUSTOMER_TYPE_GUEST;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay $paymentEntity
     *
     * @return array
     */
    public function buildCaptureRequest(OrderTransfer $orderTransfer, SpyPaymentBillpay $paymentEntity)
    {
        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay $paymentEntity
     *
     * @return array
     */
    public function buildCancelRequest(OrderTransfer $orderTransfer, SpyPaymentBillpay $paymentEntity)
    {
        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function prepareCustomerFromQuoteData(QuoteTransfer $quoteTransfer)
    {
        $data = [
            self::CUSTOMER_ID => $quoteTransfer->getCustomer()->getIdCustomer(),
            self::CUSTOMER_TYPE => $this->mapCustomerType($quoteTransfer->getCustomer()),
            self::SALUTATION => $quoteTransfer->getCustomer()->getSalutation(),
            self::TITLE => '',
            self::FIRST_NAME => $quoteTransfer->getCustomer()->getFirstName(),
            self::LAST_NAME => $quoteTransfer->getCustomer()->getLastName(),
            self::STREET => $quoteTransfer->getBillingAddress()->getAddress1(),
            self::STREET_NO => $quoteTransfer->getBillingAddress()->getAddress2(),
            self::ADDRESS_ADDITION => $quoteTransfer->getBillingAddress()->getAddress3(),
            self::ZIP => $quoteTransfer->getBillingAddress()->getZipCode(),
            self::CITY => $quoteTransfer->getBillingAddress()->getCity(),
            self::COUNTRY => $this->getIso3Code($quoteTransfer->getBillingAddress()->getIso2Code()),
            self::EMAIL => $quoteTransfer->getCustomer()->getEmail(),
            self::PHONE => $quoteTransfer->getCustomer()->getPhone(),
            self::CELL_PHONE => $quoteTransfer->getBillingAddress()->getCellPhone(),
            self::BIRTHDAY => $quoteTransfer->getPayment()->getBillpay()->getDateOfBirth(),
            self::LANGUAGE => Store::getInstance()->getCurrentLanguage(),
            self::IP => $quoteTransfer->getPayment()->getBillpay()->getClientIp(),
            self::CUSTOMER_GROUP => BillpayConstants::CUSTOMER_GROUP_B2C,
        ];

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function prepareCustomerFromOrderData(OrderTransfer $orderTransfer)
    {
        $data = [
            self::CUSTOMER_ID => $orderTransfer->getFkCustomer(),
            self::CUSTOMER_TYPE => $this->mapCustomerType($orderTransfer->getCustomer()),
            self::SALUTATION => $orderTransfer->getSalutation(),
            self::TITLE => '',
            self::FIRST_NAME => $orderTransfer->getFirstName(),
            self::LAST_NAME => $orderTransfer->getLastName(),
            self::STREET => $orderTransfer->getBillingAddress()->getAddress1(),
            self::STREET_NO => $orderTransfer->getBillingAddress()->getAddress2(),
            self::ADDRESS_ADDITION => $orderTransfer->getBillingAddress()->getAddress3(),
            self::ZIP => $orderTransfer->getBillingAddress()->getZipCode(),
            self::CITY => $orderTransfer->getBillingAddress()->getCity(),
            self::COUNTRY => $this->getIso3Code($orderTransfer->getBillingAddress()->getIso2Code()),
            self::EMAIL => $orderTransfer->getEmail(),
            self::PHONE => $orderTransfer->getBillingAddress()->getPhone(),
            self::CELL_PHONE => $orderTransfer->getBillingAddress()->getCellPhone(),
            self::BIRTHDAY => $orderTransfer->getBillpayPayment()->getDateOfBirth(),
            self::LANGUAGE => Store::getInstance()->getCurrentLanguage(),
            self::IP => $orderTransfer->getBillpayPayment()->getClientIp(),
            self::CUSTOMER_GROUP => BillpayConstants::CUSTOMER_GROUP_B2C,
        ];

        return $data;
    }

    /**
     * @param string $iso2Code
     *
     * @return string
     */
    protected function getIso3Code($iso2Code)
    {
        $country = $this->country->getCountryByIso2Code($iso2Code);
        return $country->getIso3Code();
    }

    /**
     * @param string $bptid
     *
     * @return array
     */
    protected function preparePrescoreData($bptid)
    {
        return [
            self::IS_PRESCORED => 1,
            self::BPTID => $bptid,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function prepareInvoiceCreatedData(OrderTransfer $orderTransfer)
    {
        return [
            self::CART_TOTAL_PRICE_GROSS => $orderTransfer->getTotals()->getGrandTotal(),
            self::CURRENCY => Store::getInstance()->getCurrencyIsoCode(),
            self::REFERENCE => $orderTransfer->getBillpayPayment()->getReference(),
            self::DELAYINDAYS => $this->getConfig()->getMaxDelayInDays(),
            self::IS_PARTIAL => $orderTransfer->getBillpayPayment()->getIsPartial(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getShipmentCarrierName(OrderTransfer $orderTransfer)
    {
        $selectedShipmentMethods = $orderTransfer->getShipmentMethods()->getArrayCopy();

        return array_shift($selectedShipmentMethods)->getCarrierName();
    }

}
