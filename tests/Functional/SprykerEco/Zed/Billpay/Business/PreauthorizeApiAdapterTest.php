<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Billpay\Business;

use Codeception\TestCase\Test;
use ipl_cancel_request;
use ipl_edit_cart_content_request;
use ipl_invoice_created_request;
use ipl_preauthorize_request;
use ipl_prescore_request;
use Spryker\Zed\Country\Business\CountryFacade;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\BillpayDependencyProvider;
use SprykerEco\Zed\Billpay\Business\BillpayBusinessFactory;
use SprykerEco\Zed\Billpay\Dependency\Facade\BillpayToCountryBridge;
use SprykerEco\Zed\Billpay\Persistence\BillpayQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Business
 * @group PreauthorizeApiAdapterTest
 */
class PreauthorizeApiAdapterTest extends Test
{
    const BPTID = 'dummybptid';

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | BillpayBusinessFactory
     */
    protected function createFactory()
    {
        $builder = $this->getMockBuilder(BillpayBusinessFactory::class);
        $builder->setMethods(
            [
                'createInvoiceCreatedApiRequest',
                'createCancelApiRequest',
                'createPreauthorizeApiRequest',
                'createPrescoreApiRequest',
                'createEditCartContentApiRequest',
                'getConfig',
                'getQueryContainer',
                'getProvidedDependency',
            ]
        );

        // Create a stub for the SomeClass class.
        $stub = $builder->getMock();

        // Configure the stub.
        $stub->method('createInvoiceCreatedApiRequest')
            ->willReturn($this->createInvoiceCreatedRequest());

        $stub->method('createCancelApiRequest')
            ->willReturn($this->createCancelRequest());

        $stub->method('createPreauthorizeApiRequest')
            ->willReturn($this->createPreauthorizeRequest());

        $stub->method('createPrescoreApiRequest')
            ->willReturn($this->createPrescoreRequest());

        $stub->method('createEditCartContentApiRequest')
            ->willReturn($this->createEditCartContentRequest());

        $stub->method('getConfig')
            ->willReturn(new BillpayConfig());

        $stub->method('getQueryContainer')
            ->willReturn(new BillpayQueryContainer());

        $stub->method('getProvidedDependency')
            ->with($this->equalTo(BillpayDependencyProvider::FACADE_COUNTRY))
            ->willReturn(new BillpayToCountryBridge(new CountryFacade()));

        return $stub;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | \ipl_invoice_created_request
     */
    protected function createInvoiceCreatedRequest(): \ipl_invoice_created_request
    {
        $builder = $this->getMockBuilder(ipl_invoice_created_request::class);
        $builder->setMethods(['_send']);
        $builder->setConstructorArgs(['dummy_url']);

        // Create a stub for the SomeClass class.
        $stub = $builder->getMock();

        // Configure the stub.
        $stub->method('_send')
            ->willReturn([[], [], []]);

        return $stub;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | \ipl_cancel_request
     */
    protected function createCancelRequest(): \ipl_cancel_request
    {
        $builder = $this->getMockBuilder(ipl_cancel_request::class);
        $builder->setMethods(['_send', 'ipl_core_get_api_error_info']);
        $builder->setConstructorArgs(['dummy_url']);

        // Create a stub for the SomeClass class.
        $stub = $builder->getMock();

        // Configure the stub.
        $stub->method('_send')
            ->willReturn([[], [], []]);

        $stub->method('ipl_core_get_api_error_info')
            ->willReturn(['error_code' => 0, 'customer_message' => '', 'merchant_message' => '']);

        return $stub;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | \ipl_preauthorize_request
     */
    protected function createPreauthorizeRequest(): \ipl_preauthorize_request
    {
        $builder = $this->getMockBuilder(ipl_preauthorize_request::class);
        $builder->setMethods(['_send']);
        $builder->setConstructorArgs(['dummy_url', BillpayConstants::INVOICE_B2C]);

        // Create a stub for the SomeClass class.
        $stub = $builder->getMock();

        // Configure the stub.
        $stub->method('_send')
            ->willReturn([
                [],
                $this->createPreauthorizeResponse(),
                $this->createPreauthorizeResponse(),
            ]);

        return $stub;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | \ipl_prescore_request
     */
    protected function createPrescoreRequest(): \ipl_prescore_request
    {
        $builder = $this->getMockBuilder(ipl_prescore_request::class);
        $builder->setMethods(['_send']);
        $builder->setConstructorArgs(['dummy_url', BillpayConstants::INVOICE_B2C]);

        // Create a stub for the SomeClass class.
        $stub = $builder->getMock();

        // Configure the stub.
        $stub->method('_send')
            ->willReturn([[], [], []]);

        return $stub;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | \ipl_edit_cart_content_request
     */
    protected function createEditCartContentRequest(): \ipl_edit_cart_content_request
    {
        $builder = $this->getMockBuilder(ipl_edit_cart_content_request::class);
        $builder->setMethods(['_send']);
        $builder->setConstructorArgs(['dummy_url']);

        // Create a stub for the SomeClass class.
        $stub = $builder->getMock();

        // Configure the stub.
        $stub->method('_send')
            ->willReturn([[], [], []]);

        return $stub;
    }

    /**
     * @return array
     */
    protected function createBankDetails()
    {
        $data = [];
        $data['account_holder'] = 'Dummy account holder ' . microtime();
        $data['account_number'] = 'Dummy account number ' . microtime();
        $data['bank_code'] = 'COBAFXXX';
        $data['bank_name'] = 'Commerzbank';
        $data['invoice_reference'] = 'DE-' . microtime();
        $data['invoice_duedate'] = date('Y-m-d');

        return $data;
    }

    /**
     * @return array
     */
    protected function createPreauthorizeResponse()
    {
        $data = [];
        $data['bptid'] = self::BPTID;
        $data['status'] = 'APPROVED';
        $data['account_holder'] = 'Dummy account holder ' . microtime();
        $data['account_number'] = 'Dummy account number ' . microtime();
        $data['bank_code'] = 'COBAFXXX';
        $data['bank_name'] = 'Commerzbank';
        $data['invoice_reference'] = 'DE-' . microtime();
        $data['invoice_duedate'] = date('Y-m-d');
        $data['is_prescored'] = 1;

        return $data;
    }
}
