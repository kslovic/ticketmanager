<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 08:14
 */

namespace Inchoo\TicketManager\Api\Data;


interface TicketReplyInterface
{
    const TICKET_REPLY_ID = 'ticket_reply_id';

    const TICKET_ID = 'ticket_id';

    const CONTENT = 'content';

    const ADMIN = 'admin';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

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
    /**
     * @return int
     */
    public function getTicketId();

    /**
     * @param $ticketId
     * @return $this
     */
    public function setTicketId($ticketId);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param $content
     * @return $this
     */
    public function setContent($content);
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
    public function getAdmin();

    /**
     * @param $admin
     * @return $this
     */
    public function setAdmin($admin = null);
}