<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay\Form\DataProvider;

use Generated\Shared\Transfer\BillpayPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Client\Billpay\BillpayClient;
use SprykerEco\Shared\Billpay\BillpayConstants;
use SprykerEco\Yves\Billpay\BillpayConfig;
use SprykerEco\Yves\Billpay\Form\InvoiceBillpaySubForm;

class BillpayInvoiceFormDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @var \SprykerEco\Client\Billpay\BillpayClient
     */
    protected $client;

    /**
     * @var \SprykerEco\Yves\Billpay\BillpayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Client\Billpay\BillpayClient $client
     * @param \SprykerEco\Yves\Billpay\BillpayConfig $config
     */
    public function __construct(BillpayClient $client, BillpayConfig $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $paymentTransfer->setBillpay(new BillpayPaymentTransfer());
            $paymentTransfer->setBillpayInvoice(new BillpayPaymentTransfer());

            $quoteTransfer->setPayment($paymentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        return [
            InvoiceBillpaySubForm::INVOICE_PAYMENT_METHOD_AVAILABLE => $this->isInvoicePaymentAllowed($quoteTransfer),
        ];
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isInvoicePaymentAllowed(AbstractTransfer $quoteTransfer)
    {
        $paymentMethods = $this->getPaymentMethods($quoteTransfer);
        foreach ($paymentMethods as $paymentMethod) {
            if (in_array($paymentMethod->getName(), BillpayConstants::AVAILABLE_PROVIDER_METHODS)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getPaymentMethods(AbstractTransfer $quoteTransfer)
    {
        if ($this->config->isPrescoreUsed()) {
            return $quoteTransfer
                ->getPayment()
                ->getBillpay()
                ->getBillpayPrescoringTransactionResponse()
                ->getAvailablePaymentMethods();
        }

        return $this->config->getAvailableProviderMethods();
    }
}
