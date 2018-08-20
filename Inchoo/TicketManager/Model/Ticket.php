<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 13.08.18.
 * Time: 11:46
 */

namespace Inchoo\TicketManager\Model;


use Inchoo\TicketManager\Api\Data\TicketInterface;

class Ticket extends \Magento\Framework\Model\AbstractModel implements TicketInterface
{
    /**
     * Initialize Ticket Model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Inchoo\TicketManager\Model\ResourceModel\Ticket::class);
    }

    /**
     * @return int|mixed
     */
    public function getId()
    {
        return $this->getData(self::TICKET_ID);
    }

    /**
     * @param mixed $id
     * @return TicketInterface
     */
    public function setId($id)
    {
        return $this->setData(self::TICKET_ID, $id);
    }

    /**
     * @return mixed|string
     */
    public function getSubject()
    {
        return $this->getData(self::SUBJECT);
    }

    /**
     * @param $subject
     * @return TicketInterface
     */
    public function setSubject($subject)
    {
        return $this->setData(self::SUBJECT, $subject);
    }

    /**
     * @return mixed|string
     */
    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * @param $message
     * @return TicketInterface
     */
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }


    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @param $customerId
     * @return TicketInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->getData(self::WEBSITE_ID);
    }

    /**
     * @param $websiteId
     * @return TicketInterface
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);
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
     * @return TicketInterface
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
     * @return TicketInterface
     */
    public function setUpdatedAt($updatedAt = null)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @return bool
     */
    public function getOpened()
    {
        return $this->getData(self::OPENED);
    }

    /**
     * @param $opened
     * @return TicketInterface
     */
    public function setOpened($opened = null)
    {
        return $this->setData(self::OPENED, $opened);
    }
}