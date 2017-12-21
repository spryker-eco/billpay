<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Billpay\Business\Api\Adapter;

use Codeception\TestCase\Test;
use Exception;
use Generated\Shared\Transfer\BillpayCancelResponseTransfer;
use ipl_cancel_request;
use SprykerEco\Zed\Billpay\Business\Api\Converter\CancelOrderConverter;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Api
 * @group Converter
 * @group CancelOrderConverterTest
 */
class CancelOrderConverterTest extends Test
{
    /**
     * @var \Billpay\UnitTester
     */
    protected $tester;

    // tests
    /**
     * @return void
     */
    public function testToTransactionResponseTransfer()
    {
        $data = $this->prepareRequest();
        $data->set_cancel_params('refence', 100, 'eur');
        $data->send();

        $service = new CancelOrderConverter();
        $response = $service->toTransactionResponseTransfer($data);

        $this->assertInstanceOf(BillpayCancelResponseTransfer::class, $response);
    }

    // tests
    /**
     * @return void
     */
    public function testToTransactionResponseTransferWillThrowException()
    {
        $this->expectException(Exception::class);

        $data = $this->prepareRequest(false);
        $data->set_cancel_params('refence', 100, 'eur');
        $data->send();

        $service = new CancelOrderConverter();
        $service->toTransactionResponseTransfer($data);
    }

    /**
     * @param mixed $returnValue
     *
     * @return \PHPUnit_Framework_MockObject_MockObject | \ipl_cancel_request
     */
    protected function prepareRequest($returnValue = true)
    {
        $builder = $this->getMockBuilder(ipl_cancel_request::class);
        $builder->setMethods(['_send']);
        $builder->setConstructorArgs(['dummy_url']);

        // Create a stub for the SomeClass class.
        $stub = $builder->getMock();

        // Configure the stub.
        $stub->method('_send')
            ->willReturn($returnValue);

        return $stub;
    }
}
