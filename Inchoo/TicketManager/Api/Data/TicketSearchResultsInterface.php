<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 08:16
 */

namespace Inchoo\TicketManager\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface TicketSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Inchoo\TicketManager\Api\Data\TicketInterface[]
     */
    public function getItems();

    /**
     * @param \Inchoo\TicketManager\Api\Data\TicketInterface[] $items
     * @return SearchResultsInterface
     */
    public function setItems(array $items);

}