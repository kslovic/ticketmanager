<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 08:13
 */

namespace Inchoo\TicketManager\Api\Data;


interface TicketInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const TICKET_ID = 'ticket_id';

    const SUBJECT = 'subject';

    const MESSAGE = 'message';

    const CUSTOMER_ID = 'customer_id';

    const WEBSITE_ID = 'website_id';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const OPENED = 'opened';
    /**
     * @return int
     */
    public function getId();

    /**
     * @param $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @param $subject
     * @return $this
     */
    public function setSubject($subject);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param $message
     * @return $this
     */
    public function setMessage($message);
    /**
     * @return int
     */
    public function getCustomerId();
    /**
     * @param $customerId
     * @return $this
     */
    public  function setCustomerId($customerId);
    /**
     * @return int
     */
    public function getWebsiteId();
    /**
     * @param $websiteId
     * @return $this
     */
    public  function setWebsiteId($websiteId);
      /**
       *
       * @return string
       */
    public function getCreatedAt();

    /**
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt = null);

    /**
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt = null);

    /**
     * @return bool
     */
    public function getOpened();

    /**
     * @param $opened
     * @return $this
     */
    public function setOpened($opened = null);
}