<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 13:37
 */

namespace Inchoo\TicketManager\Block\TicketReply;


use Inchoo\TicketManager\Model\TicketReplyRepository;
use Inchoo\TicketManager\Model\TicketRepository;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Data\Customer;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;

class ListBlock extends  Template
{

    protected $ticketRepository;

    protected $ticketReplyRepository;

    protected $sortOrder;

    protected $searchCriteriaBuilder;

    protected $customerSession;

    protected $customerRepository;

    protected $request;

    public function __construct(
        Context $context,
        array $data = [],
        TicketReplyRepository $ticketReplyRepository,
        TicketRepository $ticketRepository,
        SortOrder $sortOrder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Customer\Model\Session $customerSession,
        CustomerRepository $customerRepository,
        \Magento\Framework\App\Request\Http $request
    )
    {
        $this->ticketRepository= $ticketRepository;
        $this->ticketReplyRepository= $ticketReplyRepository;
        $this->sortOrder = $sortOrder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    /**
     * @return \Inchoo\TicketManager\Api\Data\TicketInterface
     */
    public function getTicket()
    {
        if ($this->customerSession->isLoggedIn()) {
            $id = $this->request->getParam('ticket_id');
            try {
                /**
                 *  TicketInterface $ticket
                 */
                $ticket = $this->ticketRepository->getById($id);

                return $ticket;

            } catch (NoSuchEntityException $e) {
            }
        }
    }
    /**
     * @return \Inchoo\TicketManager\Api\Data\TicketReplyInterface[]
     */

    public function getTicketReplies()
        {
            if ($this->customerSession->isLoggedIn()) {

                $ticket = $this->getTicket();

                if ($this->customerSession->getCustomerId() == $ticket->getCustomerId()) {
                    $this->sortOrder
                        ->setField("created_at")
                        ->setDirection("ASC");

                    $this->searchCriteriaBuilder->addFilter('ticket_id', array('eq' => $ticket->getId()))->addSortOrder($this->sortOrder)->setPageSize(10);

                    $searchCriteria = $this->searchCriteriaBuilder->create();

                    /**
                     * We call Repository::getList()
                     */
                    $result = $this->ticketReplyRepository->getList($searchCriteria)->getItems();

                    return $result;
                }

            }
        }

    public function getSenderName($id)
    {
        try {
            /**
             * @var CustomerInterface $customer
             */
            $customer = $this->customerRepository->getById($id);
            return $customer->getFirstname()." ".$customer->getLastname();
        } catch (NoSuchEntityException $e) {
        } catch (LocalizedException $e) {
        }
    }
    public function  getAddTicketReplyUrl()
    {
        return $this->_urlBuilder->getUrl(
            't_manager/ticketreply/save',
            ['_secure' => true]
        );
    }

    public function getBackUrl()
    {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('t_manager/ticket/list', ['_secure' => true]);
    }
}