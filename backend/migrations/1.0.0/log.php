<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class LogMigration_100
 */
class LogMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('log', [
                'columns' => [
                    new Column(
                        'type',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 4,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'name',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 50,
                            'after' => 'type'
                        ]
                    ),
                    new Column(
                        'message',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'name'
                        ]
                    ),
                    new Column(
                        'time',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'message'
                        ]
                    ),
                    new Column(
                        'label',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 8,
                            'after' => 'time'
                        ]
                    )
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
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
