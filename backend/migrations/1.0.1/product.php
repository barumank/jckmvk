<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ProductMigration_101
 */
class ProductMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('product', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 20,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'user_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'size' => 20,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 200,
                            'after' => 'user_id'
                        ]
                    ),
                    new Column(
                        'vendor_code',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 50,
                            'after' => 'name'
                        ]
                    ),
                    new Column(
                        'rrc',
                        [
                            'type' => Column::TYPE_DECIMAL,
                            'size' => 16,
                            'scale' => 2,
                            'after' => 'vendor_code'
                        ]
                    ),
                    new Column(
                        'discount',
                        [
                            'type' => Column::TYPE_DOUBLE,

                            'after' => 'rrc'
                        ]
                    ),
                    new Column(
                        'amount',
                        [
                            'type' => Column::TYPE_DECIMAL,
                            'notNull' => true,
                            'size' => 16,
                            'scale' => 2,
                            'after' => 'discount'
                        ]
                    ),
                    new Column(
                        'image',
                        [
                            'type' => Column::TYPE_CHAR,
                            'size' => 37,
                            'after' => 'amount'
                        ]
                    ),
                    new Column(
                        'type',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 1,
                            'after' => 'image'
                        ]
                    ),
                    new Column(
                        'hash',
                        [
                            'type' => Column::TYPE_CHAR,
                            'notNull' => true,
                            'size' => 64,
                            'after' => 'type'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('hash', ['hash'], null),
                    new Index('FK_product_user', ['user_id'], null)
                ],
                'references' => [
                    new Reference(
                        'FK_product_user',
                        [
                            'referencedTable' => 'user',
                            'referencedSchema' => 'mvk_crm',
                            'columns' => ['user_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'SET NULL'
                        ]
                    )
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '15',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_unicode_ci'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
