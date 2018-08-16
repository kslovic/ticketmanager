<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 08:17
 */

namespace Inchoo\TicketManager\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface TicketReplySearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Inchoo\TicketManager\Api\Data\TicketReplyInterface[]
     */
    public function getItems();

    /**
     * @param \Inchoo\TicketManager\Api\Data\TicketReplyInterface[] $items
     * @return SearchResultsInterface
     */
    public function setItems(array $items);

}