<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 16.08.18.
 * Time: 08:44
 */

namespace Inchoo\TicketManager\Ui\Component\Listing\Column;


use Magento\Ui\Component\Listing\Columns\Column;

class TicketActions extends Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as & $item) {
            if (isset($item['ticket_id'])) {
                $item[$this->getData('name')] = [
                    'edit' => [
                        'href' => $this->context->getUrl(
                            'ticketmanager/ticket/edit',
                            ['ticket_id' => $item['ticket_id']]
                        ),
                        'label' => __('Edit')
                    ],
                    'show' => [
                        'href' => $this->context->getUrl(
                            'ticketmanager/ticket/show',
                            ['ticket_id' => $item['ticket_id']]
                        ),
                        'label' => __('Show')
                    ]
                ];
            }
        }

        return $dataSource;
    }
}