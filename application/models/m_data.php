<?php
class M_data extends CI_Model
{
    //untuk update data ganti password
    function update_data($table, $data, $where)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
    }
    function get_data($table)
    {
        return $this->db->get($table);
    }
    function insert_data($table, $data)
    {
        return $this->db->insert($table, $data);
    }
    function edit_data($table, $where)
    {
        return $this->db->get_where($table, $where);
    }
    function delete_data($table, $where)
    {
        return $this->db->delete($table, $where);
    }

    function save_order($customer, $order, $items)
    {
        $this->db->trans_start();

        // 1. Find or create customer by phone number
        $existing = $this->db->get_where('customers', array('phone' => $customer['phone']))->row();
        if ($existing) {
            $customer_id = $existing->id;
        } else {
            $this->db->insert('customers', $customer);
            $customer_id = $this->db->insert_id();
        }

        // 2. Insert order (linked to customer)
        $order['customer_id'] = $customer_id;
        $this->db->insert('orders', $order);
        $order_id = $this->db->insert_id();

        // 3. Insert each order item (linked to order)
        foreach ($items as $item) {
            $item['order_id'] = $order_id;
            $this->db->insert('order_items', $item);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    function get_all_orders()
    {
        $this->db->select('orders.*, customers.name as customer_name');
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->order_by('orders.id', 'DESC');
        return $this->db->get();
    }
}
