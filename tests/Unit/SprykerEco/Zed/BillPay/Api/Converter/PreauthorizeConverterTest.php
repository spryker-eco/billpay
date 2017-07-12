<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\BillPay\Api\Converter;

use Codeception\TestCase\Test;
use Exception;
use Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer;
use ipl_preauthorize_request;
use SprykerEco\Zed\Billpay\Business\Api\Converter\PreauthorizeConverter;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Api
 * @group Converter
 * @group PreauthorizeConverterTest
 */
class PreauthorizeConverterTest extends Test
{

    /**
     * @var \Billpay\UnitTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testToTransactionResponseTransfer()
    {
        $data = $this->prepareRequest([[], [], []]);
        $data->set_total(100, 10, 'DHL', 3, 3.30, 113, 114, 'EUR', 'reference');
        $data->add_article('article_id', 1, 'stuff', 'stuff_description', 3.30, 113);
        $data->send();

        $service = new PreauthorizeConverter();
        $response = $service->toTransactionResponseTransfer($data);

        $this->assertInstanceOf(BillpayPreauthorizeTransactionResponseTransfer::class, $response);
    }

    // tests
    /**
     * @return void
     */
    public function testToTransactionResponseTransferWillThrowException()
    {
        $this->expectException(Exception::class);

        $data = $this->prepareRequest(false);
        $data->send();

        $service = new PreauthorizeConverter();
        $service->toTransactionResponseTransfer($data);
    }

    /**
     * @param mixed $returnValue
     *
     * @return \PHPUnit_Framework_MockObject_MockObject | \ipl_preauthorize_request
     */
    protected function prepareRequest($returnValue = true)
    {
        $builder = $this->getMockBuilder(ipl_preauthorize_request::class);
        $builder->setMethods(['_send']);
        $builder->setConstructorArgs(['dummy_url', 'INVOICE_B2C']);

        // Create a stub for the SomeClass class.
        $stub = $builder->getMock();

        // Configure the stub.
        $stub->method('_send')
            ->willReturn($returnValue);

        return $stub;
    }

}
