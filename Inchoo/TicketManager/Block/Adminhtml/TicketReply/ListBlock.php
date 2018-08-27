<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 17.08.18.
 * Time: 08:23
 */

namespace Inchoo\TicketManager\Block\Adminhtml\TicketReply;

use Inchoo\TicketManager\Model\TicketReplyRepository;
use Inchoo\TicketManager\Model\TicketRepository;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template\Context;
use  Magento\Framework\View\Element\Template;

class ListBlock extends Template
{
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;
    /**
     * @var TicketReplyRepository
     */
    protected $ticketReplyRepository;
    /**
     * @var SortOrder
     */
    protected $sortOrder;
    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * ListBlock constructor.
     * @param Context $context
     * @param array $data
     * @param TicketReplyRepository $ticketReplyRepository
     * @param TicketRepository $ticketRepository
     * @param SortOrder $sortOrder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        Context $context,
        array $data = [],
        TicketReplyRepository $ticketReplyRepository,
        TicketRepository $ticketRepository,
        SortOrder $sortOrder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CustomerRepository $customerRepository
    )
    {
        $this->ticketRepository = $ticketRepository;
        $this->ticketReplyRepository = $ticketReplyRepository;
        $this->sortOrder = $sortOrder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->customerRepository = $customerRepository;
        parent::__construct($context, $data);
    }

    /**
     * @return bool|\Inchoo\TicketManager\Api\Data\TicketInterface
     */
    public function getTicket()
    {
        $id = $this->getRequest()->getParam('ticket_id');
        try {
            /**
             *  TicketInterface $ticket
             */
            $ticket = $this->ticketRepository->getById($id);

            return $ticket;

        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * @return \Inchoo\TicketManager\Model\ResourceModel\TicketReply\Collection
     */
    public function getTicketReplies()
    {
        //get ticket
        $ticket = $this->getTicket();
        //set sort order
        $this->sortOrder
            ->setField('created_at')
            ->setDirection('desc');
        //set current page
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        //set page size
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        //set search criteria
        $this->searchCriteriaBuilder->addFilter('ticket_id', array('eq' => $ticket->getId()))->addSortOrder($this->sortOrder)->setCurrentPage($page)->setPageSize($pageSize);

        $searchCriteria = $this->searchCriteriaBuilder->create();

        /**
         * We call Repository::getList()
         */
        $result = $this->ticketReplyRepository->getListCollection($searchCriteria);

        return $result;
    }

    /**
     * @param $id
     * @return string
     * get sender name
     */
    public function getSenderName($id)
    {
        try {
            /**
             * @var CustomerInterface $customer
             */
            $customer = $this->customerRepository->getById($id);
            return $customer->getFirstname() . " " . $customer->getLastname();
        } catch (NoSuchEntityException $e) {
            return false;
        } catch (LocalizedException $e) {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getBackUrl(){
        return $this->getUrl('*/*/', ['_secure' => true]);
    }
    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return $this|\Magento\Framework\View\Element\Template
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        //get collection
        $collection = $this->getTicketReplies();
        //set pager properties
        if ($collection) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'fme.ticket.admin.pager'
            )->setTemplate('Inchoo_TicketManager::html/pager.phtml')->setAvailableLimit(array(5 => 5, 10 => 10, 15 => 15))->setCollection(
                $collection
            );
            $this->setChild('pager', $pager);
        }
        return $this;
    }
}