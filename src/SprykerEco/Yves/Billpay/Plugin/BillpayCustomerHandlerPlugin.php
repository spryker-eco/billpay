<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay\Plugin;

use Generated\Shared\Transfer\BillpayPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Yves\Billpay\BillpayFactory getFactory()
 */
class BillpayCustomerHandlerPlugin extends AbstractPlugin implements StepHandlerPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToDataClass(Request $request, AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $paymentTransfer->setBillpay(new BillpayPaymentTransfer());
            $paymentTransfer->setBillpayInvoice(new BillpayPaymentTransfer());

            $quoteTransfer->setPayment($paymentTransfer);
        }

        $quoteTransfer = $this
            ->getFactory()
            ->createBillpayHandler()
            ->addClientIpToQuote(
                $request->getClientIp(),
                $quoteTransfer
            );

        return $quoteTransfer;
    }
}
