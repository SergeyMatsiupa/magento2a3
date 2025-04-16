<?php
namespace Overdose\LessonOne\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Psr\Log\LoggerInterface;

/**
 * Class LessonOne
 *
 * Resource model for LessonOne entity
 */
class LessonOne extends AbstractDb
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param LoggerInterface $logger
     * @param string|null $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        LoggerInterface $logger,
        $connectionName = null
    ) {
        $this->logger = $logger;
        parent::__construct($context, $connectionName);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        // Initialize the main table and its primary key
        $this->_init('lesson_one', 'lesson_id');
    }

    /**
     * Save object data
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function save(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->logger->debug('ResourceModel LessonOne save called with data: ' . json_encode($object->getData()));
        try {
            $result = parent::save($object);
            $id = $object->getId();
            if (!$id) {
                throw new \Exception('Failed to save lesson: No ID returned after save.');
            }
            $this->logger->debug('ResourceModel LessonOne saved with ID: ' . $id);
            return $result;
        } catch (\Exception $e) {
            $this->logger->error('ResourceModel LessonOne save failed: ' . $e->getMessage());
            throw $e;
        }
    }
}