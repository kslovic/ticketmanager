<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 13.08.18.
 * Time: 11:47
 */

namespace Inchoo\TicketManager\Model\ResourceModel\Ticket;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize ticket Collection
     *
     * @return void
     */
        protected function _construct()
    {
        $this->_init(
            \Inchoo\TicketManager\Model\Ticket::class,
            \Inchoo\TicketManager\Model\ResourceModel\Ticket::class
        );
    }

}
