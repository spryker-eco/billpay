<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Request;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderTransactionInterface extends TransactionInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return mixed
     */
    public function request(OrderTransfer $orderTransfer);

}
