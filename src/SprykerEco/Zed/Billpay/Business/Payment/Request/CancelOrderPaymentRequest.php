<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Request;

use Generated\Shared\Transfer\BillpayCancelResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\CancelResponseHandler;
use SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface;

class CancelOrderPaymentRequest extends AbstractPaymentRequest implements TransactionInterface, OrderTransactionInterface
{
    /**
     * @var \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\Billpay\Business\Payment\Handler\CancelResponseHandler
     */
    protected $cancelResponseHandler;

    /**
     * @param \SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface $adapter
     * @param \SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface $converter
     * @param \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Billpay\BillpayConfig $config
     * @param \SprykerEco\Zed\Billpay\Business\Payment\Handler\CancelResponseHandler $cancelResponseHandler
     */
    public function __construct(
        AdapterInterface $adapter,
        ConverterInterface $converter,
        BillpayQueryContainerInterface $queryContainer,
        BillpayConfig $config,
        CancelResponseHandler $cancelResponseHandler
    ) {
        parent::__construct(
            $adapter,
            $converter,
            $config
        );

        $this->queryContainer = $queryContainer;
        $this->cancelResponseHandler = $cancelResponseHandler;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayCancelResponseTransfer
     */
    public function request(OrderTransfer $orderTransfer)
    {
        $paymentTransfer = $orderTransfer->getBillpayPayment();

        $requestData = $this
            ->getMethodMapper($paymentTransfer->getPaymentMethod())
            ->buildCancelOrderRequest($orderTransfer);

        $billpayResponseTransfer = $this->sendRequest($requestData);

        $this
            ->handle($billpayResponseTransfer, $orderTransfer);

        return $billpayResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\BillpayCancelResponseTransfer $billpayResponseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function handle(BillpayCancelResponseTransfer $billpayResponseTransfer, OrderTransfer $orderTransfer)
    {
        $this->cancelResponseHandler->handle($billpayResponseTransfer, $orderTransfer);
    }
}
