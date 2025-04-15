<?php
namespace Overdose\LessonOne\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class UpgradeSchema
 *
 * Handles schema upgrades for the LessonOne module
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrades the database schema
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // Check if version is less than 1.0.1 (adds file_name)
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $table = $setup->getTable('lesson_one');
            $setup->getConnection()->addColumn(
                $table,
                'file_name',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'default' => null,
                    'comment' => 'File Name'
                ]
            );
        }

        // Check if version is less than 1.0.2 (adds file_size)
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $table = $setup->getTable('lesson_one');
            $setup->getConnection()->addColumn(
                $table,
                'file_size',
                [
                    'type' => Table::TYPE_INTEGER,
                    'nullable' => true,
                    'default' => null,
                    'comment' => 'File Size in Bytes'
                ]
            );
        }

        $setup->endSetup();
    }
}