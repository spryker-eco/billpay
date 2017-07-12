<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\BillPay\Api\Converter;

use Codeception\TestCase\Test;
use Exception;
use Generated\Shared\Transfer\BillpayEditCartResponseTransfer;
use ipl_edit_cart_content_request;
use SprykerEco\Zed\Billpay\Business\Api\Converter\EditCartContentConverter;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Api
 * @group Converter
 * @group EditCartConverterTest
 */
class EditCartConverterTest extends Test
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
        $data->add_invoice(100, 10, 3, 3.30, 113, 114, 'EUR', 'reference');
        $data->send();

        $service = new EditCartContentConverter();
        $response = $service->toTransactionResponseTransfer($data);

        $this->assertInstanceOf(BillpayEditCartResponseTransfer::class, $response);
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
     * @return \PHPUnit_Framework_MockObject_MockObject | \ipl_edit_cart_content_request
     */
    protected function prepareRequest($returnValue = true)
    {
        $builder = $this->getMockBuilder(ipl_edit_cart_content_request::class);
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
