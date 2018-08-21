<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 20.08.18.
 * Time: 14:06
 */

namespace Inchoo\TicketEmail\Observer;


use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;

class NewTicketEmail implements ObserverInterface
{
    /**
     * @var \PSr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * NewTicketEmail constructor.
     * @param \PSr\Log\LoggerInterface $logger
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        \PSr\Log\LoggerInterface $logger,
        CustomerRepository $customerRepository,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Message\ManagerInterface $messageManager

    )
    {
        $this->logger = $logger;
        $this->customerRepository = $customerRepository;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->_escaper = $escaper;
        $this->messageManager = $messageManager;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $ticket = $observer->getEvent()->getModel();
        $this->logger->info('Inchoo\TicketEmail' . $ticket->getSubject());
        try {
            $customer = $this->customerRepository->getById($ticket->getCustomerId());
        } catch (NoSuchEntityException $e) {
            return $this;
        } catch (LocalizedException $e) {
            return $this;
        }
        $ticket = [
            'email' => $this->_escaper->escapeHtml($customer->getEmail()),
            'subject' => $this->_escaper->escapeHtml($ticket->getSubject()),
            'message' => $this->_escaper->escapeHtml($ticket->getMessage())
        ];
        $email = $this->scopeConfig->getValue('trans_email/ident_support/email', ScopeInterface::SCOPE_STORE);
        $name = $this->scopeConfig->getValue('trans_email/ident_support/name', ScopeInterface::SCOPE_STORE);
        $admin = [
            'name' => $this->_escaper->escapeHtml($name),
            'email' => $this->_escaper->escapeHtml($email),
        ];
        try {
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('inchoo_ticket_email_new_ticket_email_template')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,// this is using frontend area to get the template file
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars($ticket)
                ->setFrom($admin)
                ->addTo($admin['email'])
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
            $this->messageManager->addSuccessMessage(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );

            return $this;
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
            $this->messageManager->addErrorMessage(__('We can\'t process your request right now. Sorry, that\'s all we know.' . $e->getMessage())
            );
            return $this;
        }
    }

}