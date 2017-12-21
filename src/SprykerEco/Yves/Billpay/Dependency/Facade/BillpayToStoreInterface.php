<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay\Dependency\Facade;


interface BillpayToStoreInterface
{
    /**
     * @return string
     */
    public function getCurrencyIsoCode();
}