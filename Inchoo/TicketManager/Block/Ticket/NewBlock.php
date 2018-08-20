<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 13.08.18.
 * Time: 15:26
 */

namespace Inchoo\TicketManager\Block\Ticket;


use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\View\Element\Template;

class NewBlock extends  \Magento\Framework\View\Element\Template
{
    /**
     * @var RedirectInterface
     */
    protected $redirect;

    /**
     * NewBlock constructor.
     * @param Template\Context $context
     * @param array $data
     * @param RedirectInterface $redirect
     */
    public function __construct(
        Template\Context $context,
        array $data = [],
        RedirectInterface $redirect
    )
    {
        parent::__construct($context, $data);
        $this->redirect = $redirect;
    }

    /**
     * Return the Url for saving.
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->_urlBuilder->getUrl(
            't_manager/ticket/save', ['_secure' => true]
        );
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {

        if ($this->redirect->getRefererUrl()) {
            return $this->redirect->getRefererUrl();
        }
        return $this->getUrl('t_manager/ticket/list', ['_secure' => true]);
    }
}