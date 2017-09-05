<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger;

use Generated\Shared\Transfer\BillpayResponseHeaderTransfer;
use Orm\Zed\Billpay\Persistence\SpyPaymentBillpayApiLog;

class BillpayResponseLogger implements BillpayResponseLoggerInterface
{

    /**
     * {@inheritdoc}
     */
    public function log(BillpayResponseHeaderTransfer $header, $method)
    {
        $logEntity = new SpyPaymentBillpayApiLog();
        $logEntity->fromArray($header->toArray());
        $logEntity->setMethod($method);
        $logEntity->save();

        return $logEntity;
    }

}
