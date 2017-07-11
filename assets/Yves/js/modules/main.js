var $ = require('jquery');

var paymentContainerSelector = '.bpy-checkout-container';
var billpayValue = 'billpayInvoice';

var $paymentForm = $('#payment-form');
var $paymentChoices = $('[name="paymentForm[paymentSelection]"]:radio', $paymentForm);
var $paymentContainer = $(paymentContainerSelector);

function init() {
    mapEvents();
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
