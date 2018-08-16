<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 13:35
 */

namespace Inchoo\TicketManager\Controller\TicketReply;


use Magento\Framework\App\Action\Action;

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
     * View constructor.
     * @param  \Magento\Framework\App\Action\Context $context
     * @param  \Magento\Framework\View\Result\PageFactory $resultPageFactory
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
        if($this->customerSession->isLoggedIn())
            return $this->resultPageFactory->create();
        else
            $this->_redirect('customer/account/login');
    }
}