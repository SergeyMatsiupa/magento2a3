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
        $connection = $this->getConnection();
        try {
            $table = $this->getMainTable();
            $data = $object->getData();

            // Prepare data for insert/update
            $insertData = [
                'title' => $data['title'] ?? '',
                'content' => $data['content'] ?? '',
                'file_name' => $data['file_name'] ?? null,
                'file_size' => $data['file_size'] ?? null,
                'file' => $data['file'] ?? null
            ];

            if (!$object->getId()) {
                // Insert new record
                $connection->insert($table, $insertData);
                $object->setId($connection->lastInsertId($table));
                $this->logger->debug('Inserted new record with ID: ' . $object->getId());
            } else {
                // Update existing record
                $where = $connection->quoteInto('lesson_id = ?', $object->getId());
                $connection->update($table, $insertData, $where);
                $this->logger->debug('Updated record with ID: ' . $object->getId());
            }

            $result = $object;
            if (!$object->getId()) {
                throw new \Exception('Failed to save lesson: No ID returned after save.');
            }
            $this->logger->debug('ResourceModel LessonOne saved with ID: ' . $object->getId());
            return $result;
        } catch (\Exception $e) {
            $this->logger->error('ResourceModel LessonOne save failed: ' . $e->getMessage());
            throw $e;
        }
    }
}