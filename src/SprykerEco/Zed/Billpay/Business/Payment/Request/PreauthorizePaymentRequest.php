<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Request;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface;
use SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface;

class PreauthorizePaymentRequest extends AbstractPaymentRequest implements TransactionInterface, OrderTransactionInterface
{

    /**
     * @var \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface $adapter
     * @param \SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface $converter
     * @param \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Billpay\BillpayConfig $config
     */
    public function __construct(
        AdapterInterface $adapter,
        ConverterInterface $converter,
        BillpayQueryContainerInterface $queryContainer,
        BillpayConfig $config
    ) {
        parent::__construct(
            $adapter,
            $converter,
            $config
        );

        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer
     */
    public function request(OrderTransfer $orderTransfer)
    {
        $paymentMethod = $orderTransfer->getBillpayPayment()->getPaymentMethod();

        $requestData = $this
            ->getMethodMapper($paymentMethod)
            ->buildPreauthorizeOrderRequest($orderTransfer);

        return $this->sendRequest($requestData);
    }

}
