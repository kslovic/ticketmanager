<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 16.08.18.
 * Time: 12:29
 */

namespace Inchoo\TicketManager\Ui\Component\Form\Ticket;

use Magento\Framework\Data\OptionSourceInterface;

class StatusOptions implements OptionSourceInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {

            $options= array(
                array(
                'label' => 'Opened',
                'value' => 1
                ),
                array(
                    'label' => 'Closed',
                    'value' => 0
                ));

        return $options;
    }
}