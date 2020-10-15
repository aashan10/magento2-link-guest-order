<?php

namespace Aashan\LinkGuestOrder\Helper;

use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Model\Order;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Ui\Model\Export\MetadataProvider;

class OrderLinkHelper extends AbstractHelper
{
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var MetadataProvider
     */
    protected $metadataProvider;
    /**
     * @var Filter
     */
    protected $filter;
    /**
     * @var CustomerRepository
     */
    private $repository;
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        Context $context,
        RequestInterface $request,
        MetadataProvider $metadataProvider,
        Filter $filter,
        CustomerRepository $repository,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->metadataProvider = $metadataProvider;
        $this->filter = $filter;
        $this->repository = $repository;
        $this->messageManager = $messageManager;
    }

    /**
     * @return SearchResultInterface | Order[]
     */
    protected function getCollection()
    {
        try {
            $component = $this->filter->getComponent();
            $this->filter->prepareComponent($component);
            $this->filter->applySelectionOnTargetProvider();
            $dataProvider = $component->getContext()->getDataProvider();
            $dataProvider->setLimit(0, false);
            return $dataProvider->getSearchResult();
        } catch (LocalizedException $e) {
            return [];
        }
    }

    /**
     * @return void
     */
    public function processGuestOrders()
    {
        foreach ($this->getCollection() as $order) {
            /** @var $order Order */
            $this->linkOrderToCustomer($order);
        }
    }

    /**
     * @param Order $order
     */
    public function linkOrderToCustomer(Order $order)
    {
        $email = $order->getCustomerEmail();
        try {
            $customer = $this->repository->get($email);
            if ($customer->getId()) {
                $order->setCustomerId($customer->getId());
                $order->setCustomerIsGuest(0);
                $order->save();
                $this->messageManager->addSuccessMessage(
                    __(
                        'Order ID #%1 has been linked to customer with email `%2`.',
                        $order->getIncrementId(),
                        $email
                    )
                );
            }
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addWarningMessage(
                __(
                    'Order ID #%1 has been skipped because customer with email `%2` doesn\'t exist.',
                    $order->getIncrementId(),
                    $email
                )
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __(
                    'Order ID #%1 has been skipped. Reason:',
                    $order->getIncrementId()
                ),
                $e->getMessage()
            );
        }
    }
}
