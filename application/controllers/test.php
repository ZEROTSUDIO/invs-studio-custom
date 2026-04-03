<?php 
public function produk_aksi() 
    {
        $config['upload_path'] = './gambar/produk/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('produk_foto')) {
            $gambar = $this->upload->data();
            $nama = $this->input->post('produk_nama');
            $deskripsis = $this->input->post('produk_deskripsis');
            $foto = $gambar['file_name'];
            $link = $this->input->post('produk_link');
            $harga = $this->input->post('produk_harga');
            $data = array(
                'produk_nama' => $nama,
                'produk_foto' => $foto,
                'produk_deskripsis' => $deskripsis,
                'produk_link' => $link,
                'produk_harga' => $harga
            );
            $this->m_data->insert_data('produk', $data);
            redirect(base_url() . 'dashboard/produk');
        } else {
            $this->form_validation->set_message('produk_foto', $data['gambar_error'] = $this->upload->display_errors());
            $data['link'] = $this->m_data->get_data('link')->result();
            $data['active_page'] = 'produk';
            $this->load->view('dashboard/v_header', $data);
            $this->load->view('dashboard/v_produk_tambah', $data);
            $this->load->view('dashboard/v_footer');
        }
    }

    public function produk_edit($id)
    {
        $where = array(
            'produk_id' => $id
        );
        $data['produk'] = $this->m_data->edit_data('produk', $where)->result();
        $data['link'] = $this->m_data->get_data('link')->result();
        $data['active_page'] = 'produk';
        $this->load->view('dashboard/v_header', $data);
        $this->load->view('dashboard/v_produk_edit', $data);
        $this->load->view('dashboard/v_footer');
    }
    public function produk_update()
    {
        $id = $this->input->post('id');
        $nama = $this->input->post('produk_nama');
        $deskripsis = $this->input->post('produk_deskripsis');
        $link = $this->input->post('produk_link');
        $harga = $this->input->post('produk_harga');

        $where = array(
            'produk_id' => $id
        );
        $data = array(
            'produk_nama' => $nama,
            'produk_deskripsis' => $deskripsis,
            'produk_link' => $link,
            'produk_harga' => $harga
        );
        $this->m_data->update_data('produk', $data, $where);
        if (!empty($_FILES['produk_foto']['name'])) {
            $config['upload_path'] = './gambar/produk/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $new_filename = uniqid() . '.' . pathinfo($_FILES['produk_foto']['name'], PATHINFO_EXTENSION);
            $config['file_name'] = $new_filename;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('produk_foto')) {
                $gambar = $this->upload->data();
                $data = array(
                    'produk_foto' => $gambar['file_name']
                );
                $this->m_data->update_data('produk', $data, $where);
                redirect(base_url() . 'dashboard/produk');
            } else {
                $this->load->library('form_validation');
                $this->form_validation->set_message('produk_foto', $data['gambar_error'] = $this->upload->display_errors());

                $data['produk'] = $this->m_data->edit_data('produk', $where)->result();
                $data['active_page'] = 'produk';
                $this->load->view('dashboard/v_header', $data);
                $this->load->view('dashboard/v_produk_edit', $data);
                $this->load->view('dashboard/v_footer');
            }
        } else {
            redirect(base_url() . 'dashboard/produk');
        }
    }


    public function produk_hapus($id)
    {
        $where = array(
            'produk_id' => $id
        );
        $this->m_data->delete_data('produk', $where);
        redirect(base_url() . 'dashboard/produk');
    }
?>