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
     * @return TicketReplyInterface
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * @return int
     */
    public function getTicketId()
    {
        return $this->getData(self::TICKET_ID);
    }

    /**
     * @param $ticketId
     * @return TicketReplyInterface
     */
    public function setTicketId($ticketId)
    {
        return $this->setData(self::TICKET_ID, $ticketId);
    }

    /**
     *
     * @return string
     */
    public function getCreatedAt()
    {
         return $this->getData(self::CREATED_AT);
    }

    /**
     *
     * @param string $createdAt
     * @return TicketReplyInterface
     */
    public function setCreatedAt($createdAt = null)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     *
     * @param string $updatedAt
     * @return TicketReplyInterface
     */
    public function setUpdatedAt($updatedAt = null)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @return bool
     */
    public function getAdmin()
    {
        return $this->getData(self::ADMIN);
    }

    /**
     * @param $admin
     * @return $this
     */
    public function setAdmin($admin = null)
    {
        return $this->setData(self::ADMIN, $admin);
    }
}