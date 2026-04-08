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

    function save_order($customer_id, $order, $items)
    {
        $this->db->trans_start();

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

    function create_customer($data)
    {
        $this->db->insert('customers', $data);
        return $this->db->insert_id();
    }

    function get_customer_by_phone($phone)
    {
        $this->db->where('phone', $phone);
        return $this->db->get('customers')->row();
    }

    function search_customers($query)
    {
        $this->db->select('id, name, phone');
        $this->db->group_start();
        $this->db->like('name', $query);
        $this->db->or_like('phone', $query);
        $this->db->group_end();
        $this->db->limit(8);
        return $this->db->get('customers')->result();
    }

    function get_all_customers()
    {
        $this->db->select('c.id, c.name, c.phone, COUNT(o.id) as total_orders');
        $this->db->from('customers c');
        $this->db->join('orders o', 'o.customer_id = c.id', 'left');
        $this->db->group_by('c.id');
        $this->db->order_by('c.name', 'ASC');
        return $this->db->get()->result();
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
