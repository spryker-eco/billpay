<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Converter;

use ipl_xml_request;

interface ConverterInterface
{

    /**
     * @param \ipl_xml_request $xmlRequest
     *
     * @return mixed
     */
    public function toTransactionResponseTransfer(ipl_xml_request $xmlRequest);

}
