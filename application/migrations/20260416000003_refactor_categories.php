<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Refactor_categories extends CI_Migration {

    public function up()
    {
        // 1. Create categories table
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'base_duration' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ),
            'created_at timestamp DEFAULT CURRENT_TIMESTAMP',
            'updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('categories');

        // 2. Seed categories
        $categories = array(
            array('name' => 'Kaos', 'base_duration' => 10),
            array('name' => 'Crop Top', 'base_duration' => 12),
            array('name' => 'Hoodie', 'base_duration' => 15),
            array('name' => 'Topi', 'base_duration' => 8),
            array('name' => 'Custom (model cutsom)', 'base_duration' => 20)
        );
        $this->db->insert_batch('categories', $categories);

        // Get the ID of the "Custom" category
        $custom_category = $this->db->get_where('categories', array('name' => 'Custom (model cutsom)'))->row();
        $custom_id = $custom_category ? $custom_category->id : 1;

        // 3. Add category_id to orders table
        $fields = array(
            'category_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'after' => 'customer_id'
            )
        );
        $this->dbforge->add_column('orders', $fields);

        // 4. Migrate existing data: Point all existing orders to "Custom"
        $this->db->update('orders', array('category_id' => $custom_id));

        // 5. Drop old product_type column
        $this->dbforge->drop_column('orders', 'product_type');
        
        // Add foreign key if possible (optional but good practice)
        // Note: CI3 dbforge doesn't support add_foreign_key directly in some setups, 
        // using raw query for safety if needed, but let's stick to columns for now.
    }

    public function down()
    {
        // Add product_type back
        $fields = array(
            'product_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
                'after' => 'category_id'
            )
        );
        $this->dbforge->add_column('orders', $fields);
        
        // Remove category_id
        $this->dbforge->drop_column('orders', 'category_id');
        
        // Drop categories table
        $this->dbforge->drop_table('categories');
    }
}
