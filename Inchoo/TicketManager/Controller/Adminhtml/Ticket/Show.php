<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 16.08.18.
 * Time: 13:23
 */

namespace Inchoo\TicketManager\Controller\Adminhtml\Ticket;


use Inchoo\TicketManager\Api\Data\TicketInterface;
use Inchoo\TicketManager\Model\TicketRepository;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;


class  Show extends \Magento\Backend\App\Action
{
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;

    /**
     * Show constructor.
     * @param Action\Context $context
     * @param TicketRepository $ticketRepository
     */
    public function __construct(
        Action\Context $context,
        TicketRepository $ticketRepository
    )
    {
        parent::__construct($context);
        $this->ticketRepository = $ticketRepository;
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
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Zend_Validate_Exception
     * @throws \Exception
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('ticket_id');
        $p = $this->getRequest()->getParam('p');
        $error = false;
        //validate parameters
        try {
            if (!\Zend_Validate::is(trim($id), 'NotEmpty')||!\Zend_Validate::is(trim($id), 'Int')) {
                $error = true;
            }
            if(isset($p)) {
                if (!\Zend_Validate::is(trim($p), 'Int')) {
                    $error = true;
                }
            }
            if($error){
                throw new \Exception();
            }
        } catch (\Exception $e){
            $this->messageManager->addErrorMessage(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
            return $this->_redirect('ticketmanager/ticket/index');
        }
        //get ticket
        try {
                /**
                 * @var  TicketInterface $ticket
                 */
                $ticket = $this->ticketRepository->getById($id);
                //check if opened
                if ($ticket->getOpened() != false) {
                    /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
                    $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
                    $resultPage->setActiveMenu('Inchoo_TicketManager::ticket');
                    $resultPage->getConfig()->getTitle()->prepend(__('Show Ticket'));

                    return $resultPage;
                } else
                     return $resultRedirect->setPath('*/*/closedshow', array('ticket_id'=>$id));

            } catch (NoSuchEntityException $e) {
                $this->messageManager->addExceptionMessage($e, __('Ticket not found.'));
            }
        }
}