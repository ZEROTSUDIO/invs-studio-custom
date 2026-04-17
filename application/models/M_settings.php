<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_settings extends CI_Model
{
    private $_table = 'settings';

    /**
     * Get a single setting value by its key.
     * Returns the default if not found.
     */
    public function get($key, $default = null)
    {
        $res = $this->db->where('key', $key)->get($this->_table)->row();
        return $res ? $res->value : $default;
    }

    /**
     * Get all settings grouped for the UI.
     */
    public function get_all()
    {
        return $this->db->get($this->_table)->result();
    }

    /**
     * Update multiple settings from a POST array.
     */
    public function update_batch($data)
    {
        foreach ($data as $key => $value) {
            $this->db->where('key', $key)->update($this->_table, ['value' => $value]);
        }
    }
}
