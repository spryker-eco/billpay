<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Handler;

use Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Billpay\Persistence\SpyPaymentBillpay;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Shared\Billpay\BillpayConfig;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayPreauthorizeException;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger\BillpayResponseLoggerInterface;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceBankAccountSaverInterface;
use SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface;

class PreauthorizeResponseHandler extends AbstractResponseHandler implements PreauthorizeResponseHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    const METHOD = 'PREAUTHORIZE';

    /**
     * @var \SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceBankAccountSaverInterface
     */
    protected $invoiceBankAccountSaver;

    /**
     * @param \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger\BillpayResponseLoggerInterface $logger
     * @param \SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceBankAccountSaverInterface $invoiceBankAccountSaver
     */
    public function __construct(
        BillpayQueryContainerInterface $queryContainer,
        BillpayResponseLoggerInterface $logger,
        InvoiceBankAccountSaverInterface $invoiceBankAccountSaver
    ) {

        parent::__construct($queryContainer, $logger);

        $this->invoiceBankAccountSaver = $invoiceBankAccountSaver;
    }

    /**
     * @param \Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function handle(
        BillpayPreauthorizeTransactionResponseTransfer $responseTransfer,
        OrderTransfer $orderTransfer
    ) {
        $this->logHeader($responseTransfer->getHeader(), self::METHOD);

        if (!$responseTransfer->getHeader()->getIsSuccess()) {
            return;
        }

        $this->handleDatabaseTransaction(function () use ($responseTransfer, $orderTransfer) {
            $this->saveBillpayOrderDetails($responseTransfer, $orderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveBillpayOrderDetails(
        BillpayPreauthorizeTransactionResponseTransfer $responseTransfer,
        OrderTransfer $orderTransfer
    ) {
        $paymentEntity = $this->getPaymentEntity($orderTransfer);

        $this->saveBptid($responseTransfer, $paymentEntity);

        $invoiceBankAccount = $this->saveInvoiceBankAccount($responseTransfer);

        foreach ($paymentEntity->getSpyPaymentBillpayOrderItems() as $item) {
            $item->setSpyPaymentBillpayInvoiceBankAccount($invoiceBankAccount);
            $item->setStatus(BillpayConfig::BILLPAY_OMS_STATUS_PREAUTHORIZED);
            $item->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer $responseTransfer
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayInvoiceBankAccount
     */
    protected function saveInvoiceBankAccount(BillpayPreauthorizeTransactionResponseTransfer $responseTransfer)
    {
        return $this->invoiceBankAccountSaver->persist($responseTransfer->getInvoiceBankAccount());
    }

    /**
     * @param \Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer $responseTransfer
     * @param \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay $paymentEntity
     *
     * @return void
     */
    protected function saveBptid(BillpayPreauthorizeTransactionResponseTransfer $responseTransfer, SpyPaymentBillpay $paymentEntity)
    {
        if ($paymentEntity->getBptid() == null) {
            $paymentEntity->setBptid($responseTransfer->getHeader()->getBptid());
            $paymentEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayPreauthorizeException
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay $paymentEntity
     */
    protected function getPaymentEntity(OrderTransfer $orderTransfer)
    {
        /** @var \Orm\Zed\Billpay\Persistence\SpyPaymentBillpay $paymentEntity */
        $paymentEntity = $this
            ->queryContainer
            ->queryPaymentBySalesOrderId($orderTransfer->getIdSalesOrder())
            ->findOne();

        if (!$paymentEntity instanceof SpyPaymentBillpay) {
            throw new BillpayPreauthorizeException(sprintf('Missing payment for Billpay sales order id %s', $orderTransfer->getIdSalesOrder()));
        }

        return $paymentEntity;
    }
}
