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
    const CONTENT = 'content';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param $id
     * @return void
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param $content
     * @return void
     */
    public function setContent($content);
}