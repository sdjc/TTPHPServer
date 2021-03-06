<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."core/TT_Controller.php");

class Depart extends TT_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('depart_model');
		$this->load->model('user_model');
	}

	public function index()
	{
		$this->load->view('base/header');
		$this->load->view('base/depart');
		$this->load->view('base/footer');
	}

	public function all()
	{
		$perpage = 10000;
		$departs = $this->depart_model->getList(array('status'=>0), '*', 0, $perpage);
		$data = array();
		foreach ($departs as $key => $value) {
			$data[$value['id']] = $value;
		}
		foreach ($departs as $key => $value) {
			if($value['pid']){
				$departs[$key]['pid_value'] = $data[$value['pid']]['title'];
			}else{
				$departs[$key]['pid_value'] = '当前部门是父部门';
			}
		}
		$result = array(
			'departs'=>$departs,
		);
		echo json_encode($result);
	}

	public function del()
	{
		$id = $this->input->post('id');
		$departCount = $this->depart_model->getCount(array('status'=>0,'pid'=>$id));
		if($departCount){
			echo "has departs";
			exit();
		}
		$userCount = $this->user_model->getCount(array('status'=>0,'departId'=>$id));
		if($userCount){
			echo "has users";
			exit();
		}
		$result = $this->depart_model->update(array('status'=>1), $id);
		if($result){
			echo 'success';
		}
	}

	public function add()
	{
		$params = array(
			'title'=>$this->input->post('title'),
			'desc'=>$this->input->post('desc'),
			'pid'=>$this->input->post('pid'),
			'created'=>time(),
			'updated'=>time()
		);
		$result = $this->depart_model->insert($params);
		if($result){
			echo 'success';
		}
	}

	public function edit()
	{
		$params = array(
			'title'=>$this->input->post('title'),
			'desc'=>$this->input->post('desc'),
			'pid'=>$this->input->post('pid'),
			'updated'=>time()
		);
		$id = $this->input->post('id');
		$result = $this->depart_model->update($params,$id);
		if($result){
			echo 'success';
		}
	}

	public function get()
	{
		$id = $this->input->post('id');
		$result = $this->depart_model->getOne(array('id'=>$id));
		if($result){
			echo json_encode($result);
		}
	}
}