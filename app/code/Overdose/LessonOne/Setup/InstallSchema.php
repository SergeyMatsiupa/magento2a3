<?php
namespace Overdose\LessonOne\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('lesson_one'))
            ->addColumn(
                'lesson_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Lesson ID'
            )
            ->addColumn(
                'title',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Lesson Title'
            )
            ->addColumn(
                'content',
                Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'Lesson Content'
            )
            ->setComment('Lesson One Table');
        
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}