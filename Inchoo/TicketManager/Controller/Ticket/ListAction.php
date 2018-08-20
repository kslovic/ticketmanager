<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 13.08.18.
 * Time: 14:40
 */

namespace Inchoo\TicketManager\Controller\Ticket;


class ListAction extends \Magento\Framework\App\Action\Action
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
     * ListAction constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $p = $this->getRequest()->getParam('p');
        //check p
        if(isset($p)) {
            $error = false;
            //validate p
            try {
                if (!\Zend_Validate::is(trim($p), 'Int')) {
                    $error = true;
                }
                if ($error) {
                    throw new \Exception();
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
                return $this->_redirect('t_manager/ticket/list');
            }
        }
        //check if customer is logged in
        if($this->customerSession->isLoggedIn())
            return $this->resultPageFactory->create();
        return $this->_redirect('customer/account/login');
    }
}