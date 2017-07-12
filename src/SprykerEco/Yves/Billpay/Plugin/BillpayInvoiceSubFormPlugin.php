<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;

/**
 * @method \SprykerEco\Yves\Billpay\BillpayFactory getFactory()
 */
class BillpayInvoiceSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{

    /**
     * @return \SprykerEco\Yves\Billpay\Form\InvoiceBillpaySubForm
     */
    public function createSubForm()
    {
        return $this
            ->getFactory()
            ->createInvoiceForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSubFormDataProvider()
    {
        return $this
            ->getFactory()
            ->createInvoiceFormDataProvider();
    }

}
