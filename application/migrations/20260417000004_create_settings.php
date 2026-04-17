<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_settings extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'key' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => TRUE,
            ),
            'value' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'label' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'type' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => 'text',
            ),
            'group' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'general',
            )
        ));
        $this->dbforge->add_key('key', TRUE);
        $this->dbforge->create_table('settings');

        // Seed initial data
        $data = array(
            array(
                'key' => 'urgency_slack_buffer',
                'value' => '0.25',
                'label' => 'Urgency Slack Buffer',
                'description' => 'The safety margin (decimal percentage) required before an order is considered "Safe." For example, 0.25 means the system wants a 25% safety gap relative to the job duration.',
                'type' => 'number',
                'group' => 'algorithm'
            ),
            array(
                'key' => 'quick_insert_threshold',
                'value' => '480',
                'label' => 'Quick-Insert Threshold (Minutes)',
                'description' => 'Jobs shorter than this limit can "cut in line" and finish today if there is remaining time. 480 mins = 1 full shift.',
                'type' => 'number',
                'group' => 'algorithm'
            ),
            array(
                'key' => 'quick_insert_deadline_days',
                'value' => '2',
                'label' => 'Quick-Insert Deadline Window',
                'description' => 'Only jobs due within this many days are eligible for the Quick-Insert priority bypass.',
                'type' => 'number',
                'group' => 'algorithm'
            ),
            array(
                'key' => 'business_hour_start',
                'value' => '08:30',
                'label' => 'Business Start Time',
                'description' => 'When your production day begins (e.g., 08:30). Tasks will never be scheduled to start before this.',
                'type' => 'time',
                'group' => 'business'
            ),
            array(
                'key' => 'business_hour_end',
                'value' => '17:00',
                'label' => 'Business End Time',
                'description' => 'When your production day ends (e.g., 17:00). Tasks exceeding this will roll over to the next business day.',
                'type' => 'time',
                'group' => 'business'
            )
        );
        $this->db->insert_batch('settings', $data);
    }

    public function down() {
        $this->dbforge->drop_table('settings');
    }
}
