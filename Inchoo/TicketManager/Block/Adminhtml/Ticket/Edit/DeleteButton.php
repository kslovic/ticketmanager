<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 20.08.18.
 * Time: 08:15
 */

namespace Inchoo\TicketManager\Block\Adminhtml\Ticket\Edit;


use Inchoo\TicketManager\Model\TicketRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;

/**
 * Class DeleteButton
 */
class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;

    /**
     * DeleteButton constructor.
     * @param Context $context
     * @param TicketRepository $ticketRepository
     */
    public function __construct(
        Context $context,
        TicketRepository $ticketRepository
    )
    {
        $this->context = $context;
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getTicketId()) {
            $data = [
                'label' => __('Delete Ticket'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to do this?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->context->getUrlBuilder()->getUrl('*/*/delete', ['ticket_id' => $this->getTicketId()]);
    }
    public function getTicketId()
    {
        try {
            return $this->ticketRepository->getById(
                $this->context->getRequest()->getParam('ticket_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }
}