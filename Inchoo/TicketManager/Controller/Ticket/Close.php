<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 12:47
 */

namespace Inchoo\TicketManager\Controller\Ticket;

use Inchoo\TicketManager\Model\TicketRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class Close extends Action
{
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * Close constructor.
     * @param Context $context
     * @param TicketRepository $ticketRepository
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        Context $context,
        TicketRepository $ticketRepository,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        parent::__construct($context);
        $this->ticketRepository = $ticketRepository;
        $this->customerSession = $customerSession;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        //check if customer is logged in
        if ($this->customerSession->isLoggedIn()) {
            $id = $this->getRequest()->getParam('id');
            $error = false;
            //validate
            try {
                if (!\Zend_Validate::is(trim($id), 'NotEmpty') || !\Zend_Validate::is(trim($id), 'Int')) {
                    $error = true;
                }
                if ($error) {
                    throw new \Exception();
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
                return $this->_redirect('t_manager/ticket/list');
            }
            //get ticket
            try {
                $ticket = $this->ticketRepository->getById($id);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('Could not found ticket'));
                return $this->_redirect('t_manager/ticket/list');
            }
            //check if ticket owner is current user
            if ($this->customerSession->getCustomer()->getId() == $ticket->getCustomerId()) {
                //close ticket
                $ticket->setOpened(false);
                //save ticket
                try {
                    $this->ticketRepository->save($ticket);
                    $this->messageManager->addSuccessMessage(__('You closed the ticket.'));
                } catch (CouldNotSaveException $e) {
                    $this->messageManager->addErrorMessage(__('Could not save ticket'));
                }
                return $this->_redirect('t_manager/ticket/list');
                }
        }
        return $this->_redirect('customer/account/login');
    }
}