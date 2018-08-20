<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 20.08.18.
 * Time: 08:27
 */

namespace Inchoo\TicketManager\Controller\Adminhtml\Ticket;


use Inchoo\TicketManager\Model\TicketRepository;
use Magento\Backend\App\Action;

class Delete extends Action
{
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param TicketRepository $ticketRepository
     */
    public function __construct(Action\Context $context, TicketRepository $ticketRepository)
    {
        parent::__construct($context);
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Delete action
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // validate parameter id
        $id = $this->getRequest()->getParam('ticket_id');
        $error = false;
        try {
            if (!\Zend_Validate::is(trim($id), 'NotEmpty')||!\Zend_Validate::is(trim($id), 'Int')) {
                $error = true;
            }
            if($error){
                throw new \Exception();
            }
        } catch (\Exception $e){
            // display error message
            $this->messageManager->addErrorMessage(__('We can\'t find a ticket to delete.'));
            // go to grid
            return $resultRedirect->setPath('*/*/');
        }
        try {
            // init model and delete
            $ticket = $this->ticketRepository->getById($id);
            $this->ticketRepository->delete($ticket);
            // display success message
            $this->messageManager->addSuccessMessage(__('You deleted the ticket.'));
            // go to grid
            return $resultRedirect->setPath('*/*/');
        } catch (\Exception $e) {
            // display error message
            $this->messageManager->addErrorMessage($e->getMessage());
            // go back to edit form
            return $resultRedirect->setPath('*/*/edit', ['ticket_id' => $id]);
        }
    }
}