<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 13.08.18.
 * Time: 11:48
 */

namespace Inchoo\TicketManager\Model;


use Inchoo\TicketManager\Api\Data\TicketReplyInterface;

class TicketReply extends \Magento\Framework\Model\AbstractModel implements TicketReplyInterface
{
    /**
     * Initialize TicketReply Model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Inchoo\TicketManager\Model\ResourceModel\TicketReply::class);
    }

    /**
     * @return int
     */
    public function getId()
    {
       return $this->getData(self::TICKET_REPLY_ID);
    }

    /**
     * @param int $id
     * @return TicketReplyInterface
     */
    public function setId($id)
    {
        return $this->setData(self::TICKET_REPLY_ID, $id);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * @param $content
     * @return void
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }
}