<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 08:12
 */

namespace Inchoo\TicketManager\Api;


use Inchoo\TicketManager\Api\Data\TicketInterface;
use Inchoo\TicketManager\Api\Data\TicketSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface TicketRepositoryInterface
{
    /**
     * @param int $ticketReplyId
     * @return TicketInterface
     */
    public function getById($ticketReplyId);

    /**
     * @param TicketInterface $ticketReply
     * @return TicketInterface
     */
    public function save(TicketInterface$ticketReply);

    /**
     * @param TicketInterface $ticketReplyId
     * @return bool true on success
     */
    public function delete(TicketInterface $ticketReplyId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return TicketSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}