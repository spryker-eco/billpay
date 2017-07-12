<?php
/**
 * Copy over the following configs to your config
 */

use SprykerEco\Shared\Billpay\BillpayConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Oms\OmsConfig;

$config[BillpayConstants::VENDOR_ROOT] = APPLICATION_ROOT_DIR . '/vendor/spryker-eco';

$config[KernelConstants::DEPENDENCY_INJECTOR_YVES] = [
    'Checkout' => [
        'Billpay',
    ],
];

$config[KernelConstants::DEPENDENCY_INJECTOR_ZED] = [
    'Payment' => [
        'Billpay',
    ],
    'Oms' => [
        'Billpay',
    ],
];

$config[OmsConstants::PROCESS_LOCATION] = [
    OmsConfig::DEFAULT_PROCESS_LOCATION,
    $config[AfterpayConstants::VENDOR_ROOT] . '/billpay/config/Zed/Oms',
];

$config[OmsConstants::ACTIVE_PROCESSES] = [
    'BillpayInvoice01',
];

$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING] = [
    BillpayConstants::PAYMENT_METHOD_INVOICE => 'BillpayInvoice01',
];

// TEST system
$config[BillpayConstants::GATEWAY_URL] = 'https://test-api.billpay.de/xml/offline';

// LIVE system
//$config[BillpayConstants::GATEWAY_URL] = 'https://api.billpay.de/xml';

$config[BillpayConstants::BILLPAY_MERCHANT_ID] = 'BILLPAY_MERCHANT_ID';
$config[BillpayConstants::BILLPAY_PORTAL_ID] = 'BILLPAY_PORTAL_ID';
$config[BillpayConstants::BILLPAY_SECURITY_KEY] = 'BILLPAY_SECURITY_KEY';
$config[BillpayConstants::BILLPAY_PUBLIC_API_KEY] = 'BILLPAY_PUBLIC_API_KEY';
$config[BillpayConstants::BILLPAY_MAX_DELAY_IN_DAYS] = 'BILLPAY_MAX_DELAY_IN_DAYS';
$config[BillpayConstants::USE_MD5_HASH] = 'USE_MD5_HASH';
//$config[BillpayConstants::USE_PRESCORE] = 'USE_PRESCORE';
//$config[BillpayConstants::IS_TEST_MODE] = 'IS_TEST_MODE';
//$config[BillpayConstants::CUSTOMER_GROUP] = 'CUSTOMER_GROUP';

