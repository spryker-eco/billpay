<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay\Plugin;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Yves\Billpay\BillpayFactory getFactory()
 */
class BillpayPaymentHandlerPlugin extends AbstractPlugin implements StepHandlerPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToDataClass(Request $request, AbstractTransfer $quoteTransfer)
    {
        $quoteTransfer = $this
            ->getFactory()
            ->createBillpayHandler()
            ->addPaymentToQuote(
                $quoteTransfer
            );

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
