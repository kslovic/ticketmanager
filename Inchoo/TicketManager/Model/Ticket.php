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


}