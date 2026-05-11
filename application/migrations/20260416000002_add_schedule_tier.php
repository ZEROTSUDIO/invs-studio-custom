<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_schedule_tier extends CI_Migration {

    public function up()
    {
        $fields = array(
            'schedule_tier' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'normal',
                'null' => FALSE,
                'after' => 'order_id' // Placed conceptually near the order relation
            )
        );
        $this->dbforge->add_column('production_schedule', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('production_schedule', 'schedule_tier');
    }
}
