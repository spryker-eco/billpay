<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Adapter;

use ipl_preauthorize_request;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceManagerInterface;

class PreauthorizeApiAdapter extends AbstractApiAdapter
{
    /**
     * @var \ipl_preauthorize_request
     */
    protected $xmlRequest;

    /**
     * @param \SprykerEco\Zed\Billpay\BillpayConfig $config
     * @param \ipl_preauthorize_request $xmlRequest
     */
    public function __construct(BillpayConfig $config, ipl_preauthorize_request $xmlRequest)
    {
        parent::__construct($config);
        $this->xmlRequest = $xmlRequest;
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function prepareData(array $data)
    {
        $this->setCustomerDetails($data);

        $this->xmlRequest->set_shipping_details(
            $data[BillpayConstants::PARAM_GROUP_SHIPPING][InvoiceManagerInterface::USE_BILLING_ADDRESS]
        );

        $this->addArticles($data);

        $this->setTotal($data);

        $this->setPrescoreEnable($data);

        $this->xmlRequest->set_capture_request_necessary(
            0
        );
        $this->xmlRequest->set_terms_accepted(
            1
        );
        $this->xmlRequest->set_fraud_detection(
            $this->getFraudDetection()
        );
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
    protected function setCustomerDetails(array $data)
    {
        $this->xmlRequest->set_customer_details(
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::CUSTOMER_ID],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::CUSTOMER_TYPE],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::SALUTATION],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::TITLE],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::FIRST_NAME],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::LAST_NAME],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::STREET],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::STREET_NO],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::ADDRESS_ADDITION],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::ZIP],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::CITY],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::COUNTRY],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::EMAIL],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::PHONE],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::CELL_PHONE],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::BIRTHDAY],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::LANGUAGE],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::IP],
            $data[BillpayConstants::PARAM_GROUP_CUSTOMER][InvoiceManagerInterface::CUSTOMER_GROUP]
        );
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
     * @param array $data
     *
     * @return void
     */
    protected function setPrescoreEnable(array $data)
    {
        $this->xmlRequest->set_prescore_enable(
            $data[BillpayConstants::PARAM_GROUP_PRESCORE][InvoiceManagerInterface::IS_PRESCORED],
            $data[BillpayConstants::PARAM_GROUP_PRESCORE][InvoiceManagerInterface::BPTID]
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
