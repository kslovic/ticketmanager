<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 17.08.18.
 * Time: 08:39
 */

namespace Inchoo\TicketManager\Controller\Adminhtml\TicketReply;


use Inchoo\TicketManager\Api\Data\TicketReplyInterface;
use Inchoo\TicketManager\Api\Data\TicketReplyInterfaceFactory;
use Inchoo\TicketManager\Model\TicketReplyRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\CouldNotSaveException;

class Save extends Action
{
    /**
     * @var TicketReplyRepository
     */
    protected $ticketReplyRepository;
    /**
     * @var TicketReplyInterfaceFactory
     */
    protected $ticketReplyFactory;

    /**
     * Save constructor.
     * @param Context $context
     * @param TicketReplyRepository $ticketReplyRepository
     * @param TicketReplyInterfaceFactory $ticketReplyFactory
     */
    public function __construct(
        Context $context,
        TicketReplyRepository $ticketReplyRepository,
        TicketReplyInterfaceFactory $ticketReplyFactory
    )
    {
        parent::__construct($context);
        $this->ticketReplyRepository = $ticketReplyRepository;
        $this->ticketReplyFactory = $ticketReplyFactory;
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

    public function execute()
    {
        $id = $this->getRequest()->getParam('ticket_id');
        $content = $this->getRequest()->getParam('content');
        $error = false;
        //validate parameters
        try {
            if (!\Zend_Validate::is(trim($id), 'NotEmpty')||!\Zend_Validate::is(trim($id), 'Int')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($content), 'NotEmpty')||!\Zend_Validate::is(trim($content), 'Regex', array('pattern' => '/[0-9a-zA-Z\s\'.;-]+/'))||!\Zend_Validate::is(trim($content), 'StringLength',array('max' =>64000))) {
                $error = true;
            }
            if($error){
                throw new \Exception();
            }
        } catch (\Exception $e){
            $this->messageManager->addErrorMessage(__('We can\'t process your request right now. Your message might contain invalid characters.'));
            return $this->_redirect('ticketmanager/ticket/');
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        /**
         * @var TicketReplyInterface $ticketReply
         */
        $ticketReply = $this->ticketReplyFactory->create();
        //set data
        $ticketReply->setData($this->getRequest()->getParams());
        $ticketReply->setAdmin(true);
        $ticketReply->setCreatedAt();
        $ticketReply->setUpdatedAt();
        //save reply
        try {
            $this->ticketReplyRepository->save($ticketReply);
            return $resultRedirect->setPath('ticketmanager/ticket/show', ['ticket_id' => $ticketReply->getTicketId()]);
        } catch (CouldNotSaveException $e) {
            //catch block
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the ticket.'));
            }

        return $resultRedirect->setPath('ticketmanager/ticket/');

    }
}