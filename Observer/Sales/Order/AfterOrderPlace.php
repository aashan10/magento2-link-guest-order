<?php


namespace Aashan\LinkGuestOrder\Observer\Sales\Order;


use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;

class AfterOrderPlace implements ObserverInterface
{

    /**
     * @var CollectionFactory
     */
    protected $factory;

    public function __construct(CollectionFactory $factory)
    {
        $this->factory = $factory;
    }

    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getOrder();

        if (!$order->getCustomerId()) {
            $email = $order->getCustomerEmail();
            $customer = $this->factory->create()->addFieldToFilter('email', $email);
            if ($customer->count() > 0) {
                $customer = $customer->getFirstItem();
            } else {
                $customer = false;
            }

            if ($customer) {
                /** @var Customer $customer */
                $order->setCustomerId($customer->getId());
                $order->setCustomerIsGuest(0);
                $order->save();
            }
        }
    }

}
