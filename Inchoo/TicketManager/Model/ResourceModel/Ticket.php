<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 13.08.18.
 * Time: 11:47
 */

namespace Inchoo\TicketManager\Model\ResourceModel;


class Ticket extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize Ticket Resource
     *
     * @return void
     */
    protected function _construct()
{
    $this->_init('inchoo_ticket', 'ticket_id');
}
}