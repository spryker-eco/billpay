<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Adapter;

use ipl_edit_cart_content_request;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceManagerInterface;

class EditCartContentApiAdapter extends AbstractApiAdapter
{

    /**
     * @var \ipl_edit_cart_content_request
     */
    protected $xmlRequest;

    /**
     * @param \SprykerEco\Zed\Billpay\BillpayConfig $config
     * @param \ipl_edit_cart_content_request $xmlRequest
     */
    public function __construct(BillpayConfig $config, ipl_edit_cart_content_request $xmlRequest)
    {
        parent::__construct($config);

        //$this->xmlRequest = new ipl_edit_cart_content_request($this->gatewayUrl);
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
        $this->setTotal($data);
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
    protected function setTotal(array $data)
    {
        $this->xmlRequest->set_total(
            $data[BillpayConstants::PARAM_GROUP_TOTALS][InvoiceManagerInterface::REBATE],
            $data[BillpayConstants::PARAM_GROUP_TOTALS][InvoiceManagerInterface::REBATE_GROSS],
            $data[BillpayConstants::PARAM_GROUP_TOTALS][InvoiceManagerInterface::SHIPPING_NAME],
            $data[BillpayConstants::PARAM_GROUP_TOTALS][InvoiceManagerInterface::SHIPPING_PRICE],
            $data[BillpayConstants::PARAM_GROUP_TOTALS][InvoiceManagerInterface::SHIPPING_PRICE_GROSS],
            $data[BillpayConstants::PARAM_GROUP_TOTALS][InvoiceManagerInterface::CART_TOTAL_PRICE],
            $data[BillpayConstants::PARAM_GROUP_TOTALS][InvoiceManagerInterface::CART_TOTAL_PRICE_GROSS],
            $data[BillpayConstants::PARAM_GROUP_TOTALS][InvoiceManagerInterface::CURRENCY],
            $data[BillpayConstants::PARAM_GROUP_TOTALS][InvoiceManagerInterface::REFERENCE]
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
