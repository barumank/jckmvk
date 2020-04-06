<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ObjectWorkMigration_102
 */
class ObjectWorkMigration_102 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('object_work', [
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
                        'root_organization_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'size' => 20,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'directory_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'size' => 20,
                            'after' => 'root_organization_id'
                        ]
                    ),
                    new Column(
                        'user_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'size' => 20,
                            'after' => 'directory_id'
                        ]
                    ),
                    new Column(
                        'client_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'size' => 20,
                            'after' => 'user_id'
                        ]
                    ),
                    new Column(
                        'foreman_id',
                        [
                            'type' => Column::TYPE_BIGINTEGER,
                            'unsigned' => true,
                            'size' => 20,
                            'after' => 'client_id'
                        ]
                    ),
                    new Column(
                        'name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 50,
                            'after' => 'foreman_id'
                        ]
                    ),
                    new Column(
                        'amount',
                        [
                            'type' => Column::TYPE_DECIMAL,
                            'size' => 16,
                            'scale' => 2,
                            'after' => 'name'
                        ]
                    ),
                    new Column(
                        'square',
                        [
                            'type' => Column::TYPE_DOUBLE,
                            'after' => 'amount'
                        ]
                    ),
                    new Column(
                        'floor',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'square'
                        ]
                    ),
                    new Column(
                        'radiators',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'floor'
                        ]
                    ),
                    new Column(
                        'warm_floor',
                        [
                            'type' => Column::TYPE_DOUBLE,
                            'after' => 'radiators'
                        ]
                    ),
                    new Column(
                        'boiler_room_power',
                        [
                            'type' => Column::TYPE_DOUBLE,
                            'after' => 'warm_floor'
                        ]
                    ),
                    new Column(
                        'address',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 50,
                            'after' => 'boiler_room_power'
                        ]
                    ),
                    new Column(
                        'image',
                        [
                            'type' => Column::TYPE_CHAR,
                            'size' => 37,
                            'after' => 'address'
                        ]
                    ),
                    new Column(
                        'images',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 1,
                            'after' => 'image'
                        ]
                    ),
                    new Column(
                        'is_delete',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 4,
                            'after' => 'images'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('FK_object_user', ['user_id'], null),
                    new Index('FK_object_organization', ['root_organization_id'], null),
                    new Index('FK_object_directory', ['directory_id'], null),
                    new Index('FK_object_client', ['client_id'], null),
                    new Index('FK_object_foreman', ['foreman_id'], null)
                ],
                'references' => [
                    new Reference(
                        'FK_object_client',
                        [
                            'referencedTable' => 'client',
                            'referencedSchema' => 'mvk_crm',
                            'columns' => ['client_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'SET NULL'
                        ]
                    ),
                    new Reference(
                        'FK_object_directory',
                        [
                            'referencedTable' => 'directory',
                            'referencedSchema' => 'mvk_crm',
                            'columns' => ['directory_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'SET NULL'
                        ]
                    ),
                    new Reference(
                        'FK_object_foreman',
                        [
                            'referencedTable' => 'foreman',
                            'referencedSchema' => 'mvk_crm',
                            'columns' => ['foreman_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'SET NULL'
                        ]
                    ),
                    new Reference(
                        'FK_object_organization',
                        [
                            'referencedTable' => 'organization',
                            'referencedSchema' => 'mvk_crm',
                            'columns' => ['root_organization_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'SET NULL'
                        ]
                    ),
                    new Reference(
                        'FK_object_user',
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
                    'AUTO_INCREMENT' => '1',
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
