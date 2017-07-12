# Billpay Bundle

## Installation

1 Add to composer.json 
```
  ...
  "repositories": [
    ...
    {
      "type": "git",
      "url": "git@github.com:spryker-eco/billpay.git"
    },
    ...
  ],
  ...

```

2 Run in console
```
composer require spryker-eco/billpay
```
```
console propel:install
```

3 Set up config_default.php


## Documentation

[Spryker Documentation](http://spryker.github.io)

[Spryker BillPay Documentation (Old)](https://wiki.spryker.com/display/ECO/BillPay)

[Spryker BillPay Documentation (New)](https://academy.spryker.com/developing_with_spryker/3rd-party_integration/billpay/integration_payment_billpay.html)

[BillPay TechDocs](https://techdocs.billpay.de/en/For_developers/Introduction.html)

[Tests](https://wiki.spryker.com/display/ECO/Testing+Troubleshooting)

## Default implementation

1 In Pyz\Yves\ApplicationYvesBootstrap.php

Add
```
...
use SprykerEco\Yves\Billpay\Plugin\ServiceProvider\TwigBillpayServiceProvider;
...
    protected function registerServiceProviders()
    {
        ...
        $this->application->register(new TwigBillpayServiceProvider());
        ...
    }
```

2 In Pyz\Yves\Checkout\CheckoutDependencyProvider.php

Add
```
    ...
    const CLIENT_BILLPAY = 'CLIENT_BILLPAY';
    ...
    protected function provideClients(Container $container)
    {
        ...
        $container[self::CLIENT_BILLPAY] = function (Container $container) {
            return $container->getLocator()->billpay()->client();
        };
        ...
    }
    ...
    
        protected function providePlugins(Container $container)
        {
            parent::providePlugins($container);
    
            $container[self::PLUGIN_CUSTOMER_STEP_HANDLER] = function () {
                ...
                $plugins->add(new BillpayCustomerHandlerPlugin(), BillpayConstants::PAYMENT_METHOD_INVOICE);
                ...
            };
            ...
        }
    
```

3 In Pyz\Yves\Checkout\Process\StepFactory.php

```
    protected function createShipmentStep()
    {
        return new ShipmentStep(
            ...
            $this->getProvidedDependency(CheckoutDependencyProvider::CLIENT_BILLPAY),
            ...
        );
    }

```



4 In Pyz\Yves\Checkout\Process\Steps\CustomerStep.php

Add
```
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        ...
        $this->customerStepHandler->get(BillpayConstants::PAYMENT_METHOD_INVOICE)->addToDataClass($request, $quoteTransfer);
    }
```

5 In Pyz\Yves\Checkout\Process\Steps\ShipmentStep.php

Add Prescore

```
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        ...
        $this->billpayClient->prescorePayment($quoteTransfer);
        ...
    }
```

6 In Pyz\Zed\Checkout\CheckoutDependencyProvider.php

Add

```
    protected function getCheckoutOrderSavers(Container $container)
    {
        return [
            ...
            new BillpaySaveOrderPlugin(),
        ];
    }
```

7 In Pyz\Zed\Oms\OmsDependencyProvider.php

Add

```

    protected function getConditionPlugins(Container $container)
    {
        $collection = parent::getConditionPlugins($container);
        ...
        $collection->add(new IsInvoicePaidConditionPlugin(), 'Billpay/IsInvoicePaid');
        $collection->add(new IsPreauthorizedConditionPlugin(), 'Billpay/IsPreauthorized');
        $collection->add(new IsCancelledConditionPlugin(), 'Billpay/IsCancelled');
        $collection->add(new IsItemCancelledConditionPlugin(), 'Billpay/IsItemCancelled');

        return $collection;
    }
    
    ...
    
     protected function getCommandPlugins(Container $container)
     {
        $collection = parent::getCommandPlugins($container);
        ...
        $collection->add(new PreauthorizeCommandPlugin(), 'Billpay/Preauthorize');
        $collection->add(new InvoiceCreatedCommandPlugin(), 'Billpay/InvoiceCreated');
        $collection->add(new CancelOrderCommandPlugin(), 'Billpay/CancelOrder');
        $collection->add(new CancelItemCommandPlugin(), 'Billpay/CancelItem');
    
        return $collection;
     }
    
```

