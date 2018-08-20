<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 13:35
 */

namespace Inchoo\TicketManager\Controller\TicketReply;


use Inchoo\TicketManager\Api\Data\TicketInterface;
use Inchoo\TicketManager\Model\TicketRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Exception\NoSuchEntityException;

class ListAction extends Action
{
    /**
     * @var  \Magento\Framework\View\Result\PageFactory
     */
    protected  $resultPageFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;

    /**
     * ListAction constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param TicketRepository $ticketRepository
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        TicketRepository $ticketRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('ticket_id');
        $p = $this->getRequest()->getParam('p');
        $error = false;
        //validate
        try {
            if (!\Zend_Validate::is(trim($id), 'NotEmpty') || !\Zend_Validate::is(trim($id), 'Int')) {
                $error = true;
            }
            if(isset($p)) {
                if (!\Zend_Validate::is(trim($p), 'Int')) {
                    $error = true;
                }
            }
            if ($error) {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
            return $this->_redirect('t_manager/ticket/list');
        }
        //check if customer is logged in
        if($this->customerSession->isLoggedIn()) {
                //get ticket
                try {
                    /**
                     * @var  TicketInterface $ticket
                     */
                    $ticket = $this->ticketRepository->getById($id);
                    //check if current customer owns ticket
                    if ($this->customerSession->getCustomerId() == $ticket->getCustomerId()) {
                        return $this->resultPageFactory->create();
                    }
                } catch (NoSuchEntityException $e) {
                    $this->messageManager->addExceptionMessage($e,__('Ticket not found'));
                    return $this->_redirect('t_manager/ticket/list');
                }
        }
        return $this->_redirect('customer/account/login');

    }
}