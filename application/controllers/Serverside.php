<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Serverside extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Serverside_model', 'serverside');
	}

	public function index()
	{
		$this->load->view('serverside');
	}

	public function getData()
	{
		$result = $this->serverside->getData();
		$data = [];
		$no = $_POST['start'];

		foreach($result as $result){
			$row = array();
			$row[] = ++$no;
			$row[] = $result->nama_depan;
			$row[] = $result->nama_belakang;
			$row[] = $result->alamat;
			$row[] = $result->no_hp;
			$row[] = '
			<a href="#" class="btn btn-primary" onclick="byid('."'".$result->id."','edit'".')">Edit</a> |
			<a href="#" class="btn btn-danger" onclick="byid('."'".$result->id."','hapus'".')">Hapus</a>
			';
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->serverside->count_all_data(),
			"recordsFiltered" => $this->serverside->count_filtered_data(),
			"data" => $data
		);

		$this->output->set_content_type('aplication/json')->set_output(json_encode($output));
	}

	public function add()
	{
		$this->_validation();

		$data = [
			'nama_depan' => htmlspecialchars($this->input->post('nama_depan')),
			'nama_belakang' => htmlspecialchars($this->input->post('nama_belakang')),
			'alamat' => htmlspecialchars($this->input->post('alamat')),
			'no_hp' => htmlspecialchars($this->input->post('no_hp')),
		];

		if($this->serverside->add($data) > 0){
			$massage['status'] = 'success';
		}else{
			$massage['status'] = 'failed';
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($massage));
	}

	public function byid($id)
	{
		$data = $this->serverside->getDataById($id);
		
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function update()
	{
		$this->_validation();
		$data = $this->input->post(null, true);
		
		if($this->serverside->update($data) >= 0){
			$massage['status'] = 'success';
		}else{
			$massage['status'] = 'failed';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($massage));
	}

	public function delete($id)
	{
		if($this->serverside->delete($id) > 0){
			$massage['status'] = 'success';
		}else{
			$massage['status'] = 'failed';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($massage));
	}

	private function _validation()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = true;

		if($this->input->post('nama_depan') == ''){
			$data['inputerror'][] = 'nama_depan';
			$data['error_string'][] = 'Nama Depan Wajib Di Isi';
			$data['status'] = false;
		}

		if($this->input->post('nama_belakang') == ''){
			$data['inputerror'][] = 'nama_belakang';
			$data['error_string'][] = 'Nama Belakang Wajib Di Isi';
			$data['status'] = false;
		}

		if($this->input->post('alamat') == ''){
			$data['inputerror'][] = 'alamat';
			$data['error_string'][] = 'Alamat Wajib Di Isi';
		}

		if($this->input->post('no_hp') == ''){
			$data['inputerror'][] = 'no_hp';
			$data['error_string'][] = 'No Handphone Wajib Di Isi';
			$data['status'] = false;
		}

		if($data['status'] === false){
			echo json_encode($data);
			exit();
		}
	}

}
