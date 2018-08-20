<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 16.08.18.
 * Time: 10:07
 */

namespace Inchoo\TicketManager\Controller\Adminhtml\Ticket;


use Inchoo\TicketManager\Api\Data\TicketInterface;
use Inchoo\TicketManager\Model\TicketRepository;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;

    /**
     * Close constructor.
     * @param Action\Context $context
     * @param TicketRepository $ticketRepository
     */
    public function __construct(
        Action\Context $context,
        TicketRepository $ticketRepository

    )
    {
        $this->ticketRepository = $ticketRepository;
        parent::__construct($context);
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Inchoo_TicketManager::ticket');
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getPostValue('ticket_id');
        $status = $this->getRequest()->getPostValue('opened');
        $error = false;
        //validate id and status value
        try {
            if (!\Zend_Validate::is(trim($id), 'NotEmpty')||!\Zend_Validate::is(trim($id), 'Int')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($status), 'NotEmpty')||!\Zend_Validate::is(trim($status), 'InArray', ['haystack' => [true, false]])) {
                $error = true;
            }
            if($error){
                throw new \Exception();
            }
        } catch (\Exception $e){
            $this->messageManager->addErrorMessage(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
            return $this->_redirect('*/*/');
        }
        // get ticket
        try {
            /**
             * @var  TicketInterface $ticket
             */
             $ticket = $this->ticketRepository->getById($id);
             // set new status
             $ticket->setOpened($status);
            } catch (LocalizedException $e) {
              $this->messageManager->addErrorMessage(__('This ticket no longer exists.'));
              return $resultRedirect->setPath('*/*/');
            }
        //save ticket
        try {
            $this->ticketRepository->save($ticket);
            if ($status == 0)
                $this->messageManager->addSuccessMessage(__('You closed the ticket.'));
            else
                $this->messageManager->addSuccessMessage(__('You opened the ticket.'));
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/close', ['ticket_id' => $ticket->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the ticket.'));
            }
    }
}