<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 13.08.18.
 * Time: 11:49
 */

namespace Inchoo\TicketManager\Model\ResourceModel\TicketReply;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize TicketReply Collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Inchoo\TicketManager\Model\TicketReply::class,
            \Inchoo\TicketManager\Model\ResourceModel\TicketReply::class
        );
    }
}