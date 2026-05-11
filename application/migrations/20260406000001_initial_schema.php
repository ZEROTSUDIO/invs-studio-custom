<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Initial_schema extends CI_Migration {

    public function up()
    {
        // Table: customers
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => FALSE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => TRUE,
                'default' => 'CURRENT_TIMESTAMP'
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('customers', TRUE);

        // Table: orders
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => FALSE,
                'auto_increment' => TRUE
            ],
            'customer_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'order_code' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'qty' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'product_type' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ],
            'design_file' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'status' => [
                'type' => "ENUM('waiting','scheduled','in_progress','done')",
                'default' => 'waiting',
            ],
            'est_duration' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'deadline' => [
                'type' => 'DATE',
                'null' => TRUE,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => TRUE,
                'default' => 'CURRENT_TIMESTAMP'
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => TRUE,
                'default' => 'CURRENT_TIMESTAMP'
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('orders', TRUE);

        // Table: order_items
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => FALSE,
                'auto_increment' => TRUE
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'size' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE,
            ],
            'qty' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('order_items', TRUE);

        // Table: production_schedule
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => FALSE,
                'auto_increment' => TRUE
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'queue_position' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'start_date' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'end_date' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'status' => [
                'type' => "ENUM('scheduled','in_progress','completed')",
                'default' => 'scheduled',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => TRUE,
                'default' => 'CURRENT_TIMESTAMP'
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => TRUE,
                'default' => 'CURRENT_TIMESTAMP'
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('production_schedule', TRUE);

        // Table: users
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => FALSE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => TRUE,
                'default' => 'CURRENT_TIMESTAMP'
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => TRUE,
                'default' => 'CURRENT_TIMESTAMP'
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('users', TRUE);

        // Add constraints manually since dbforge has limited support for them
        $this->db->query("ALTER TABLE `orders` ADD UNIQUE KEY `order_code` (`order_code`)");
        $this->db->query("ALTER TABLE `users` ADD UNIQUE KEY `email` (`email`)");
        
        // Foreign Keys
        $this->db->query("ALTER TABLE `orders` ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE");
        $this->db->query("ALTER TABLE `order_items` ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE");
        $this->db->query("ALTER TABLE `production_schedule` ADD CONSTRAINT `fk_schedule_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE");

        // Insert initial admin user if not exists
        $admin_exists = $this->db->get_where('users', ['email' => 'admin@gmail.com'])->num_rows();
        if (!$admin_exists) {
            $this->db->insert('users', [
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => md5('admin'), // Using md5 as per existing pattern
                'created_at' => '2026-04-03 03:19:00',
                'updated_at' => '2026-04-03 03:19:00'
            ]);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('production_schedule', TRUE);
        $this->dbforge->drop_table('order_items', TRUE);
        $this->dbforge->drop_table('orders', TRUE);
        $this->dbforge->drop_table('customers', TRUE);
        $this->dbforge->drop_table('users', TRUE);
    }
}
