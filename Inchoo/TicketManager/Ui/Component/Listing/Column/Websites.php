<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 17.08.18.
 * Time: 10:11
 */

namespace Inchoo\TicketManager\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Store\Model\StoreManagerInterface;
/**
 * Class Websites
 */
class Websites extends Column
{


    protected $_storeManager;


    /**
     * Websites constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    )
    {
        $this->_storeManager = $storeManager;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $Websites = $this->_storeManager->getWebsites();
                $WebsiteName = '-';
                foreach ($Websites as $website) {
                    if ($website->getId() == $item['website_id']) {
                        $WebsiteName = $website->getName();
                    }
                }
                $item[$this->getData('name')] = $WebsiteName;
            }
        }
        return $dataSource;
    }
}