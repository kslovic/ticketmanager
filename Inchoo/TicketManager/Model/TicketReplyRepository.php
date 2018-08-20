<?php
/**
 * Created by PhpStorm.
 * User: kristina
 * Date: 14.08.18.
 * Time: 08:28
 */

namespace Inchoo\TicketManager\Model;


use Inchoo\TicketManager\Api\Data\TicketReplyInterface;
use Inchoo\TicketManager\Api\Data\TicketReplyInterfaceFactory;
use Inchoo\TicketManager\Api\Data\TicketReplySearchResultsInterfaceFactory;
use Inchoo\TicketManager\Api\Data\TicketReplySearchResultsInterface;
use Inchoo\TicketManager\Api\TicketReplyRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Inchoo\TicketManager\Model\ResourceModel\TicketReply\CollectionFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class TicketReplyRepository implements TicketReplyRepositoryInterface
{
    /**
     * @var TicketReplyInterfaceFactory
     */
    protected $ticketReplyModelFactory;

    /**
     * @var ResourceModel\TicketReply
     */
    protected $ticketReplyResource;

    /**
     * @var CollectionFactory
     */
    protected $ticketReplyCollectionFactory;

    /**
     * @var CollectionProcessor
     */
    protected $collectionProcessor;

    /**
     * @var TicketReplySearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * TicketReplyRepository constructor.
     * @param TicketReplyInterfaceFactory $ticketReplyModelFactory
     * @param ResourceModel\TicketReply $ticketReplyResource
     * @param CollectionFactory $ticketReplyCollectionFactory
     * @param CollectionProcessor $collectionProcessor
     * @param TicketReplySearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        TicketReplyInterfaceFactory $ticketReplyModelFactory,
        \Inchoo\TicketManager\Model\ResourceModel\TicketReply $ticketReplyResource,
        CollectionFactory $ticketReplyCollectionFactory,
        CollectionProcessor $collectionProcessor,
        TicketReplySearchResultsInterfaceFactory $searchResultsFactory
    )
    {
        $this->ticketReplyModelFactory = $ticketReplyModelFactory;
        $this->ticketReplyResource = $ticketReplyResource;
        $this->ticketReplyCollectionFactory = $ticketReplyCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @param int $ticketReplyId
     * @return TicketReplyInterface
     * @throws NoSuchEntityException
     */
    public function getById($ticketReplyId)
    {
        $ticket = $this->ticketReplyModelFactory->create();
        $this->ticketReplyResource->load($ticket, $ticketReplyId);
        if (!$ticket->getId()) {
            throw new NoSuchEntityException(__('TicketReply with id "%1" does not exist.', $ticketReplyId));
        }
        return $ticket;
    }

    /**
     * @param TicketReplyInterface $ticketReply
     * @return TicketReplyInterface
     * @throws CouldNotSaveException
     */
    public function save(TicketReplyInterface $ticketReply)
    {
        try {
            $this->ticketReplyResource->save($ticketReply);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $ticketReply;
    }

    /**
     * @param TicketReplyInterface $ticketReply
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(TicketReplyInterface $ticketReply)
    {
        try{
            $this->ticketReplyResource->delete($ticketReply);
        }
        catch (\Exception $exception){
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return TicketReplySearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Inchoo\TicketManager\Model\ResourceModel\TicketReply\Collection $collection */
        $collection = $this->ticketReplyCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var TicketReplySearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Inchoo\TicketManager\Model\ResourceModel\TicketReply\Collection
     */
    public function getListCollection(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Inchoo\TicketManager\Model\ResourceModel\TicketReply\Collection $collection */
        $collection = $this->ticketReplyCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        return $collection;
    }
}