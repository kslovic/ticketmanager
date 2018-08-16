<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 08:28
 */

namespace Inchoo\TicketManager\Model;


use Inchoo\TicketManager\Api\Data\TicketInterface;
use Inchoo\TicketManager\Api\Data\TicketInterfaceFactory;
use Inchoo\TicketManager\Api\Data\TicketSearchResultsInterface;
use Inchoo\TicketManager\Api\Data\TicketSearchResultsInterfaceFactory;
use Inchoo\TicketManager\Api\TicketRepositoryInterface;
use Inchoo\TicketManager\Model\ResourceModel\Ticket\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

class TicketRepository implements TicketRepositoryInterface
{
    /**
     * @var TicketInterfaceFactory
     */
    protected $ticketModelFactory;

    /**
     * @var ResourceModel\Ticket
     */
    protected $ticketResource;

    /**
     * @var CollectionFactory
     */
    protected $ticketCollectionFactory;

    /**
     * @var CollectionProcessor
     */
    protected $collectionProcessor;

    /**
     * @var TicketSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * TicketRepository constructor.
     * @param TicketInterfaceFactory $ticketModelFactory
     * @param ResourceModel\Ticket $ticketResource
     * @param CollectionFactory $ticketCollectionFactory
     * @param CollectionProcessor $collectionProcessor
     * @param TicketSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        TicketInterfaceFactory $ticketModelFactory,
        \Inchoo\TicketManager\Model\ResourceModel\Ticket $ticketResource,
        CollectionFactory $ticketCollectionFactory,
        CollectionProcessor $collectionProcessor,
        TicketSearchResultsInterfaceFactory $searchResultsFactory
    )
    {
        $this->ticketModelFactory = $ticketModelFactory;
        $this->ticketResource = $ticketResource;
        $this->ticketCollectionFactory = $ticketCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @param int $ticketId
     * @return TicketInterface
     * @throws NoSuchEntityException
     */
    public function getById($ticketId)
    {
        $ticket = $this->ticketModelFactory->create();
        $this->ticketResource->load($ticket, $ticketId);
        if (!$ticket->getId()) {
            throw new NoSuchEntityException(__('News with id "%1" does not exist.', $ticketId));
        }
        return $ticket;
    }

    /**
     * @param TicketInterface $ticket
     * @return TicketInterface
     * @throws CouldNotSaveException
     */
    public function save(TicketInterface $ticket)
    {
        try {
            $this->ticketResource->save($ticket);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $ticket;
    }

    /**
     * @param TicketInterface $ticket
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(TicketInterface $ticket)
    {
        try{
            $this->ticketResource->delete($ticket);
        }
        catch (\Exception $exception){
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return TicketSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Inchoo\TicketManager\Model\ResourceModel\Ticket\Collection $collection */
        $collection = $this->ticketCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var TicketSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}