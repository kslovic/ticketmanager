<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 16.08.18.
 * Time: 08:32
 */

namespace Inchoo\TicketManager\Ui\Component\Listing;


class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * DataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param \Inchoo\TicketManager\Model\ResourceModel\Ticket\CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Inchoo\TicketManager\Model\ResourceModel\Ticket\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collection = $collectionFactory->create();
    }

    /**
     *
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = $this->getCollection()->toArray();
        return $data;
    }
}