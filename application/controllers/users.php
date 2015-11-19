<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

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
			array( 'db' => 'email',  'dt' => 1 ),
			array('db'        => 'creation_date',
					'dt'        => 2,
					'formatter' => function( $d, $row ) {
						return date( 'jS M y', strtotime($d));
					}
			),
			array( 'db' => 'id',
					'dt' => 3,
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
		echo json_encode( SSP::simple( $post, USER, "id", $columns ) );exit;
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
									'trim|required|valid_email|is_unique['.USER.'.email]',
									array(
										"required"=>"Please enter %s.",
										"valid_email"=>"Please enter valid %s.",
										"is_unique"=>"%s is already exists."
									)
			);

			$this->form_validation->set_rules('user_name', 'User Name', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('re_password', 'Password Confirmation', 'trim|required|matches[password]',
								array(
									"matches"=>"%s field does not match."
								)
			);
			
			if ($this->form_validation->run() !== false) {
				$data = array('name' => $post['user_name'],
								'email' => $post['email'],
								'password' => sha1(trim($post['password'])),
								'cust_id' => NULL
							  );
				
				$ret = $this->common_model->insertData(USER, $data);

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

			$original_email = $this->common_model->selectData(USER, 'email', $where);
			
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
			
			if (trim($post['password']) != "") {
				$this->form_validation->set_rules('password', 'Password', 'trim|required');
				$this->form_validation->set_rules('re_password', 'Password Confirmation', 'trim|required|matches[password]',
								array(
									"matches"=>"%s field does not match."
								)
				);
				$psFlas = true;
			}

			if ($this->form_validation->run() !== false) {
				$data = array('name' => $post['user_name'],
								'email' => $post['email'],
								'cust_id' => NULL
							);
				if($psFlas)
					$data['password'] = sha1(trim($post['password']));
				
				$ret = $this->common_model->updateData(USER, $data, $where);

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
		$data['user'] = $user = $this->common_model->selectData(USER, '*', $where);

		if (empty($user)) {
			redirect('users');
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}


	public function delete()
	{
		$post = $this->input->post();

		if ($post) {
			$ret = $this->common_model->deleteData(USER, array('id' => $post['id'] ));
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
