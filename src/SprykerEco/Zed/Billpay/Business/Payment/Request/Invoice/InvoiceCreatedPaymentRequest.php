<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Request\Invoice;

use Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer;
use Generated\Shared\Transfer\BillpayResponseHeaderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\Invoice\InvoiceCreatedResponseHandler;
use SprykerEco\Zed\Billpay\Business\Payment\Request\AbstractPaymentRequest;
use SprykerEco\Zed\Billpay\Business\Payment\Request\OrderTransactionInterface;
use SprykerEco\Zed\Billpay\Business\Payment\Request\TransactionInterface;
use SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface;

class InvoiceCreatedPaymentRequest extends AbstractPaymentRequest implements TransactionInterface, OrderTransactionInterface
{
    /**
     * @var \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\Billpay\Business\Payment\Handler\Invoice\InvoiceCreatedResponseHandler
     */
    protected $invoiceCreatedResponseHandler;

    /**
     * @param \SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface $adapter
     * @param \SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface $converter
     * @param \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Billpay\BillpayConfig $config
     * @param \SprykerEco\Zed\Billpay\Business\Payment\Handler\Invoice\InvoiceCreatedResponseHandler $invoiceCreatedResponseHandler
     */
    public function __construct(
        AdapterInterface $adapter,
        ConverterInterface $converter,
        BillpayQueryContainerInterface $queryContainer,
        BillpayConfig $config,
        InvoiceCreatedResponseHandler $invoiceCreatedResponseHandler
    ) {
        parent::__construct(
            $adapter,
            $converter,
            $config
        );

        $this->queryContainer = $queryContainer;
        $this->invoiceCreatedResponseHandler = $invoiceCreatedResponseHandler;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer
     */
    public function request(OrderTransfer $orderTransfer)
    {
        $requestData = $this
            ->getMethodMapper($orderTransfer->getBillpayPayment()->getPaymentMethod())
            ->buildInvoiceCreatedOrderRequest($orderTransfer);

        $billpayResponseTransfer =  $this->sendRequest($requestData);

        $this
            ->handle($billpayResponseTransfer, $orderTransfer);

        return $billpayResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer|\Generated\Shared\Transfer\BillpayResponseHeaderTransfer $billpayResponseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $orderTransfer
     *
     * @return void
     */
    protected function handle(BillpayInvoiceCreatedResponseTransfer $billpayResponseTransfer, OrderTransfer $orderTransfer)
    {
        $this->invoiceCreatedResponseHandler->handle($billpayResponseTransfer, $orderTransfer);
    }
}
