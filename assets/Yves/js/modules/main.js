var $ = require('jquery');

var paymentContainerSelector = '.bpy-checkout-container';
var billpayValue = 'billpayInvoice';

var $paymentForm = $('#payment-form');
var $paymentChoices = $('[name="paymentForm[paymentSelection]"]:radio', $paymentForm);
var $paymentContainer = $(paymentContainerSelector);
var $billpayChoice = $('[value="' + billpayValue + '"]:radio', $paymentForm);

function init() {
    mapEvents();
    firstInit();
}

function mapEvents() {
    $paymentForm.on('submit', onSubmit);
    $paymentChoices.on('change', onChange);
}

function onChange() {
    var value = $(this).val();

    if (value !== billpayValue) {
        return;
    }

    billpayFormInit();
}

function firstInit()
{
    if ($billpayChoice.is(':checked'))
    {
        billpayFormInit();
    }
}

function billpayFormInit()
{
    if ($paymentContainer.hasClass('is-rendered')) {
        return;
    }

    $paymentContainer.addClass('is-rendered');
    createBillpayForm();
}

function onSubmit() {
    return billpayCheckout('isValid');
}

function createBillpayForm() {
    billpayCheckout('run', {
        container: paymentContainerSelector
    });
}

init();
