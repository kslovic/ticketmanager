<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 15:23
 */

namespace Inchoo\TicketManager\Controller\TicketReply;


use Inchoo\TicketManager\Api\Data\TicketReplyInterface;
use Inchoo\TicketManager\Api\Data\TicketReplyInterfaceFactory;
use Inchoo\TicketManager\Model\TicketReplyRepository;
use Inchoo\TicketManager\Model\TicketRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    /**
     * @var TicketReplyRepository
     */
    protected $ticketReplyRepository;
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;
    /**
     * @var TicketReplyInterfaceFactory
     */
    protected $ticketReplyFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Save constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param TicketReplyRepository $ticketReplyRepository
     * @param TicketRepository $ticketRepository
     * @param TicketReplyInterfaceFactory $ticketReplyFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        TicketReplyRepository $ticketReplyRepository,
        TicketRepository $ticketRepository,
        TicketReplyInterfaceFactory $ticketReplyFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->ticketReplyRepository = $ticketReplyRepository;
        $this->ticketRepository = $ticketRepository;
        $this->ticketReplyFactory = $ticketReplyFactory;
        $this->customerSession = $customerSession;
        $this->_storeManager = $storeManager;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        //check if customer is logged in
        if ($this->customerSession->isLoggedIn()) {
            /**
             * @var TicketReplyInterface $ticketReply
             */
            $ticketReply = $this->ticketReplyFactory->create();
            $id = $this->getRequest()->getParam('ticket_id');
            $content = $this->getRequest()->getParam('content');
            $error = false;
            //validate
            try {
                if (!\Zend_Validate::is(trim($id), 'NotEmpty') || !\Zend_Validate::is(trim($id), 'Int')) {
                    $error = true;
                }
                if (!\Zend_Validate::is(trim($content), 'NotEmpty') || !\Zend_Validate::is(trim($content), 'Regex', array('pattern' => '/[0-9a-zA-Z\s\'.;-]+/')) || !\Zend_Validate::is(trim($content), 'StringLength', array('max' => 64000))) {
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
                $this->messageManager->addErrorMessage(__('Ticket is not found.'));
                return $this->_redirect('t_manager/ticketreply/list');
            }
            //check if current user owns ticket
            if ($this->customerSession->getCustomer()->getId() == $ticket->getCustomerId()) {
                $ticketReply->setData($this->getRequest()->getParams());
                    //save ticket
                    try {
                        $this->ticketReplyRepository->save($ticketReply);
                    } catch (CouldNotSaveException $e) {
                        $this->messageManager->addErrorMessage(__('Ticket could not be save.'));
                    }

                    return $this->_redirect('t_manager/ticketreply/list/ticket_id/' . $ticketReply->getTicketId());
                }

        }
        return $this->_redirect('customer/account/login');

    }
}