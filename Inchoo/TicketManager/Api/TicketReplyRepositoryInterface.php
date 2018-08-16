<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 08:13
 */

namespace Inchoo\TicketManager\Api;


use Inchoo\TicketManager\Api\Data\TicketReplyInterface;
use Inchoo\TicketManager\Api\Data\TicketReplySearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface TicketReplyRepositoryInterface
{
    /**
     * @param int $ticketReplyId
     * @return TicketReplyInterface
     */
    public function getById($ticketReplyId);

    /**
     * @param TicketReplyInterface $ticketReply
     * @return TicketReplyInterface
     */
    public function save(TicketReplyInterface$ticketReply);

    /**
     * @param TicketReplyInterface $ticketReplyId
     * @return bool true on success
     */
    public function delete(TicketReplyInterface $ticketReplyId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return TicketReplySearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}