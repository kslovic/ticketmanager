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

    const TICKET_ID = 'ticket_id';
    const SUBJECT = 'subject';
    const MESSAGE = 'message';

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
    public function getSubject();

    /**
     * @param $subject
     * @return void
     */
    public function setSubject($subject);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param $message
     * @return void
     */
    public function setMessage($message);

}