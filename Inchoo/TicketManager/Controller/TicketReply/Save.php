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
use Magento\Framework\App\Action\Action;
use Magento\Framework\Exception\CouldNotSaveException;

class Save extends Action
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;
    /**
     * @var TicketReplyRepository
     */
    protected $ticketReplyRepository;
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
     * @param \Magento\Framework\App\Request\Http $request
     * @param TicketReplyRepository $ticketReplyRepository
     * @param TicketReplyInterfaceFactory $ticketReplyFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request,
        TicketReplyRepository $ticketReplyRepository,
        TicketReplyInterfaceFactory $ticketReplyFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->request = $request;
        $this->ticketReplyRepository = $ticketReplyRepository;
        $this->ticketReplyFactory = $ticketReplyFactory;
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
         * @var TicketReplyInterface $ticketReply
         */

        if($this->customerSession->isLoggedIn()) {
            $ticketReply = $this->ticketReplyFactory->create();
            $ticketReply->setData($this->request->getParams());

            try {
                $this->ticketReplyRepository->save($ticketReply);
            } catch (CouldNotSaveException $e) {
                //catch block
                var_dump($e->getMessage());
            }

            $this->_redirect('t_manager/ticketreply/list/ticket_id/'.$ticketReply->getTicketId());
        }
        else
            $this->_redirect('customer/account/login');

    }
}