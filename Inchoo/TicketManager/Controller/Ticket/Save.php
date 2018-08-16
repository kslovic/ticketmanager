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
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;
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
     * Save constructor.
     * @param Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param TicketRepository $ticketRepository
     * @param TicketInterfaceFactory $ticketFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Request\Http $request,
        TicketRepository $ticketRepository,
        TicketInterfaceFactory $ticketFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->request = $request;
        $this->ticketRepository = $ticketRepository;
        $this->ticketFactory = $ticketFactory;
        $this->customerSession = $customerSession;
        $this->_storeManager = $storeManager;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        /**
         * @var Ticket $ticket
         */

        if($this->customerSession->isLoggedIn()) {
            $ticket = $this->ticketFactory->create();
            $ticket->setData($this->request->getParams());


            $ticket->setData('customer_id', $this->customerSession->getCustomerId());

            $website_id = $this->_storeManager->getStore()->getWebsiteId();
            $ticket->setData('website_id', $website_id);

            try {
                $this->ticketRepository->save($ticket);
            } catch (CouldNotSaveException $e) {
                //catch block
                var_dump($e->getMessage());
            }

            $this->_redirect('t_manager/ticket/list');
        }
        else
            $this->_redirect('customer/account/login');

    }
}