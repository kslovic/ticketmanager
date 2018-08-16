<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 13.08.18.
 * Time: 15:26
 */

namespace Inchoo\TicketManager\Block\Ticket;


class NewBlock extends  \Magento\Framework\View\Element\Template
{
    /**
     * Return the Url for saving.
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->_urlBuilder->getUrl(
            't_manager/ticket/save',
            ['_secure' => true]
        );
    }

    public function getBackUrl()
    {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('t_manager/ticket/list', ['_secure' => true]);
    }
}