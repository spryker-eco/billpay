<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Billpay\Form;

use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use SprykerEco\Shared\Billpay\BillpaySharedConfig;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

abstract class AbstractBillpaySubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    const FIELD_API_KEY = 'api_key';
    const FIELD_SALUTATION = 'salutation';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_STREET = 'street';
    const FIELD_STREET_NO = 'street_no';
    const FIELD_ZIP = 'zip';
    const FIELD_CITY = 'city';
    const FIELD_PHONE = 'phone';

    const FIELD_DATE_OF_BIRTH = 'date_of_birth';
    const MIN_BIRTHDAY_DATE_STRING = '-18 years';
    const BILLPAY_DATE_FORMAT = 'Ymd';

    const INVOICE_PAYMENT_METHOD_AVAILABLE = 'INVOICE_PAYMENT_METHOD_AVAILABLE';

    /**
     * @return string
     */
    public function getProviderName()
    {
        return BillpaySharedConfig::PROVIDER_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addDateOfBirth(FormBuilderInterface $builder, array $options)
    {
        $dataOfBirth = $this->getValueFromOptions(self::FIELD_DATE_OF_BIRTH, $options);

        $builder->add(
            self::FIELD_DATE_OF_BIRTH,
            'hidden',
            [
                'label' => false,
                'data' => $dataOfBirth['data'],
            ]
        );

        $builder->get(self::FIELD_DATE_OF_BIRTH)
            ->addModelTransformer(new CallbackTransformer(
                function ($date) {
                    return $date;
                },
                function ($date) {
                    return date(self::BILLPAY_DATE_FORMAT, strtotime($date));
                }
            ));

        return $this;
    }

    /**
     * @param string $key
     * @param array $options
     *
     * @return array
     */
    protected function getValueFromOptions($key, array $options)
    {
        $value = '';
        if (isset($options['select_options'][$key])) {
            $value = $options['select_options'][$key];
        }

        return [
            'data' => $value,
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNotBlankConstraint()
    {
        return new NotBlank(['groups' => $this->getPropertyPath()]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createBirthdayConstraint()
    {
        return new Callback([
            'methods' => [
                function ($date, ExecutionContextInterface $context) {
                    if (strtotime($date) > strtotime(self::MIN_BIRTHDAY_DATE_STRING)) {
                        $context->addViolation('checkout.step.payment.must_be_older_than_18_years');
                    }
                },
            ],
            'groups' => $this->getPropertyPath(),
        ]);
    }
}
