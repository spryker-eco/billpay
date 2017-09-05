<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Billpay\Dependency\Facade;

use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;

class BillpayToOmsBridge implements BillpayToOmsInterface
{

    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct(OmsFacadeInterface $omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param string $eventId
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = [])
    {
        return $this
            ->omsFacade
            ->triggerEvent(
                $eventId,
                $orderItems,
                $logContext,
                $data
            );
    }

}
