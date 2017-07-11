<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Adapter;

use ipl_invoice_created_request;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceManagerInterface;

class InvoiceCreatedApiAdapter extends AbstractApiAdapter
{

    /**
     * @var \ipl_invoice_created_request
     */
    protected $xmlRequest;

    /**
     * @param \SprykerEco\Zed\Billpay\BillpayConfig $config
     * @param \ipl_invoice_created_request $xmlRequest
     */
    public function __construct(BillpayConfig $config, ipl_invoice_created_request $xmlRequest)
    {
        parent::__construct($config);

        //$this->xmlRequest = new ipl_invoice_created_request($this->gatewayUrl);
        $this->xmlRequest = $xmlRequest;
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function prepareData(array $data)
    {
        $this->addArticles($data);
        $this->setInvoiceParams($data);
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function addArticles(array $data)
    {
        foreach ($data[BillpayConstants::PARAM_GROUP_ARTICLES] as $article) {
            $this->xmlRequest->add_article(
                $article[InvoiceManagerInterface::ARTICLE_ID],
                $article[InvoiceManagerInterface::ARTICLE_QUANTITY],
                $article[InvoiceManagerInterface::ARTICLE_NAME],
                $article[InvoiceManagerInterface::ARTICLE_DESCRIPTION],
                $article[InvoiceManagerInterface::ARTICLE_PRICE],
                $article[InvoiceManagerInterface::ARTICLE_PRICE_GROSS]
            );
        }
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function setInvoiceParams(array $data)
    {
        $this->xmlRequest->set_invoice_params(
            $data[BillpayConstants::PARAM_GROUP_INVOICE][InvoiceManagerInterface::CART_TOTAL_PRICE_GROSS],
            $data[BillpayConstants::PARAM_GROUP_INVOICE][InvoiceManagerInterface::CURRENCY],
            $data[BillpayConstants::PARAM_GROUP_INVOICE][InvoiceManagerInterface::REFERENCE],
            $data[BillpayConstants::PARAM_GROUP_INVOICE][InvoiceManagerInterface::DELAYINDAYS],
            $data[BillpayConstants::PARAM_GROUP_INVOICE][InvoiceManagerInterface::IS_PARTIAL]
        );
    }

    /**
     * @return \ipl_xml_request
     */
    protected function getXmlRequest()
    {
        return $this->xmlRequest;
    }

}
