<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 16.08.18.
 * Time: 15:12
 */

namespace Inchoo\TicketManager\Block\Adminhtml\TicketReply\Edit;


use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Reply'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}