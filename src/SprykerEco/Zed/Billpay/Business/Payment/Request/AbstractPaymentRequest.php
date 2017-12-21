<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Business\Payment\Request;

use SprykerEco\Zed\Billpay\BillpayConfig;
use SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface;
use SprykerEco\Zed\Billpay\Business\Exception\BillpayMethodMapperException;
use SprykerEco\Zed\Billpay\Business\Payment\Manager\AbstractManagerInterface;

abstract class AbstractPaymentRequest
{
    /**
     * @var \SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface
     */
    protected $converter;

    /**
     * @var \SprykerEco\Zed\Billpay\BillpayConfig
     */
    protected $config;

    /**
     * @var array|\SprykerEco\Zed\Billpay\Business\Payment\Manager\AbstractManagerInterface[]
     */
    protected $methodMappers = [];

    /**
     * @param \SprykerEco\Zed\Billpay\Business\Api\Adapter\AdapterInterface $adapter
     * @param \SprykerEco\Zed\Billpay\Business\Api\Converter\ConverterInterface $converter
     * @param \SprykerEco\Zed\Billpay\BillpayConfig $config
     */
    public function __construct(
        AdapterInterface $adapter,
        ConverterInterface $converter,
        BillpayConfig $config
    ) {
        $this->adapter = $adapter;
        $this->converter = $converter;
        $this->config = $config;
    }

    /**
     * @param \SprykerEco\Zed\Billpay\Business\Payment\Manager\AbstractManagerInterface $paymentMethod
     *
     * @return void
     */
    public function registerManager(AbstractManagerInterface $paymentMethod)
    {
        $this->methodMappers[$paymentMethod->getMethodName()] = $paymentMethod;
    }

    /**
     * @param string $methodName
     *
     * @throws \SprykerEco\Zed\Billpay\Business\Exception\BillpayMethodMapperException
     *
     * @return \SprykerEco\Zed\Billpay\Business\Payment\Manager\AbstractManagerInterface|\SprykerEco\Zed\Billpay\Business\Payment\Manager\Invoice\InvoiceManagerInterface
     */
    protected function getMethodMapper($methodName)
    {
        if (isset($this->methodMappers[$methodName]) === false) {
            throw new BillpayMethodMapperException('The method mapper is not registered.');
        }

        return $this->methodMappers[$methodName];
    }

    /**
     * @param array $requestData
     *
     * @return mixed
     */
    protected function sendRequest(array $requestData)
    {
        $xmlRequest = $this
            ->adapter
            ->sendRequest($requestData);

        $responseTransfer = $this
            ->converter
            ->toTransactionResponseTransfer(
                $xmlRequest
            );

        return $responseTransfer;
    }
}
