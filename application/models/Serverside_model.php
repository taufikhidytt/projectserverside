<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Serverside_model extends CI_Model
{
    var $table = 'karyawan';
    var $column_order = array('id', 'nama_depan', 'nama_belakang', 'alamat', 'no_hp');
    var $order = array('id', 'nama_depan', 'nama_belakang', 'alamat', 'no_hp');

    public function getData()
    {
        $this->get_data_query();

        if($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();

        return $query->result();
    }

    public function count_filtered_data()
    {
        $this->get_data_query();

        $query = $this->db->get();

        return $query->num_rows();
    }

    public function count_all_data()
    {
        $this->db->from($this->table);

        return $this->db->count_all_results();
    }

    private function get_data_query()
    {
        $this->db->from($this->table);

        // untuk mencari data
        if(isset($_POST['search']['value'])){
            $this->db->like('nama_depan', $_POST['search']['value']);
            $this->db->or_like('nama_belakang', $_POST['search']['value']);
            $this->db->or_like('alamat', $_POST['search']['value']);
            $this->db->or_like('no_hp', $_POST['search']['value']);
        }

        // untuk oreder data
        if(isset($_POST['order'])){
            $this->db->order_by($this->order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else{
            $this->db->order_by('id', 'DESC');
        }
    }

    public function add($data)
    {
        $this->db->insert('karyawan', $data);

        return $this->db->affected_rows();
    }

    public function getDataById($id)
    {
        // return $this->db->get_where('karyawan', ['id' => $id])->row();
        $this->db->where('id', $id);
        return $this->db->get('karyawan')->row();
    }

    public function update($data)
    {
        $params = [
            "nama_depan" => $data['nama_depan'],
            "nama_belakang" => $data['nama_belakang'],
            "alamat" => $data['alamat'],
            "no_hp" => $data['no_hp'],
        ];

        $where = [
            "id" => $data['id']
        ];

        $this->db->update('karyawan', $params, $where);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        $this->db->delete('karyawan', ['id' => $id]);
        return $this->db->affected_rows();
        
        
    }
}
?>