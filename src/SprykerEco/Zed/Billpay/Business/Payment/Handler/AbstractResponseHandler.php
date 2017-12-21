<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Handler;

use Generated\Shared\Transfer\BillpayResponseHeaderTransfer;
use SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger\BillpayResponseLoggerInterface;
use SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface;

abstract class AbstractResponseHandler
{
    /**
     * @var \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger\BillpayResponseLoggerInterface
     */
    protected $logger;

    /**
     * @param \SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger\BillpayResponseLoggerInterface $logger
     */
    public function __construct(
        BillpayQueryContainerInterface $queryContainer,
        BillpayResponseLoggerInterface $logger
    ) {
        $this->queryContainer = $queryContainer;
        $this->logger = $logger;
    }

    /**
     * @param \Generated\Shared\Transfer\BillpayResponseHeaderTransfer $headerTransfer
     * @param string $method
     *
     * @return \Orm\Zed\Billpay\Persistence\Base\SpyPaymentBillpayApiLog
     */
    protected function logHeader(BillpayResponseHeaderTransfer $headerTransfer, $method)
    {
        return $this->logger->log($headerTransfer, $method);
    }
}
