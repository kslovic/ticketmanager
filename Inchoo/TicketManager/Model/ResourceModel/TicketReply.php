<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 13.08.18.
 * Time: 11:48
 */

namespace Inchoo\TicketManager\Model\ResourceModel;


class TicketReply extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize TicketReply Resource
     *
     * @return void
     */
    protected function _construct()
{
    $this->_init('inchoo_ticket_reply', 'ticket_reply_id');
}
}