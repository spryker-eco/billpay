<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Billpay\Business;

use Generated\Shared\Transfer\BillpayPreauthorizeTransactionResponseTransfer;
use Orm\Zed\Billpay\Persistence\SpyPaymentBillpay;
use SprykerEco\Zed\Billpay\Business\BillpayFacade;
use SprykerEcoTest\Zed\Billpay\Business\Mock\OrderTransferTrait;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Billpay
 * @group Business
 * @group BillpayFacadePreauthorizePaymentTest
 */
class BillpayFacadePreauthorizePaymentTest extends PreauthorizeApiAdapterTest
{
    use OrderTransferTrait;

    /**
     * @return void
     */
    public function testPreauthorizePayment()
    {
        $payment = $this->createBillpayPayment();

        $service = new BillpayFacade();
        $service->setFactory($this->createFactory());

        $response = $service->preauthorizePayment($this->createOrder($payment));

        $this->assertInstanceOf(BillpayPreauthorizeTransactionResponseTransfer::class, $response);
    }

    /**
     *
     */
    protected function createOrder(SpyPaymentBillpay $paymentBillpay)
    {
        $orderTransfer = $this->createOrderTransfer();
        $orderTransfer->setIdSalesOrder($paymentBillpay->getFkSalesOrder());
        return $orderTransfer;
    }
}
