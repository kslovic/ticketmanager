<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 17.08.18.
 * Time: 12:13
 */

namespace Inchoo\TicketManager\Ui\Component\Listing\Column;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Customers extends Column
{

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    protected $searchCriteriaBuilder;

    /**
     * Customers constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     * @param CustomerRepository $customerRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = [],
        CustomerRepository $customerRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->customerRepository = $customerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }


    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                try {
                    /**
                     * @var CustomerInterface[] $customers
                     */
                    $customers = $this->customerRepository->getList($this->searchCriteriaBuilder->create())->getItems();
                    $customerName = "-";
                    foreach ($customers as $customer) {
                        if ($customer->getId() == $item['customer_id']) {
                            $customerName = $customer->getEmail();
                        }
                    }
                    $item[$this->getData('name')] = $customerName;
                } catch (LocalizedException $e) {
                }
            }
        }
        return $dataSource;
    }
}