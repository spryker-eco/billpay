<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Handler\Logger;

use Generated\Shared\Transfer\BillpayResponseHeaderTransfer;

interface BillpayResponseLoggerInterface
{

    /**
     * @param \Generated\Shared\Transfer\BillpayResponseHeaderTransfer $header
     * @param string $method
     *
     * @return \Orm\Zed\Billpay\Persistence\SpyPaymentBillpayApiLog
     */
    public function log(BillpayResponseHeaderTransfer $header, $method);

}
