<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 13.08.18.
 * Time: 15:55
 */

namespace Inchoo\TicketManager\Controller\Ticket;

use Inchoo\TicketManager\Api\Data\TicketInterfaceFactory;
use Inchoo\TicketManager\Model\Ticket;
use Inchoo\TicketManager\Model\TicketRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\CouldNotSaveException;

class Save extends Action
{
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;
    /**
     * @var TicketInterfaceFactory
     */
    protected $ticketFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * Save constructor.
     * @param Context $context
     * @param TicketRepository $ticketRepository
     * @param TicketInterfaceFactory $ticketFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        TicketRepository $ticketRepository,
        TicketInterfaceFactory $ticketFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Event\ManagerInterface $eventManager
    )
    {
        parent::__construct($context);
        $this->ticketRepository = $ticketRepository;
        $this->ticketFactory = $ticketFactory;
        $this->customerSession = $customerSession;
        $this->_storeManager = $storeManager;
        $this->eventManager = $eventManager;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        /**
         * @var Ticket $ticket
         */
        //check if customer is logged in
        if($this->customerSession->isLoggedIn()) {

            $subject = $this->getRequest()->getParam('subject');
            $message = $this->getRequest()->getParam('message');
            $error = false;
            //validate
            try {
                if (!\Zend_Validate::is(trim($subject), 'NotEmpty')||!\Zend_Validate::is(trim($subject), 'Regex', array('pattern' => '/[0-9a-zA-Z\s\'.;-]+/'))||!\Zend_Validate::is(trim($subject), 'StringLength',array('max' =>255))) {
                    $error = true;
                }
                if (!\Zend_Validate::is(trim($message), 'NotEmpty')||!\Zend_Validate::is(trim($message), 'Regex', array('pattern' => '/[0-9a-zA-Z\s\'.;-]+/'))||!\Zend_Validate::is(trim($message), 'StringLength',array('max' =>64000))) {
                    $error = true;
                }
                if($error){
                    throw new \Exception();
                }
            } catch (\Exception $e){
                $this->messageManager->addErrorMessage(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
                return $this->_redirect('t_manager/ticket/list');
            }
            // set data
            $ticket = $this->ticketFactory->create();

            $ticket->setData($this->getRequest()->getParams());
            $ticket->setData('customer_id', $this->customerSession->getCustomerId());

            $website_id = $this->_storeManager->getStore()->getWebsiteId();
            $ticket->setData('website_id', $website_id);
            //save ticket
            try {
                $this->ticketRepository->save($ticket);
                $this->messageManager->addSuccessMessage(__('You successfully added new ticket.'));
                $this->eventManager->dispatch(
                    'new_ticket_created',
                    ['model' => $ticket]
                );
            } catch (CouldNotSaveException $e) {
                $this->messageManager->addErrorMessage(__('Could not save ticket'));
            }
            return $this->_redirect('t_manager/ticket/list');
        }
        return $this->_redirect('customer/account/login');

    }
}