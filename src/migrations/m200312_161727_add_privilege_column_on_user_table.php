<?php

use yii\db\Migration;

/**
 * Class m200312_161727_add_privilege_column_on_user_table
 */
class m200312_161727_add_privilege_column_on_user_table extends Migration
{
    protected $table = 'user';

    /**
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'privilege', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown()
    {
        $this->dropColumn($this->table, 'privilege');
    }
}
