<?php

use yii\db\Schema;
use yii\db\Migration;
use sammaye\audittrail\LoggableBehavior;

class m161004_132206_change_action_field extends Migration
{
    public function up()
    {

        $this->alterColumn('tbl_audit_trail', 'action', "ENUM('".LoggableBehavior::ACTION_CHANGE."', '".LoggableBehavior::ACTION_CREATE."', '".LoggableBehavior::ACTION_DELETE."', '".LoggableBehavior::ACTION_SET."') NOT NULL");

    }

    public function down()
    {
        $this->alterColumn('tbl_audit_trail', 'action', Schema::TYPE_STRING . ' NOT NULL');
    }
    
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    	$this->up();
    }
    
    public function safeDown()
    {
    	$this->down();
    }
}
