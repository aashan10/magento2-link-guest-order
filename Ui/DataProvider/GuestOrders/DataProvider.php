<?php

namespace Aashan\LinkGuestOrder\Ui\DataProvider\GuestOrders;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $factory
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     * @throws NoSuchEntityException
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $factory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->storeManager = $storeManager;
        $this->collection = $factory->create()
            ->addFieldToSelect('*')
            ->addFieldToFilter(
                'customer_id',
                [
                    'null' => true
                ]
            )
            ->addFieldToFilter(
                'customer_is_guest',
                1
            )
            ->addFieldToFilter(
                'main_table.store_id',
                $this->storeManager->getStore()->getId()
            );

        $this->joinCustomerTable();
    }

    /**
     * Joins customer_entity table to collection.
     * @return void
     */
    protected function joinCustomerTable()
    {
        $customerTable = $this->collection->getTable('customer_entity');

        $this->collection->getSelect()->joinLeft(
            $customerTable . ' as customer',
            'main_table.customer_email = customer.email',
            [
                'customer_name' => 'CONCAT(firstname, " ", lastname)',
                'email' => 'email'
            ]
        );
    }
}
