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

    function get_order_by_id($id)
    {
        $this->db->select('orders.*, customers.name as customer_name, customers.phone as customer_phone');
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->where('orders.id', $id);
        return $this->db->get()->row();
    }

    function get_order_items($order_id)
    {
        $this->db->where('order_id', $order_id);
        return $this->db->get('order_items')->result();
    }

    function update_order_complete($order_id, $order_data, $items)
    {
        $this->db->trans_start();
        
        // Update main order
        $this->db->where('id', $order_id);
        $this->db->update('orders', $order_data);
        
        // Replace order items
        $this->db->where('order_id', $order_id);
        $this->db->delete('order_items');
        
        if (!empty($items)) {
            foreach ($items as $item) {
                $item['order_id'] = $order_id;
                $this->db->insert('order_items', $item);
            }
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // ─── Dashboard home data ───────────────────────────────────────────────

    /**
     * Returns KPI aggregate stats for the dashboard landing page.
     */
    function get_dashboard_stats()
    {
        $total   = $this->db->count_all('orders');
        $new_today = $this->db
            ->where("DATE(created_at) = '" . date('Y-m-d') . "'", NULL, FALSE)
            ->count_all_results('orders');
        $in_progress = $this->db
            ->where('status', 'in_progress')
            ->count_all_results('orders');
        $done_month = $this->db
            ->where('status', 'done')
            ->where('MONTH(created_at) = MONTH(NOW())', null, false)
            ->where('YEAR(created_at) = YEAR(NOW())', null, false)
            ->count_all_results('orders');
        $waiting = $this->db
            ->where('status', 'waiting')
            ->count_all_results('orders');
        $scheduled = $this->db
            ->where('status', 'scheduled')
            ->count_all_results('orders');

        return (object)[
            'total_orders'  => $total,
            'new_today'     => $new_today,
            'in_progress'   => $in_progress,
            'done_month'    => $done_month,
            'waiting'       => $waiting,
            'scheduled'     => $scheduled,
        ];
    }

    /**
     * Returns today's production schedule items (in_progress + scheduled starting today).
     * Used for the "Today's Work Order" to-do list.
     */
    function get_todays_work_orders()
    {
        $today = date('Y-m-d');
        $this->db->select('ps.id as schedule_id, ps.order_id, ps.queue_position, ps.start_date, ps.end_date, ps.status as schedule_status, o.order_code, o.product_type, o.qty, o.est_duration, o.deadline, o.status as order_status, c.name as customer_name');
        $this->db->from('production_schedule ps');
        $this->db->join('orders o', 'o.id = ps.order_id');
        $this->db->join('customers c', 'c.id = o.customer_id');
        $this->db->group_start();
            $this->db->where('ps.status', 'in_progress');
            $this->db->or_where("DATE(ps.start_date) = '" . $today . "'", NULL, FALSE);
        $this->db->group_end();
        $this->db->order_by('ps.queue_position', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Returns orders with deadline within the next $days days (exclusive of done/canceled).
     */
    function get_deadline_alerts($days = 1)
    {
        $cutoff = date('Y-m-d', strtotime("+{$days} days"));
        $this->db->select('o.id, o.order_code, o.product_type, o.qty, o.deadline, o.status, c.name as customer_name');
        $this->db->from('orders o');
        $this->db->join('customers c', 'c.id = o.customer_id');
        $this->db->where('o.deadline <=', $cutoff);
        $this->db->where_not_in('o.status', ['done', 'canceled']);
        $this->db->order_by('o.deadline', 'ASC');
        return $this->db->get()->result();
    }
}
