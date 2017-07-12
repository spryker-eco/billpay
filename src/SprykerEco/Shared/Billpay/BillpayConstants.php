<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Billpay;

interface BillpayConstants
{

    const PAYMENT_PROVIDER = 'Billpay';
    const GATEWAY_URL = 'GATEWAY_URL';
    const IS_TEST_MODE = 'IS_TEST_MODE';
    const VENDOR_ROOT = 'VENDOR_ROOT';

    const BILLPAY_MERCHANT_ID = 'BILLPAY_MERCHANT_ID';
    const BILLPAY_PORTAL_ID = 'BILLPAY_PORTAL_ID';
    const BILLPAY_SECURITY_KEY = 'BILLPAY_SECURITY_KEY';
    const BILLPAY_PUBLIC_API_KEY = 'BILLPAY_PUBLIC_API_KEY';
    const BILLPAY_MAX_DELAY_IN_DAYS = 'BILLPAY_MAX_DELAY_IN_DAYS';
    const USE_MD5_HASH = 'USE_MD5_HASH';

    /** Billpay payment method codes */
    const INVOICE_B2C = 'INVOICE_B2C';
    const INVOICE_B2B = 'INVOICE_B2B';

    const PROVIDER_NAME = 'billpay';
    const PAYMENT_METHOD_INVOICE = 'billpayInvoice';

    const INVOICE = 'INVOICE';

    const USE_PRESCORE = 'USE_PRESCORE';
    const CUSTOMER_GROUP = 'CUSTOMER_GROUP';

    const BILLPAY_OMS_STATUS_NEW = 'new';
    const BILLPAY_OMS_STATUS_PREAUTHORIZED = 'preauthorized';
    const BILLPAY_OMS_STATUS_INVOICE_CREATED = 'invoice created';
    const BILPAY_OMS_STATUS_PAID = 'paid';
    const BILLPAY_OMS_STATUS_PREAUTHORIZATION_FAILED = 'preauthorized_failed';
    const BILLPAY_OMS_STATUS_CANCELLED = 'cancelled';
    const BILLPAY_OMS_STATUS_ITEM_CANCELLED = 'item cancelled';
    const BILLPAY_OMS_STATUS_CLOSED = 'closed';

    /**
     * Payment methods
     */
    const METHOD_INVOICE = 'INVOICE';

    /**
     * A list of methods that
     */
    const AVAILABLE_PROVIDER_METHODS = [
        self::INVOICE_B2C
    ];

    const PARAM_GROUP_CUSTOMER = 'customer';
    const PARAM_GROUP_SHIPPING = 'shipping';
    const PARAM_GROUP_ARTICLES = 'articles';
    const PARAM_GROUP_TOTALS = 'totals';
    const PARAM_GROUP_PRESCORE = 'prescore';
    const PARAM_GROUP_INVOICE = 'invoice';
    const PARAM_GROUP_CANCEL = 'cancel';

    const PAYMENT_METHODS_MAP = [
        BillpayConstants::METHOD_INVOICE => self::IPL_CORE_PAYMENT_TYPE_INVOICE,
    ];

    //PROVIDER CONSTANTS
    const CUSTOMER_GROUP_B2B = 'b';
    const CUSTOMER_GROUP_B2C = 'p';

    const CUSTOMER_TYPE_GUEST = 'g';
    const CUSTOMER_TYPE_NEW_CUSTOMER = 'n';
    const CUSTOMER_TYPE_EXISTING_CUSTOMER = 'e';

    const RESPONSE_STATUS_PRE_APPROVED = 'PRE_APPROVED';
    const RESPONSE_STATUS_APPROVED = 'APPROVED';
    const RESPONSE_STATUS_DENIED = 'DENIED';

    // Constants taken from ipl_xml_api.php
    const IPL_CORE_PAYMENT_TYPE_INVOICE = 1;
    const IPL_CORE_PAYMENT_TYPE_DIRECT_DEBIT = 2;
    const IPL_CORE_PAYMENT_TYPE_RATE_PAYMENT = 3;
    const IPL_CORE_PAYMENT_TYPE_PAY_LATER = 4;
    const IPL_CORE_PAYMENT_TYPE_PAY_LATER_COLLATERAL_PROMISE = 7;
    const IPL_CORE_PAYMENT_TYPE_INVOICE_COLLATERAL_PROMISE = 8;
    const IPL_CORE_PAYMENT_TYPE_DIRECT_DEBIT_COLLATERAL_PROMISE = 9;

    //PROVIDER ERROR CODES
    const IPL_CORE_ERROR_CODE_SUCCESS = 0;
    const IPL_CORE_ERROR_CODE_TIMEOUT = 1;
    const IPL_CORE_ERROR_CODE_SOCKET_ERROR = 2;
    const IPL_CORE_ERROR_CODE_CURL_INIT_ERROR = 3;
    const IPL_CORE_ERROR_CODE_INVALID_HTTP_RESPONSE = 4;
    const IPL_CORE_ERROR_CODE_INVALID_HTTP_HEADER = 5;
    const IPL_CORE_ERROR_CODE_HTTP_ERROR_CODE_RECEIVED = 6;
    const IPL_CORE_ERROR_CODE_REQUEST_URL_IS_EMPTY = 7;
    const IPL_CORE_ERROR_CODE_UNKNOWN_HTTP_CLIENT = 8;
    const IPL_CORE_ERROR_CODE_UNKNOWN_XML_PARSER_LIB = 9;
    const IPL_CORE_ERROR_CODE_INVALID_XML_RESPONSE_RECEIVED = 10;
    const IPL_CORE_ERROR_CODE_FEATURE_NOT_IMPLEMENTED = 11;
    const IPL_CORE_ERROR_CODE_ERROR_PARSING_RESULT = 12;
    const IPL_CORE_ERROR_CODE_CURL_LIB_NOT_LOADED = 13;
    const IPL_CORE_ERROR_CODE_PARSE_FUNCTION_NOT_FOUND = 14;
    const IPL_CORE_ERROR_CODE_SIMPLEXML_LIB_NOT_LOADED = 15;
    const IPL_CORE_ERROR_CODE_REDIRECT_RESPONSE_RECEIVED = 16;
    const IPL_CORE_ERROR_CODE_UNSUPPORTED_PROTOCOL_VERSION = 17;
    const IPL_CORE_ERROR_CODE_TOO_MANY_REDIRECTS = 18;

}
