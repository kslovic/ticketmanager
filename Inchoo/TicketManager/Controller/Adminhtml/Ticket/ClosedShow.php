<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 18.08.18.
 * Time: 12:18
 */

namespace Inchoo\TicketManager\Controller\Adminhtml\Ticket;


use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class ClosedShow extends Action
{
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
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $p = $this->getRequest()->getParam('p');
        $id = $this->getRequest()->getParam('ticket_id');
        $error = false;
        //validate parameters p i id
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
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Inchoo_TicketManager::ticket');
        $resultPage->getConfig()->getTitle()->prepend(__('Ticket'));

        return $resultPage;
    }
}