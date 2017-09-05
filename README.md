# Billpay Module

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
    {
      "type": "package",
      "package": {
      "name": "billpay/lib",
      "version": "1.6.0",
      "dist": {
        "url": "https://www.billpay.de/wp-content/uploads/2016/11/billpay_core_api_php_v1.6.0.zip",
          "type": "zip"
        },
        "autoload": {
          "classmap": ["", "php5/"]
        }
      }
    },
    ...
  ],
  ...

```

2 Run in console
```
composer require spryker-eco/billpay
console propel:install
console transfer:generate
console transfer:databuilder:generate
./setup --install-yves 
```

3 Set up config_default.php


## Documentation

[Spryker Documentation](http://spryker.github.io)

[Spryker BillPay Documentation (Old)](https://wiki.spryker.com/display/ECO/BillPay)

[Spryker BillPay Documentation (New)](https://academy.spryker.com/developing_with_spryker/3rd-party_integration/billpay/integration_payment_billpay.html)

[BillPay TechDocs](https://techdocs.billpay.de/en/For_developers/Introduction.html)

[Tests](https://wiki.spryker.com/display/ECO/Testing+Troubleshooting)

## Default implementation

1 In Pyz\Yves\Application\YvesBootstrap.php

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
    use SprykerEco\Shared\Billpay\BillpayConstants;
    use SprykerEco\Yves\Billpay\Plugin\BillpayCustomerHandlerPlugin;
    ...
    const CLIENT_BILLPAY = 'CLIENT_BILLPAY';
    const CUSTOMER_STEP_HANDLER = 'CUSTOMER STEP HANDLER';
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
                $plugins = new StepHandlerPluginCollection();
                $plugins->add(new BillpayCustomerHandlerPlugin(), BillpayConstants::PAYMENT_METHOD_INVOICE);
                $plugins->add(new CustomerStepHandler(), self::CUSTOMER_STEP_HANDLER);
                ...
                return $plugins;
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
    use SprykerEco\Shared\Billpay\BillpayConstants;
    use Pyz\Yves\Checkout\CheckoutDependencyProvider;
    ...
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        ...
        $this->customerStepHandler->get(CheckoutDependencyProvider::CUSTOMER_STEP_HANDLER)->addToDataClass($request, $quoteTransfer);
        $this->customerStepHandler->get(BillpayConstants::PAYMENT_METHOD_INVOICE)->addToDataClass($request, $quoteTransfer);
        
        return $quoteTransfer;
    }
```
Change
```
    public function __construct(
        ...
        StepHandlerPluginInterface $customerStepHandler
        ...
    ) { ...
```
To
```
public function __construct(
        ...
        StepHandlerPluginCollection $customerStepHandler
        ...
    ) { ...
```

5 In Pyz\Yves\Checkout\Process\Steps\ShipmentStep.php

Add Prescore

```
    use SprykerEco\Client\Billpay\BillpayClientInterface;
    ...
    /**
     * @var \SprykerEco\Client\Billpay\BillpayClientInterface
     */
     protected $billpayClient;
    ...
    public function __construct(
        ...
        BillpayClientInterface $billpayClient,
        ...
    ) {
        parent::__construct($stepRoute, $escapeRoute);
        ...
        $this->billpayClient = $billpayClient;
    }
    
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        ...
        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);
        $this->billpayClient->prescorePayment($quoteTransfer);
        
        return $quoteTransfer;
        ...
    }
```

6 In Pyz\Zed\Checkout\CheckoutDependencyProvider.php

Add

```
    use SprykerEco\Zed\Billpay\Communication\Plugin\Checkout\BillpaySaveOrderPlugin;
    ...
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
    use SprykerEco\Zed\Billpay\Communication\Plugin\Oms\Command\CancelItemCommandPlugin;
    use SprykerEco\Zed\Billpay\Communication\Plugin\Oms\Command\CancelOrderCommandPlugin;
    use SprykerEco\Zed\Billpay\Communication\Plugin\Oms\Command\InvoiceCreatedCommandPlugin;
    use SprykerEco\Zed\Billpay\Communication\Plugin\Oms\Command\PreauthorizeCommandPlugin;
    use SprykerEco\Zed\Billpay\Communication\Plugin\Oms\Condition\IsCancelledConditionPlugin;
    use SprykerEco\Zed\Billpay\Communication\Plugin\Oms\Condition\IsInvoicePaidConditionPlugin;
    use SprykerEco\Zed\Billpay\Communication\Plugin\Oms\Condition\IsItemCancelledConditionPlugin;
    use SprykerEco\Zed\Billpay\Communication\Plugin\Oms\Condition\IsPreauthorizedConditionPlugin;
    ...
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

8 In Pyz\Yves\Checkout\Theme\default\checkout\payment.twig
  
  Change
  
  ```
    <li>
  ```
  to 
  ```
    <li {% if paymentForm[method.vars.value].vars.disabled == true %} style="display:none" {% endif %}>
  ```


## Tests

Run
```$xslt
./setup_test -f
```

Functional 

1 Set up [Tests](https://wiki.spryker.com/display/ECO/Testing+Troubleshooting)

2 Run 
```$xslt
codecept run Functional vendor/spryker-eco/billpay/tests/Functional
```

Unit 

1 Add to vendor/autoload.php

```
defined('APPLICATION_STORE') || define('APPLICATION_STORE', 'DE');
defined('APPLICATION') || define('APPLICATION', 'ZED');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'devtest');
defined('APPLICATION_VENDOR_DIR') || define('APPLICATION_VENDOR_DIR',  realpath(__DIR__));
```
2 Run 
```
codecept run Unit vendor/spryker-eco/billpay/tests/Unit
```

## BillPay debug info

Use 'spy_payment_billpay_api_log' table
