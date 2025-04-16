<?php
namespace Overdose\LessonOne\Model\ResourceModel\LessonOne;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Psr\Log\LoggerInterface;

/**
 * Class Collection
 *
 * Collection for LessonOne entity
 */
class Collection extends AbstractCollection
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param \Magento\Framework\Logger\Monolog $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        \Magento\Framework\Logger\Monolog $loggerMonolog,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->logger = $logger;
        parent::__construct($entityFactory, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Overdose\LessonOne\Model\LessonOne', 'Overdose\LessonOne\Model\ResourceModel\LessonOne');
        $this->_idFieldName = 'lesson_id';
    }

    /**
     * Load collection data
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->logger->debug('Collection _afterLoad called with size: ' . $this->getSize());
        return parent::_afterLoad();
    }
}