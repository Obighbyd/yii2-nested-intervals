<?php
/**
 * @link https://github.com/paulzi/yii2-nested-intervals
 * @copyright Copyright (c) 2015 PaulZi <pavel.zimakoff@gmail.com>
 * @license MIT (https://github.com/paulzi/yii2-nested-intervals/blob/master/LICENSE)
 */

namespace paulzi\nestedintervals\tests\migrations;

use yii\db\Schema;
use yii\db\Migration;

/**
 * @author PaulZi <pavel.zimakoff@gmail.com>
 */
class TestMigration extends Migration
{
    public function up()
    {
        ob_start();
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // tree
        if ($this->db->getTableSchema('{{%tree}}', true) !== null) {
            $this->dropTable('{{%tree}}');
        }
        $this->createTable('{{%tree}}', [
            'id'    => Schema::TYPE_PK,
            'lft'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'rgt'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'depth' => Schema::TYPE_INTEGER . ' NOT NULL',
            'slug'  => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
        $this->createIndex('lft1', '{{%tree}}', ['lft', 'rgt']);
        $this->createIndex('rgt1', '{{%tree}}', ['rgt']);

        // multiple tree
        if ($this->db->getTableSchema('{{%multiple_tree}}', true) !== null) {
            $this->dropTable('{{%multiple_tree}}');
        }
        $this->createTable('{{%multiple_tree}}', [
            'id'    => Schema::TYPE_PK,
            'tree'  => Schema::TYPE_INTEGER . ' NULL',
            'lft'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'rgt'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'depth' => Schema::TYPE_INTEGER . ' NOT NULL',
            'slug'  => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
        $this->createIndex('lft2', '{{%multiple_tree}}', ['tree', 'lft', 'rgt']);
        $this->createIndex('rgt2', '{{%multiple_tree}}', ['tree', 'rgt']);

        // multiple tree 64 bit
        if ($this->db->getTableSchema('{{%multiple_tree_64}}', true) !== null) {
            $this->dropTable('{{%multiple_tree_64}}');
        }
        $this->createTable('{{%multiple_tree_64}}', [
            'id'    => Schema::TYPE_PK,
            'tree'  => Schema::TYPE_BIGINT . ' NULL',
            'lft'   => Schema::TYPE_BIGINT . ' NOT NULL',
            'rgt'   => Schema::TYPE_BIGINT . ' NOT NULL',
            'depth' => Schema::TYPE_INTEGER . ' NOT NULL',
            'slug'  => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
        $this->createIndex('lft3', '{{%multiple_tree_64}}', ['tree', 'lft', 'rgt']);
        $this->createIndex('rgt3', '{{%multiple_tree_64}}', ['tree', 'rgt']);

        // update cache (sqlite bug)
        $this->db->getSchema()->getTableSchema('{{%tree}}', true);
        $this->db->getSchema()->getTableSchema('{{%multiple_tree}}', true);
        $this->db->getSchema()->getTableSchema('{{%multiple_tree_64}}', true);
        ob_end_clean();
    }
}