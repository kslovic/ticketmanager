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
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;
    /**
     * @var SortOrder
     */
    protected $sortOrder;
    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    /**
     * ListBlock constructor.
     * @param Context $context
     * @param array $data
     * @param TicketRepository $ticketRepository
     * @param SortOrder $sortOrder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     */
    public function __construct(
        Context $context,
        array $data = [],
        TicketRepository
        $ticketRepository,
        SortOrder $sortOrder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Response\RedirectInterface $redirect

    )
    {
        $this->ticketRepository= $ticketRepository;
        $this->sortOrder = $sortOrder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->customerSession = $customerSession;
        $this->redirect = $redirect;
        parent::__construct($context, $data);
    }

    /**
     * @return \Inchoo\TicketManager\Model\ResourceModel\Ticket\Collection
     */
    public function getTickets()
    {
        //set sort order
        $this->sortOrder
            ->setField("created_at")
            ->setDirection("DESC");
        //set current page
        $page=($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        //set page size
        $pageSize=($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 10;
        //set search criteria parameters
        $this->searchCriteriaBuilder->addFilter('customer_id',array('eq'=>$this->customerSession->getCustomerId()))->addSortOrder($this->sortOrder)->setPageSize($pageSize)->setCurrentPage($page);

        $searchCriteria = $this->searchCriteriaBuilder->create();

        /**
         * We call Repository::getList()
         */
        $result = $this->ticketRepository->getListCollection($searchCriteria);

        return $result;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        if ($this->redirect->getRefererUrl()) {
            return $this->redirect->getRefererUrl();
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
        return $this->getUrl('t_manager/ticket/close', ['secure'=>true , 'id'=>$id]);
    }

    /**
     * @param $id
     * @return string
     */
    public function getShowTicketRepliesUrl($id)
    {
        return $this->getUrl('t_manager/ticketreply/list', ['secure'=>true ,'ticket_id'=>$id]);
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
        $ticketCollection = $this->getTickets();
        // set pager properties
        if ($ticketCollection) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'fme.ticket.pager'
            )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(
                $ticketCollection
            );
            $this->setChild('pager', $pager);
            $ticketCollection->load();
        }
        return $this;
    }
}