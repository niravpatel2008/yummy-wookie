<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
@set_time_limit(-1);
class Cellarea extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->user_session = $this->session->userdata('user_session');
		$this->filefields = array("town","cell_id","address","latitude","longitude");
		$this->companies = array("vodafone","airtel","idea","reliance","telenor","docomo","videocon");
	}

	public function index()
	{
		#pr($this->session->flashdata('flash_msg'));
		$data['view'] = "index";
		$data['filefields'] = $this->filefields;
		$data['companies'] = $this->companies;
		$this->load->view('content', $data);
	}

	public function ajax_list($limit=0)
	{
		$post = $this->input->post();

		$columns = array(
			array( 'db' => 'company', 'dt' => 0 ),
			array( 'db' => 'cell_id', 'dt' => 1 ),
			array( 'db' => 'town',  'dt' => 2 ),
			array( 'db' => 'address',  'dt' => 3 ),
			array( 'db' => 'latitude',  'dt' => 4 ),
			array( 'db' => 'longitude',  'dt' => 5 ),
			array('db'        => 'created_date',
					'dt'        => 6,
					'formatter' => function( $d, $row ) {
						return date( 'jS M y', strtotime($d));
					}
			),
			array('db'        => 'modified_date',
					'dt'        => 7,
					'formatter' => function( $d, $row ) {
						return date( 'jS M y', strtotime($d));
					}
			),
			array( 'db' => 'id',
					'dt' => 8,
					'formatter' => function( $d, $row ) {
						$op = array();
						$op[] = '<a href="javascript:addedit_cellinfo('.$d.');" class="fa fa-edit"></a> ';
						$op[] = '<a href="javascript:void(0);" onclick="delete_cellarea('.$d.')" class="fa fa-trash-o"></a>';
						
						return implode(" / ",$op);
					}
			),
		);
		echo json_encode( SSP::simple( $post, CELLAREA, "id", $columns ) );exit;
	}

	public function add()
	{
		$post = $this->input->post();
		if ($post) {
			
				$data = array();
				foreach ($this->filefields as $field)
					$data[$field] = $post[$field]; 

				$data["company"] = $post['company'];
				$data["modified_date"] = date('Y-m-d H:i:s');
				
				$ret = $this->common_model->insertData(CELLAREA, $data);

				if ($ret > 0) {
					echo "1";
				}else{
					echo "0";
				}
			exit;
		}
		$data['view'] = "add_edit";
		$data['filefields'] = $this->filefields;
		$data['companies'] = $this->companies;
		$this->load->view('ajax-content', $data);
	}

	public function edit($id)
	{
		if ($id == "" || $id <= 0) {
			echo "You can not edit.";exit;
		}

		$where = 'id = '.$id;

		$post = $this->input->post();
		if ($post) {
				$data = array();
				foreach ($this->filefields as $field)
					$data[$field] = $post[$field]; 

				$data["company"] = $post['company'];
				$data["modified_date"] = date('Y-m-d H:i:s');

				$ret = $this->common_model->updateData(CELLAREA, $data, $where);

				if ($ret > 0) {
					echo "1";
				}else{
					echo "0";
				}
				exit;
		}
		$data['cellarea'] = $this->common_model->selectData(CELLAREA, '*', $where);

		$data['view'] = "add_edit";
		$data['filefields'] = $this->filefields;
		$data['companies'] = $this->companies;
		$this->load->view('ajax-content', $data);
	}
	
	public function search()
	{
		$post = $this->input->post();
		if (!$post)exit;

		$data = array();
		$db = $this->common_model->db;
		$db->select("*");
		$db->from(CELLAREA);
		$db->limit(10);
		$query = $db->get();
		$data = $query->result_array();
		echo json_encode($data);exit;

		print_r($post);
	}

	public function process_file()
	{
		$this->load->helper('file');
		//ignore_user_abort(true);
		$post = $this->input->post();
		if ($post) {
			$filename = $post["filename"];
			$company_name = $post["company_name"];
			if ($filename == "") exit;
			if ($company_name == "") exit;

			require('./application/libraries/spreadsheet-reader/php-excel-reader/excel_reader2.php');
			require('./application/libraries/spreadsheet-reader/SpreadsheetReader.php');
			$Reader = new SpreadsheetReader( './uploads/'.$filename);
			$headerFlag = true;
			$post_fields = $post["field"];
			$company = $post["company_name"];
			$error = 0;
			$success = 0;
			$i= 0;
			$total = count($Reader);
			write_file('./uploads/'.$filename.".html", "0 / ". $total);
			foreach ($Reader as $Row)
			{
				$i++;
				if ($headerFlag) 
				{
					$headerFlag = false;
					continue;
				}
				
				$data = array();
				foreach ($this->filefields as $field)
					$data[$field] = $Row[$post_fields[$field]]; 

				$data["company"] = $company;
				$data["created_date"] = date('Y-m-d H:i:s');
				$data["modified_date"] = date('Y-m-d H:i:s');

				$ret = $this->common_model->insertData(CELLAREA, $data);
				if ($ret > 0) {
					$success++;
				}else{
					$error++;
				}

				if ($i%100 === 0)
				{
					write_file('./uploads/'.$filename.".html", "$i / ". $total);
				}
			}
			$op = array();
			$op["success"] = $success;
			$op["error"] = $error;
			echo json_encode($op);exit;
		}
	}

	public function fileupload()
	{
		$file_name = "";
		$error = "";
		$post = $this->input->post();
		
		if($_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'xls|csv|xlsx|application/octet-stream';

			$file_name_arr = explode('.',$_FILES['file']['name']);
			$file_name_arr = array_reverse($file_name_arr);
			$file_extension = $file_name_arr[0];
			$file_name = $config['file_name'] = "cell_".time().".".$file_extension;

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('file'))
			{
				$error = $this->upload->display_errors();
			}

			if ($error != "")
				echo "Error:".$error;
			else
			{
				/* Get uploaded file header uploaded File here */
				require('./application/libraries/spreadsheet-reader/php-excel-reader/excel_reader2.php');
				require('./application/libraries/spreadsheet-reader/SpreadsheetReader.php');
				$Reader = new SpreadsheetReader( './uploads/'.$file_name);
				foreach ($Reader as $Row)
				{
					$header = $Row;
					break;
				}
				$header = array_filter($header);
				$op["filename"] = $_FILES['file']['name'];
				$op["filename_org"] = $file_name;
				$op["header"] = $header;
				echo json_encode($op,JSON_FORCE_OBJECT);
			}
			exit;
		}else
		{
			echo "Error: File not uploaded to server.";
		}
	}
	
	public function deleteByCompany()
	{
		$post = $this->input->post();

		if ($post) {
			$ret = $this->common_model->deleteData(CELLAREA, array('company' => $post['company'] ));
			if ($ret > 0) {
				echo "success";
				#echo success_msg_box('User deleted successfully.');;
			}else{
				echo "error";
				#echo error_msg_box('An error occurred while processing.');
			}
		}
	}

	public function delete()
	{
		$post = $this->input->post();

		if ($post) {
			$ret = $this->common_model->deleteData(CELLAREA, array('id' => $post['id'] ));
			if ($ret > 0) {
				echo "success";
				#echo success_msg_box('User deleted successfully.');;
			}else{
				echo "error";
				#echo error_msg_box('An error occurred while processing.');
			}
		}
	}
}
