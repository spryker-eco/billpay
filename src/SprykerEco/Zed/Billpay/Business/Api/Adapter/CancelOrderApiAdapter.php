<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Adapter;

use ipl_cancel_request;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceManagerInterface;

class CancelOrderApiAdapter extends AbstractApiAdapter
{

    /**
     * @var \ipl_cancel_request
     */
    protected $xmlRequest;

    /**
     * @param \SprykerEco\Zed\Billpay\BillpayConfig $config
     * @param \ipl_cancel_request $xmlRequest
     */
    public function __construct(BillpayConfig $config, ipl_cancel_request $xmlRequest)
    {
        parent::__construct($config);

        //$this->xmlRequest = new ipl_cancel_request($this->gatewayUrl);
        $this->xmlRequest = $xmlRequest;
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function prepareData(array $data)
    {
        $this->xmlRequest->set_cancel_params(
            $data[BillpayConstants::PARAM_GROUP_CANCEL][InvoiceManagerInterface::REFERENCE],
            $data[BillpayConstants::PARAM_GROUP_CANCEL][InvoiceManagerInterface::CART_TOTAL_PRICE_GROSS],
            $data[BillpayConstants::PARAM_GROUP_CANCEL][InvoiceManagerInterface::CURRENCY]
        );
    }

    /**
     * @return \ipl_xml_request
     */
    public function getXmlRequest()
    {
        return $this->xmlRequest;
    }

}
