<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cellarea extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->user_session = $this->session->userdata('user_session');
	}

	public function index()
	{
		#pr($this->session->flashdata('flash_msg'));
		$data['view'] = "index";
		$this->load->view('content', $data);
	}

	public function ajax_list($limit=0)
	{
		$post = $this->input->post();

		$columns = array(
			array( 'db' => 'name', 'dt' => 0 ),
			array( 'db' => 'role',  'dt' => 1 ),
			array( 'db' => 'email',  'dt' => 2 ),
			array('db'        => 'creation_date',
					'dt'        => 3,
					'formatter' => function( $d, $row ) {
						return date( 'jS M y', strtotime($d));
					}
			),
			array( 'db' => 'id',
					'dt' => 4,
					'formatter' => function( $d, $row ) {
						$op = array();
						
						if (hasAccess("users","edit"))
							$op[] = '<a href="'.site_url('/users/edit/'.$d).'" class="fa fa-edit"></a> ';

						if (hasAccess("users","delete"))
							$op[] = '<a href="javascript:void(0);" onclick="delete_user('.$d.')" class="fa fa-trash-o"></a>';
						
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
			#pr($post);
			$error = array();
			$e_flag=0;

			$this->load->library('form_validation');
			$this->form_validation->set_rules('email', 'Email address', 
									'trim|required|valid_email|is_unique['.CELLAREA.'.email]',
									array(
										"required"=>"Please enter %s.",
										"valid_email"=>"Please enter valid %s.",
										"is_unique"=>"%s is already exists."
									)
			);

			$this->form_validation->set_rules('CELLAREA_name', 'User Name', 'trim|required');
			$this->form_validation->set_rules('role', 'Role', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('re_password', 'Password Confirmation', 'trim|required|matches[password]',
								array(
									"matches"=>"%s field does not match."
								)
			);
			if ($post['role'] == "d")
			{
				$this->form_validation->set_rules('cust_id', 'Customer', 'trim|required');
			}
			
			if ($this->form_validation->run() !== false) {
				$data = array('name' => $post['user_name'],
								'role' => $post['role'],
								'email' => $post['email'],
								'password' => sha1(trim($post['password'])),
								'cust_id' => NULL
							  );

				if ($post['role'] == "d")
				{
					$data["cust_id"] = $post["cust_id"];
				}
				
				$ret = $this->common_model->insertData(CELLAREA, $data);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'User added successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("users");
			}
			$data['error_msg'] = validation_errors();//$error;
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}

	public function edit($id)
	{
		if ($id == "" || $id <= 0) {
			redirect('users');
		}

		$where = 'id = '.$id;

		$post = $this->input->post();
		if ($post) {

			$original_email = $this->common_model->selectData(CELLAREA, 'email', $where);
			
			if($post['email'] != $original_email[0]->email) {
			   $is_unique =  '|is_unique['.USER.'.email]';
			} else {
			   $is_unique =  '';
			}

			$this->form_validation->set_rules('email', 'Email address', 
									'trim|required|valid_email'.$is_unique,
									array(
										"required"=>"Please enter %s.",
										"valid_email"=>"Please enter valid %s.",
										"is_unique"=>"%s is already exists."
									)
			);

			$this->form_validation->set_rules('user_name', 'User Name', 'trim|required');
			$this->form_validation->set_rules('role', 'Role', 'trim|required');
			
			if (trim($post['password']) != "") {
				$this->form_validation->set_rules('password', 'Password', 'trim|required');
				$this->form_validation->set_rules('re_password', 'Password Confirmation', 'trim|required|matches[password]',
								array(
									"matches"=>"%s field does not match."
								)
				);
				$psFlas = true;
			}

			if ($post['role'] == "d")
			{
				$this->form_validation->set_rules('cust_id', 'Customer', 'trim|required');
			}
	
			if ($this->form_validation->run() !== false) {
				$data = array('name' => $post['user_name'],
								'role' => $post['role'],
								'email' => $post['email'],
								'cust_id' => NULL
							);
				if($psFlas)
					$data['password'] = sha1(trim($post['password']));
				
				if ($post['role'] == "d")
				{
					$data["cust_id"] = $post["cust_id"];
				}

				$ret = $this->common_model->updateData(CELLAREA, $data, $where);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'User updated successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("users");
			}
			$data['error_msg'] = validation_errors();
		}
		$data['user'] = $user = $this->common_model->selectData(CELLAREA, '*', $where);

		if ($user[0]->role == "d"){
			$data['customer'] = $this->common_model->customerTitleById($user[0]->cust_id);
		}

		if (empty($user)) {
			redirect('users');
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}

	public function process_file()
	{
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
			foreach ($Reader as $Row)
			{
				if ($headerFlag) 
				{
					$headerFlag = false;
					continue;
				}
				
				$data = array();
				$data["lat"] = $Row[$post["field_lat"]];
				$data["lng"] = $Row[$post["field_lng"]];
				$data["company"] = $Row[$company_name];
				print_r($data);
				break;
			}

		}
	}

	public function fileupload()
	{
		$file_name = "";
		$error = "";
		$post = $this->input->post();
		if($_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'xls|csv|xlsx';

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
