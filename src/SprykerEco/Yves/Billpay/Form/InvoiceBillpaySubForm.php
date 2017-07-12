<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay\Form;

use Generated\Shared\Transfer\BillpayPaymentTransfer;
use SprykerEco\Shared\Billpay\BillpayConstants;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InvoiceBillpaySubForm extends AbstractBillpaySubForm
{

    const PAYMENT_METHOD = 'invoice';
    const PAYMENT_METHOD_NOT_AVAILABLE = 'not-available';
    const CART_AMOUNT = 'CART_AMOUNT';
    const ORDER_AMOUNT = 'ORDER_AMOUNT';
    const CURRENCY = 'CURRENCY';

    /**
     * @return string
     */
    public function getName()
    {
        return BillpayConstants::PAYMENT_METHOD_INVOICE;
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return BillpayConstants::PAYMENT_METHOD_INVOICE;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return BillpayConstants::PROVIDER_NAME . '/' . self::PAYMENT_METHOD;
    }

    /**
     * @return string
     */
    protected function getAlternateTemplatePath()
    {
        return BillpayConstants::PROVIDER_NAME . '/' . self::PAYMENT_METHOD_NOT_AVAILABLE;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BillpayPaymentTransfer::class,
        ])->setRequired(self::OPTIONS_FIELD_NAME);
    }

    /**
     * @deprecated Use `configureOptions()` instead.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addDateOfBirth($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view The view
     * @param \Symfony\Component\Form\FormInterface $form The form
     * @param array $options The options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        if ($this->getValueFromOptions(self::INVOICE_PAYMENT_METHOD_AVAILABLE, $options)['data'] === false) {
            $view->vars[self::TEMPLATE_PATH] = $this->getAlternateTemplatePath();
            return;
        }

        $view->vars[self::TEMPLATE_PATH] = $this->getTemplatePath();
    }

}
