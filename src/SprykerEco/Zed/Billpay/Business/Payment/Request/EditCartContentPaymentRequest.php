<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Request;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\EditCartResponseHandler;
use SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface;

class EditCartContentPaymentRequest extends AbstractPaymentRequest implements TransactionInterface, OrderItemTransactionInterface
{
    /**
     * @var \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\Billpay\Business\Payment\Handler\EditCartResponseHandler
     */
    protected $editCartResponseHandler;

    /**
     * @param \SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface $adapter
     * @param \SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface $converter
     * @param \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Billpay\BillpayConfig $config
     * @param \SprykerEco\Zed\Billpay\Business\Payment\Handler\EditCartResponseHandler $editCartResponseHandler
     */
    public function __construct(
        AdapterInterface $adapter,
        ConverterInterface $converter,
        BillpayQueryContainerInterface $queryContainer,
        BillpayConfig $config,
        EditCartResponseHandler $editCartResponseHandler
    ) {
        parent::__construct(
            $adapter,
            $converter,
            $config
        );

        $this->queryContainer = $queryContainer;
        $this->editCartResponseHandler = $editCartResponseHandler;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayCancelResponseTransfer
     */
    public function request(OrderTransfer $orderTransfer, ItemTransfer $itemTransfer)
    {
        $paymentTransfer = $orderTransfer->getBillpayPayment();

        $requestData = $this
            ->getMethodMapper($paymentTransfer->getPaymentMethod())
            ->buildEditCartContentRequest($orderTransfer);

        $billpayResponseTransfer =  $this->sendRequest($requestData);

        $this->editCartResponseHandler->handle($billpayResponseTransfer, $itemTransfer);

        return $billpayResponseTransfer;
    }
}
