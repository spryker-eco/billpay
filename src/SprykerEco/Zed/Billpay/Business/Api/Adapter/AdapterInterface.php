<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Api\Adapter;

interface AdapterInterface
{

    /**
     * @param array $data
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayApiException
     *
     * @return \ipl_xml_request
     */
    public function sendRequest(array $data);

}
