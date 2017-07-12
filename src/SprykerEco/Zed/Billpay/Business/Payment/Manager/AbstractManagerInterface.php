<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Manager;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Billpay\Persistence\SpyPaymentBillpay;

interface AbstractManagerInterface
{

    const CURRENCY = 'currency';
    const CART_TOTAL_PRICE_GROSS = 'cart_total_price_gross';
    const CART_TOTAL_PRICE = 'cart_total_price';
    const SHIPPING_PRICE_GROSS = 'shipping_price_gross';
    const SHIPPING_PRICE = 'shipping_price';
    const SHIPPING_NAME = 'shipping_name';
    const REBATE_GROSS = 'rebate_gross';
    const REBATE = 'rebate';
    const IS_PRESCORED = 'is_prescored';
    const BPTID = 'bptid';
    const REFERENCE = 'reference';
    const DELAYINDAYS = 'delayindays';
    const IS_PARTIAL = 'is_partial';
    const ARTICLE_PRICE_GROSS = 'articlepricegross';
    const ARTICLE_PRICE = 'articleprice';
    const ARTICLE_DESCRIPTION = 'articledescription';
    const ARTICLE_ID = 'articleid';
    const ARTICLE_QUANTITY = 'articlequantity';
    const ARTICLE_NAME = 'articlename';
    const CUSTOMER_ID = 'customer_id';
    const CUSTOMER_TYPE = 'customer_type';
    const SALUTATION = 'salutation';
    const TITLE = 'title';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const STREET = 'street';
    const STREET_NO = 'street_no';
    const ADDRESS_ADDITION = 'address_addition';
    const ZIP = 'zip';
    const CITY = 'city';
    const EMAIL = 'email';
    const PHONE = 'phone';
    const CELL_PHONE = 'cell_phone';
    const BIRTHDAY = 'birthday';
    const LANGUAGE = 'language';
    const IP = 'ip';
    const CUSTOMER_GROUP = 'customer_group';
    const COUNTRY = 'country';
    const USE_BILLING_ADDRESS = 'use_billing_address';

    /**
     * @return string
     */
    public function getMethodName();

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildCancelOrderRequest(OrderTransfer $orderTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildEditCartContentRequest(OrderTransfer $orderTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay $paymentEntity
     *
     * @return array
     */
    public function buildCaptureRequest(OrderTransfer $orderTransfer, SpyPaymentBillpay $paymentEntity);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay $paymentEntity
     *
     * @return array
     */
    public function buildCancelRequest(OrderTransfer $orderTransfer, SpyPaymentBillpay $paymentEntity);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function buildPrescoreRequest(QuoteTransfer $quoteTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildPreauthorizeOrderRequest(OrderTransfer $orderTransfer);

}
