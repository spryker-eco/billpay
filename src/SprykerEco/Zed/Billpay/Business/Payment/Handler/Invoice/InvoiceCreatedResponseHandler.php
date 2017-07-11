<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Handler\Invoice;

use Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Billpay\Persistence\SpyPaymentBillpay;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayInvoiceException;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\AbstractResponseHandler;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger\BillpayResponseLogger;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceBankAccountPersisterInterface;
use SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface;

class InvoiceCreatedResponseHandler extends AbstractResponseHandler
{

    const METHOD = 'INVOICE';

    /**
     * @var \SprykerEco\Zed\Billpay\Business\Payment\Manager\\Invoice\InvoiceBankAccountPersisterInterface
     */
    protected $invoiceBankAccountPersister;

    /**
     * @param \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger\BillpayResponseLogger $logger
     * @param \SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceBankAccountPersisterInterface $invoiceBankAccountPersister
     */
    public function __construct(
        BillpayQueryContainerInterface $queryContainer,
        BillpayResponseLogger $logger,
        InvoiceBankAccountPersisterInterface $invoiceBankAccountPersister
    ) {

        parent::__construct($queryContainer, $logger);

        $this->invoiceBankAccountPersister = $invoiceBankAccountPersister;
    }

    /**
     * @param \Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function handle(BillpayInvoiceCreatedResponseTransfer $responseTransfer, OrderTransfer $orderTransfer)
    {
        $this->logHeader($responseTransfer->getHeader(), self::METHOD);

        if (!$responseTransfer->getHeader()->getIsSuccess()) {
            return;
        }
        $this->saveInvoiceDetails($responseTransfer, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\BillpayInvoiceCreatedResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayInvoiceException
     *
     * @return void
     */
    private function saveInvoiceDetails(
        BillpayInvoiceCreatedResponseTransfer $responseTransfer,
        OrderTransfer $orderTransfer
    ) {
        /** @var \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay $paymentEntity */
        $paymentEntity = $this
            ->queryContainer
            ->queryPaymentByBptid($orderTransfer->getBillpayPayment()->getBptid())
            ->findOne();

        if (!$paymentEntity instanceof SpyPaymentBillpay) {
            throw new BillpayInvoiceException(sprintf('Missing payment for Billpay transaction %s', $responseTransfer->getHeader()->getBptid()));
        }

        foreach ($orderTransfer->getItems() as $selectedItem) {
            foreach ($paymentEntity->getSpyPaymentBillpayOrderItems() as $item) {
                if ($item->getFkSalesOrderItem() === $selectedItem->getIdSalesOrderItem()) {
                    $this->invoiceBankAccountPersister->persist(
                        $responseTransfer->getInvoiceBankAccount(),
                        $item->getSpyPaymentBillpayInvoiceBankAccount()
                    );
                    $item->setInvoiceDuedate($responseTransfer->getInvoiceBankAccount()->getInvoiceDuedate());
                    $item->setStatus(BillpayConstants::BILLPAY_OMS_STATUS_INVOICE_CREATED);
                    $item->save();
                }
            }
        }
    }

}
