<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Handler;

use Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer;
use Orm\Zed\Billpay\Persistence\SpyPaymentBillpay;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayPreauthorizeException;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger\BillpayResponseLogger;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceBankAccountPersisterInterface;
use SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface;

class PreauthorizeResponseHandler extends AbstractResponseHandler
{
    use DatabaseTransactionHandlerTrait;

    const METHOD = 'PREAUTHORIZE';

    /**
     * @var \SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceBankAccountPersisterInterface
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
     * @param \Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function handle(
        BillpayPreauthorizeTransactionResponseTransfer $responseTransfer
    ) {
        $this->logHeader($responseTransfer->getHeader(), self::METHOD);

        if (!$responseTransfer->getHeader()->getIsSuccess()) {
            return;
        }

        $this->handleDatabaseTransaction(function () use ($responseTransfer) {
            $this->saveBillpayOrderDetails($responseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer $responseTransfer
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayPreauthorizeException
     *
     * @return void
     */
    private function saveBillpayOrderDetails(BillpayPreauthorizeTransactionResponseTransfer $responseTransfer)
    {
        /** @var \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay $paymentEntity */
        $paymentEntity = $this
            ->queryContainer
            ->queryPaymentByBptid($responseTransfer->getHeader()->getBptid())
            ->findOne();

        if (!$paymentEntity instanceof SpyPaymentBillpay) {
            throw new BillpayPreauthorizeException(sprintf('Missing payment for Billpay transaction %s', $responseTransfer->getHeader()->getBptid()));
        }

        $invoiceBankAccount = $this->invoiceBankAccountPersister->persist($responseTransfer->getInvoiceBankAccount());

        foreach ($paymentEntity->getSpyPaymentBillpayOrderItems() as $item) {
            $item->setSpyPaymentBillpayInvoiceBankAccount($invoiceBankAccount);
            $item->setStatus(BillpayConstants::BILLPAY_OMS_STATUS_PREAUTHORIZED);
            $item->save();
        }
    }
}
