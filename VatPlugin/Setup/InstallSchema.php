<?php
/**
 * Created by PhpStorm.
 * User: rahul.makwana61@gmail.com
 * Date: 31/7/18
 * Time: 4:37 PM
 *  PHP Version 7
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Setup
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */

namespace Rahul\VatPlugin\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use \Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 *
 * @category Vat_Discount
 * @package  Rahul\VatPlugin\Setup
 * @author   Rahul Makwana <rahul.makwana61@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     rahul.makwana61@gmail.com
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * This function will add new table named discountplugin
     *
     * @param SchemaSetupInterface   $setup   setup
     * @param ModuleContextInterface $context context
     *
     * @return null
     */
    public function install(SchemaSetupInterface $setup,
                            ModuleContextInterface $context) {
        $setup->startSetup();
        $context->getVersion();

        $table = $setup->getConnection()->newTable(
            $setup->getTable('discountplugin')
        )->addColumn(
            'vatpluginid',
            Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true],
            'VatPluginId'
        )->addColumn(
            'apikey',
            Table::TYPE_TEXT,
            255,
            [],
            'ApiKey'
        )->addColumn(
            'endpoint',
            Table::TYPE_TEXT,
            255,
            [],
            'EndPoint'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false,
                'default' => Table::TIMESTAMP_INIT],
            'Create date'
        )->addColumn(
            'updated_at',
            Table::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false,
                'default' => Table::TIMESTAMP_INIT],
            'Updated date'
        )->addIndex(
            $setup->getIdxName('discountplugin', ['vatpluginid']),
            ['vatpluginid']
        )->setComment(
            'discount plugin table'
        );
        $setup->getConnection()->createTable($table);

        $eavTable1 = $setup->getTable('quote');
        $eavTable2 = $setup->getTable('sales_order');

        $columns = [
            'save_vat_discount' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Input option',
            ]
        ];

        $connection = $setup->getConnection();
        foreach ($columns as $name => $definition) {
            $connection->addColumn($eavTable1, $name, $definition);
            $connection->addColumn($eavTable2, $name, $definition);
        }
        $setup->endSetup();
    }
}
