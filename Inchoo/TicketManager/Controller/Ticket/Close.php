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
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;
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
     * @param \Magento\Framework\App\Request\Http $request
     * @param TicketRepository $ticketRepository
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        Context $context,
       \Magento\Framework\App\Request\Http $request,
        TicketRepository $ticketRepository,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        parent::__construct($context);
        $this->request = $request;
        $this->ticketRepository = $ticketRepository;
        $this->customerSession = $customerSession;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        if($this->customerSession->isLoggedIn()) {
            $id = $this->request->getParam('id');
            try {
                $ticket = $this->ticketRepository->getById($id);

                if ($this->customerSession->getCustomer()->getId() == $ticket->getCustomerId()) {
                    $ticket->setOpened(false);
                    try {
                        $this->ticketRepository->save($ticket);
                        $this->_redirect('t_manager/ticket/list');
                    } catch (CouldNotSaveException $e) {
                    }
                }
            } catch (NoSuchEntityException $e) {
            }
        }
        else
            $this->_redirect('customer/account/login');
    }
}