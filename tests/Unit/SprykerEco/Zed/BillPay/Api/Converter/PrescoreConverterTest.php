<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\BillPay\Api\Converter;

use Codeception\TestCase\Test;
use Exception;
use Generated\Shared\Transfer\BillpayPrescoringTransactionResponseTransfer;
use ipl_prescore_request;
use SprykerEco\Zed\Billpay\Business\Api\Converter\EditCartContentConverter;
use SprykerEco\Zed\Billpay\Business\Api\Converter\PrescoreConverter;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Api
 * @group Converter
 * @group PrescoreConverterTest
 */
class PrescoreConverterTest extends Test
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
        $data->add_article('article_id', 1, 'stuff', 'stuff_description', 3.30, 113);
        $data->send();

        $service = new PrescoreConverter();
        $response = $service->toTransactionResponseTransfer($data);

        $this->assertInstanceOf(BillpayPrescoringTransactionResponseTransfer::class, $response);
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

        $service = new EditCartContentConverter();
        $service->toTransactionResponseTransfer($data);
    }

    /**
     * @param mixed $returnValue
     *
     * @return \PHPUnit_Framework_MockObject_MockObject | \ipl_prescore_request
     */
    protected function prepareRequest($returnValue = true)
    {
        $builder = $this->getMockBuilder(ipl_prescore_request::class);
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
