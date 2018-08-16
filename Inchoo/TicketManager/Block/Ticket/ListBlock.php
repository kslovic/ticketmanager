<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 13.08.18.
 * Time: 14:46
 */

namespace Inchoo\TicketManager\Block\Ticket;

use Inchoo\TicketManager\Model\TicketRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\View\Element\Template\Context;

class ListBlock extends \Magento\Framework\View\Element\Template
{
    protected $ticketRepository;

    protected $sortOrder;

    protected $searchCriteriaBuilder;

    protected $customerSession;

    public function __construct(
        Context $context,
        array $data = [],
        TicketRepository
        $ticketRepository,
        SortOrder $sortOrder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        $this->ticketRepository= $ticketRepository;
        $this->sortOrder = $sortOrder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * @return \Inchoo\TicketManager\Api\Data\TicketInterface[]
     */
    public function getTickets()
    {
        $this->sortOrder
            ->setField("created_at")
            ->setDirection("DESC");

        $this->searchCriteriaBuilder->addFilter('customer_id',array('eq'=>$this->customerSession->getCustomerId()))->addSortOrder($this->sortOrder)->setPageSize(10);

        $searchCriteria = $this->searchCriteriaBuilder->create();

        /**
         * We call Repository::getList()
         */
        $result = $this->ticketRepository->getList($searchCriteria)->getItems();

        return $result;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('customer/account/', ['_secure' => true]);
    }

    /**
     * @return string
     */
    public function getAddTicketUrl()
    {
        return $this->getUrl('t_manager/ticket/new/', ['_secure' => true]);
    }

    /**
     * @param $id
     * @return string
     */
    public function getCloseTicketUrl($id)
    {
        return $this->getUrl('t_manager/ticket/close', ['id'=>$id]);
    }

    /**
     * @param $id
     * @return string
     */
    public function getShowTicketRepliesUrl($id)
    {
        return $this->getUrl('t_manager/ticketreply/list', ['ticket_id'=>$id]);
    }
}