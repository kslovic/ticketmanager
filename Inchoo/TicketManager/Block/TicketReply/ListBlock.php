<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 13:37
 */

namespace Inchoo\TicketManager\Block\TicketReply;

class ListBlock extends  \Inchoo\TicketManager\Block\Adminhtml\TicketReply\ListBlock
{
    /**
     * @return string
     */
    public function  getAddTicketReplyUrl()
    {
        return $this->_urlBuilder->getUrl(
            't_manager/ticketreply/save',
            ['_secure' => true]
        );
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('t_manager/ticket/list', ['_secure' => true]);
    }

    /**
     * @return $this|\Magento\Framework\View\Element\Template
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        //get collection
        $ticketCollection = $this->getTicketReplies();
        //set pager properties
        if ($ticketCollection) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'fme.ticket.pager'
            )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(
                $ticketCollection
            );
            $this->setChild('pager', $pager);
            $ticketCollection->load();
        }
        return $this;
    }
}