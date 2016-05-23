<?php  
defined('BASEPATH') OR exit('No direct script access allowed');
	class Verify extends CI_Controller  
	{  
		function __construct() 
		{
			parent::__construct();
				$this->load->library('form_validation');
				$this->load->helper('form','url');
				$this->load->library('session');
				$this->load->library('pagination');
				$this->load->library('form_validation');
				$this->load->model('select'); 
		}   
		public function index() 
		{
			$this->load->view('verify/login_view');  
			$this->load->view('verify/footer');
		}
				
		public Function adminlogin_passavail()
		{	
			$this->form_validation->set_rules('username', 'UserName', 'required');  
			if ($this->form_validation->run() == TRUE) 
			{
				$data = array(
				'table_name' => 'ra_admin_login',
				"username" =>$this->input->post('username'),
				"password" => md5($this->input->post('password')),
				);
				$username=$data['username'];
				$password=$data['password'];
				$this->session->set_userdata($data);
				$data['username'] = $this->session->userdata('username');
				$session_username=$data['username'];
				$this->db->select('*');
				$this->db->where('username',$username);
				$query = $this->db->get('ra_admin_login');
				if ($query->num_rows() > 0) 
				{
					foreach ($query->result() as $row)
					{
						$admin_password = $row->password;
						$firstname = $row->firstname;
						$admin_id = $row->id;
					}
						$this->session->set_userdata("session_admin_id",$admin_id);
						$session_admin_id=$this->session->userdata("session_admin_id");
						$this->session->set_userdata("session_firstname",$firstname);
						$session_firstname=$this->session->userdata("session_firstname");
					if($admin_password!="") 
					{
						if($password==$admin_password)
						{
							$this->load->view('verify/loginsuccess_view');
						}
						else
						{
							echo "Invalid password";
						}
					}
					else	
					{
						$this->load->view('verify/emptypass_login_view');
						$this->load->view('verify/footer');
					}
				} 
				else 
				{
					echo "Not valid user";
				}
			}
		}
		public Function admin_pass_insert()
		{
			$data = array(
			'table_name' => 'ra_admin_login',
			"username" =>$this->input->post('username'),
			"password" => md5($this->input->post('password')),
			);
			$this->load->model('select'); 
			$this->select->update($data);
			$this->load->view('verify/loginsuccess_view');
		}
		public Function forgot_password()
		{
			$this->load->view('verify/forgot_view');
			$this->load->view('verify/footer');
		}	
		public Function reset_password()
		{
			$username =$this->input->post('email');
			$this->db->where('username',$username);
			$query = $this->db->get('ra_admin_login');
			foreach ($query->result() as $row)
			{
				$admin_password= $row->password;
			}
			if($admin_password!="")
			{
				$data = array(
				'table_name' => 'ra_admin_login', 
				"password" => md5($this->input->post('password')),
				);
				$this->load->model('select');
				$this->select->forgot_pass($data,$username);
				echo "updated successfully";
			}
			else
			{
				echo "Please you  can login using username";
			}
		}
		public function VerifyCustomer()
		{
			$admin_id=$this->session->userdata("session_admin_id");
			$this->load->model('select');
			$config = array();
			$config["base_url"] = base_url()."index.php/verify/VerifyCustomer";
			$config['total_rows'] =$this->select->record_count(); 
			$config['cur_tag_open'] = '&nbsp;<a class="current">';
			$config['cur_tag_close'] = '</a>';
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Previous';
			$config['per_page'] = 5;
			$config["uri_segment"] = 3;
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
			$data["myuser"]=$this->select->select_myusers($admin_id,$config["per_page"], $page); 
			$data["allData"] = $this->select->verifycustomer($config["per_page"], $page);
			$data["links"] = $this->pagination->create_links();
			$data['result']=$this->List_Of_Admins_Core();
			$this->load->view('verify/admin_assign_view',$data);
			$this->load->view('verify/footer'); 
		}
		public function List_Of_Admins_Core()
		{
			$admin = array(); 
			$this->db->select('firstname,id');
			$query = $this->db->get('ra_admin_login');
			foreach($query->result_array() as $row)
			{    
				$admin[] = $row;
			}
				return $admin;			
		}
		public function AssignCustomer()
		{
			$session_id=$this->session->userdata("session_admin_id");
			$admin_id= $this->input->post('admin_id'); 
			$chkBoxArray= $this->input->post('chkBoxArray'); 
			$current_date=date("Y-m-d h:i:sa");
			$this->load->model('select'); 
			$this->select->assign_customer_confirm($admin_id,$chkBoxArray,$current_date,$session_id); 
			$this->select->assign_customer_update($chkBoxArray); 
			$data['customer']=$this->select->select_value_customer($chkBoxArray); 
			$this->load->view('verify/admin_result_view',$data); 
		}
		public function ReassignCustomer()
		{
			$admin_id= $this->input->post('admin_id'); 
			$customer_id= $this->input->post('customer_id'); 
			$this->load->model('select');  
			$this->select->Reassign_customer_update($customer_id,$admin_id); 
		}
		public function ViewCustomer($customer_id)
		{
			$this->load->model('select'); 
			$data['view_customer']=$this->select->select_view_mycustomers($customer_id); 
			$this->load->view('verify/customer_result_view',$data);
			$this->load->view('verify/footer');
		}
						
		public function ActivateCustomer()
		{
			$customer_id= $this->input->post('customer_id'); 
			$comment= $this->input->post('comment'); 
			$this->load->model('select'); 
			$this->select->activate_customer($customer_id,$comment);
			$this->VerifyCustomer();
		}
		public function DeactivateCustomer()
		{
			$customer_id= $this->input->post('customer_id');
			$comment= $this->input->post('comment');					
			$this->load->model('select'); 
			$this->select->deactivate_customer($customer_id,$comment);
			$this->VerifyCustomer();
		}
		public function verify_comments()
		{
			$customer_id=$this->input->post('id');
			$comments=$this->input->post('comments');
			$this->load->model('select'); 
			$this->select->comments_Update($comments,$customer_id); 
		} 
		public function save_customer()
		{
			$customer_type =$this->input->post('customer_type');
			$customer_id =$this->input->post('customer_id');
			$comments= $this->input->post('comments'); 
			$this->load->model('select'); 
			$this->select->save_customer($customer_type,$customer_id,$comments);
		}
		public function VerifyLandlord()
		{
			$admin_id=$this->session->userdata("session_admin_id");	
			$this->load->database();    
			$this->load->model('select');  
			$config = array();
			$config["base_url"] = base_url()."index.php/verify/VerifyLandlord";
			$config['total_rows'] = $this->select->landlord_row();  
			$config['cur_tag_open'] = '&nbsp;<a class="current">';
			$config['cur_tag_close'] = '</a>';
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Previous';
			$config['per_page'] = 5;
			$config["uri_segment"] = 3;
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
			$data["land_myuser"]=$this->select->select_land_myusers($admin_id,$config["per_page"], $page); 
			$data["land"] = $this->select->select_landlord($config["per_page"], $page);
			$data['pages'] = $this->pagination->create_links();
			$data['result']=$this->List_Of_Admins_Core();
			$this->load->view('verify/landlord_view',$data);  
			$this->load->view('verify/footer'); 
		}
		public function assign_landlord()
		{	
			$session_id=$this->session->userdata("session_admin_id");
			$admin_id= $this->input->post('admin_id'); 
			$chkBoxArray= $this->input->post('chkBoxArray'); 
			$current_date=date("Y-m-d h:i:sa");
			$this->load->model('select'); 
			$this->select->assign_landlord_confirm($admin_id,$chkBoxArray,$current_date,$session_id); 
			$this->select->assign_landlord_update($chkBoxArray); 
			$data['landlord']=$this->select->select_value_landlord($chkBoxArray) ;
			$this->load->view('verify/landlord_result_view',$data);
		}
		public function ViewLandlords($landlord_id)
		{
			$this->load->model('select'); 
			$data['view_landlord']=$this->select->select_view_mylandlords($landlord_id); 
			$this->load->view('verify/landlord_view_result',$data);
			$this->load->view('verify/footer');
		}
		public function verify_landlord_comments()
		{
			$landlord_id=$this->input->post('id');
			$comments=$this->input->post('comments');
			$this->load->model('select'); 
			$this->select->landlord_comments_Update($comments,$landlord_id); 
		} 
		public function verify_landlord_update()
		{
			$landlord_id=$this->input->post('landlord_id');
			$this->load->model('select'); 
			$this->select->verify_landlord_update($landlord_id); 
		} 
		public function SaveLandlord()
		{ 
			$landlord_id= $this->input->post('landlord_id');
			$land_value= $this->input->post('land_value');
			$comments= $this->input->post('comments'); 				
			$this->load->model('select'); 
			$this->select->assign_landlord_save($landlord_id,$comments,$land_value);
			
		}
		public function ReassignLandlords()
		{
			$admin_id= $this->input->post('admin_id'); 
			$landlord_id= $this->input->post('landlord_id'); 
			$this->load->model('select');  
			$this->select->Reassign_landlord_update($landlord_id,$admin_id); 
		}
		public function VerifyAccount()
		{
			$this->load->database();  
			$this->load->model('select');
			$admin_id=$this->session->userdata("session_admin_id");								  
			$data['after_save']=$this->select->select_account_aftersave(); 
			$config = array();
			$config["base_url"] = base_url()."index.php/verify/VerifyAccount";
			$config['total_rows'] = $this->select->account_row(); 
			$config['cur_tag_open'] = '&nbsp;<a class="current">';
			$config['cur_tag_close'] = '</a>';
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Previous';
			$config['per_page'] = 5;
			$config["uri_segment"] = 3;
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
			$data['acc_myuser']=$this->select->select_acc_myusers($admin_id,$config["per_page"], $page); 
			$data['acc']=$this->select->select_account($config["per_page"], $page);
			$data["links"] = $this->pagination->create_links();
			$data['result']=$this->List_Of_Admins_Core();
			$this->load->view('verify/account_view',$data);  
			$this->load->view('verify/footer'); 
		}		
		public function assign_account()
		{
			$session_id=$this->session->userdata("session_admin_id");
			$admin_id= $this->input->post('admin_id'); 
			$chkBoxArray= $this->input->post('chkBoxArray'); 
			$current_date=date("Y-m-d h:i:sa");
			$this->load->model('select'); 
			$this->select->customer_account_confirm($admin_id,$chkBoxArray,$current_date,$session_id); 
			$this->select->customer_account_update($chkBoxArray);
			$data['account']=$this->select->select_value_customer_account($chkBoxArray); 
			$this->load->view('verify/customer_account_result_view',$data);
		}	            
		public function verify_accounts_comments()
		{
			$customer_id=$this->input->post('id');
			$amount=$this->input->post('amount');
			$this->load->model('select'); 
			$this->select->account_comments_Update($amount,$customer_id); 
		} 
		public function save_customer_account()
		{
			$amount =$this->input->post('amount');
			$holder_id =$this->input->post('customer_id'); 
			$this->load->model('select'); 
			$this->select->customer_account_save($holder_id,$amount);
			$this->VerifyAccount(); 
		}
		public function view_customer_account($customer_id)
		{
			$this->load->model('select'); 
			$data['view_myuser']=$this->select->select_view_myusers($customer_id); 
			$this->load->view('verify/account_result_view',$data);
			$this->load->view('verify/footer'); 
		}
		public function ReassignAccounts()
		{
			$admin_id= $this->input->post('admin_id'); 
			$customer_id= $this->input->post('customer_id'); 
			$this->load->model('select'); 
			$this->select->Reassign_Account_update($customer_id,$admin_id); 
		}
		public function admin_assign()
		{
			$this->load->view('verify/admin_assign_view');
			$this->load->view('verify/footer');
		}
		public function	SelectCustomers()
		{
			$this->load->database();  
			$this->load->model('select'); 
			$data['value']=$this->input->post('selectedValue');
			if($data['value']=="one")
			{
				$admin_id=$this->session->userdata("session_admin_id");
				$config = array();
				$config["base_url"] = base_url()."index.php/verify/SelectCustomers";
				$config['total_rows'] = $this->select->assign_customer_row();    
				$config['cur_tag_open'] = '&nbsp;<a class="current">';
				$config['cur_tag_close'] = '</a>';
				$config['next_link'] = 'Next';
				$config['prev_link'] = 'Previous';
				$config['per_page'] = 5;
				$config["uri_segment"] = 3;
				$this->pagination->initialize($config);
				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
				$data["myuser"]=$this->select->select_myusers($admin_id,$config["per_page"], $page); 
				$this->load->view('verify/selectcustomers_result_view',$data);
			}
			else if($data['value']=="two")
			{
				$data['activate_customer']=$this->select->activate_myusers();  
				$this->load->view('verify/selectcustomers_result_view',$data);
			}
			else if($data['value']=="three")
			{
				$data['deactivate_customer']=$this->select->deactivate_myusers();  
				$this->load->view('verify/selectcustomers_result_view',$data);
			}
			else
			{
				echo "error";
			}
		}
		public function	SelectLandlords()
		{
			$this->load->database();   
			$this->load->model('select'); 
			$data['value']=$this->input->post('selectedValue');
			if($data['value']=="one")
			{
				$admin_id=$this->session->userdata("session_admin_id");
				$data['land_myuser']=$this->select->select_land_myusers($admin_id);  
				$this->load->view('verify/selectlandlords_result_view',$data);
			}
			else if($data['value']=="two")
			{
				$data['save_landlords']=$this->select->verified_landlords();  
				$this->load->view('verify/selectlandlords_result_view',$data);
			}
		}
		public function	SelectAccounts()
		{
			$this->load->database();   
			$this->load->model('select'); 
			$data['value']=$this->input->post('selectedValue');
			if($data['value']=="one")
			{
				$admin_id=$this->session->userdata("session_admin_id");
				$data['acc_myuser']=$this->select->select_acc_myusers($admin_id);  
				$this->load->view('verify/selectaccounts_result_view',$data);
			}
			else if($data['value']=="two")
			{
				$data['saveaccounts']=$this->select->select_account_aftersave();  
				$this->load->view('verify/selectaccounts_result_view',$data);
			}  
		}
		public function SearchContract()
		{
			$this->load->view('verify/searchcontract_view');
		}
		public function search_contract()
		{
			$data['startdate'] =$this->input->post('startdate');
			$data['enddate'] = $this->input->post('enddate');
			$data['contract_type'] = $this->input->post('contract_type');
			$data['firstname'] =$this->input->post('firstname');
			$data['lastname'] = $this->input->post('lastname');
			$data['user_status'] = $this->input->post('user_status');
			
			if($data['startdate']!="" && $data['enddate']!="" && $data['contract_type']!="")
			{
				$this->load->model('select');
				$data['search_contract']=$this->select->search_contract($data);
				$this->load->view('verify/search_by_contract_result',$data);
				$this->load->view('verify/footer');
			}
			else if($data['firstname']!="" && $data['lastname']!="" && $data['user_status']!="")
			{
				$this->load->model('select');
				$data['search_user']=$this->select->search_user($data);
				$this->load->view('verify/search_by_user_result',$data);
				$this->load->view('verify/footer');
			}
			else if($data['startdate']=="" && $data['enddate']=="" && $data['contract_type']==""&&$data['firstname']=="" && $data['lastname']=="" && $data['user_status']=="")
			{
				echo "please select either  Search by contract OR Search by user";
			}
			else if($data['startdate']!="" && $data['enddate']!="" && $data['contract_type']!=""&&$data['firstname']!="" && $data['lastname']!="" && $data['user_status']!="")
			{
				echo "please select either  Search by contract OR Search by user";
			}
			else 
			{
				echo "please fill the valid fields ";
			}
		}
		public function renter_information($contract_id)
		{
			$this->load->model('select');
			$data['renter_contract_information']=$this->select->renter_contract_information($contract_id);
			foreach($data['renter_contract_information']-> result() as $row)
			{
				$customer_id=$row->customer_id; 
				$landlord_id=$row->landlord_id; 
			}
			$data['result']=$this->List_Of_Admins_Core();
			$data['renter_customer_information']=$this->select->select_view_mycustomers($customer_id);
			$data['renter_landlord_information']=$this->select->select_view_mylandlords($landlord_id);
			$data['renter_account_information']=$this->select->contract_calculation($contract_id);
			$data['select_admin_core']=$this->select->select_admin_core($contract_id);
			$this->load->view('verify/renter_information_view',$data);
			$this->load->view('verify/footer'); 
		}
		
		public function reassign_renter_information()
		{
			$customer_id=$this->input->post('customer_id');
			$admin_id=$this->input->post('admin_id');
			$this->load->model('select');
			$data['reassign_renter_information']=$this->select->reassign_renter_information($customer_id,$admin_id);
		}
		public function renter_update_comments()
		{
			$customer_id=$this->input->post('customer_id');
			$comments=$this->input->post('comments');
			$this->load->model('select'); 
			$this->select->comments_Update($comments,$customer_id); 
		}
		public function ApproveContract()
		{
			$admin_id=$this->session->userdata("session_admin_id");
			$this->load->model('select'); 
			$config = array();
			$config["base_url"] = base_url()."index.php/verify/VerifyCustomer";
			$config['total_rows'] =$this->select->approve_contract_count(); 
			$config['cur_tag_open'] = '&nbsp;<a class="current">';
			$config['cur_tag_close'] = '</a>';
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Previous';
			$config['per_page'] = 5;
			$config["uri_segment"] = 3;
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
			$data["mycontract"]=$this->select->select_mycontract($admin_id,$config["per_page"], $page); 
			$data["allcontract"] = $this->select->allcontract($config["per_page"], $page);
			$data['result']=$this->List_Of_Admins_Core();
			$data["links"] = $this->pagination->create_links();
			$this->load->view('verify/approve_contract',$data); 
			$this->load->view('verify/footer');
			}
		public function AssignContract()
		{
			$session_id=$this->session->userdata("session_admin_id");
			$admin_id= $this->input->post('admin_id'); 
			$chkBoxArray= $this->input->post('chkBoxArray'); 
			$current_date=date("Y-m-d h:i:sa");
			$this->load->model('select'); 
			$this->select->assign_contract_confirm($admin_id,$chkBoxArray,$current_date,$session_id); 
			$this->select->assign_contract_update($chkBoxArray); 
			$data['contracts']=$this->select->select_value_contract($chkBoxArray); 
			$this->load->view('verify/contract_result',$data);  
		}
		public function contract_comments_update()
		{ 
			$customer_id=$this->input->post('id');
			$comments=$this->input->post('comments');
			$this->load->model('select'); 
			$this->select->contract_comments_update($comments,$customer_id);
		} 
		public function contract_amount_credit_details()
		{
			$session_id=$this->session->userdata("session_admin_id");
			$data['update_credit_details']=$this->select->contract_amount_credit_details($session_id);
			$this->load->view('verify/update_credit_result',$data);  
			$this->load->view('verify/footer'); 
		} 
		public function contract_amount_debit_details()
		{
			$session_id=$this->session->userdata("session_admin_id");
			$data['update_debit_details']=$this->select->contract_amount_debit_details($session_id);
			$this->load->view('verify/update_debit_result',$data);
			$this->load->view('verify/footer'); 
		}
		public function ViewContracts($contract_id)
		{
			$this->load->model('select');
			$data['renter_contract_information']=$this->select->renter_contract_information($contract_id);
			foreach($data['renter_contract_information']-> result() as $row)
			{
				$customer_id=$row->customer_id; 
				$landlord_id=$row->landlord_id; 
			}
			$data['result']=$this->List_Of_Admins_Core();
			$data['renter_customer_information']=$this->select->select_view_mycustomers($customer_id);
			$data['renter_landlord_information']=$this->select->select_view_mylandlords($landlord_id);
			$data['renter_account_information']=$this->select->contract_calculation($contract_id);
			$data['select_admin_core']=$this->select->select_admin_core($contract_id);
			$this->load->view('verify/my_contracts_view',$data);
			$this->load->view('verify/footer'); 
		}
		public function rentalcalculation()
		{
			$data['contract_id']= $this->input->post('contract_id');
			$data['flag']="";
			$data['error_code']=0;
			$this->load->library('admin_calculator_core');
			$result=$this->admin_calculator_core->calculatorcore($data);
			print_r($result['friday']);
			print_r($result['weeklyamount']);
		}
		public function CreditContract()
		{
			$chkBoxArray= $this->input->post('chkBoxArray');			
			$this->load->model('select');
			$this->select->credit_update($chkBoxArray);  
			
		}
		public function NotCreditContract()
		{
			$chkBoxArray= $this->input->post('chkBoxArray'); 
			$this->load->model('select');
			$this->select->Notcredit_update($chkBoxArray); 
		}
		public function DebitContract()
		{
			$chkBoxArray= $this->input->post('chkBoxArray'); 
			$this->load->model('select');
			$data['DebitContract']=$this->select->debit_update($chkBoxArray);
		}
		public function NotDebitContract()
		{
			$chkBoxArray= $this->input->post('chkBoxArray'); 
			$this->load->model('select');
			$this->select->Notdebit_update($chkBoxArray); 
		}
		public function approved_contract()
		{
			$customer_id= $this->input->post('customer_id'); 
			$this->load->model('select');
			$this->select->approved_contract_update($customer_id); 
		}
		public function rejection_contract()
		{
			$customer_id= $this->input->post('customer_id'); 
			$this->load->model('select');
			$this->select->rejection_contract_update($customer_id); 
		}
		public function cancelContracts()
		{
			$this->load->model('select');
			$data['cancel_contract']=$this->select->cancel_contract();
			$this->load->view('verify/cancel_contract_view.php',$data);
		}
		public function view_cancel_Contracts($contract_id)
		{
			$this->load->model('select');
			$data['renter_contract_information']=$this->select->renter_contract_information($contract_id);
			foreach($data['renter_contract_information']-> result() as $row)
			{
				$customer_id=$row->customer_id; 
				$landlord_id=$row->landlord_id; 
			}
			$data['result']=$this->List_Of_Admins_Core();
			$data['renter_customer_information']=$this->select->select_view_mycustomers($customer_id);
			$data['renter_landlord_information']=$this->select->select_view_mylandlords($landlord_id);
			$data['renter_account_information']=$this->select->contract_calculation($contract_id);
			$data['select_admin_core']=$this->select->select_admin_core($contract_id);
			$this->load->view('verify/cancel_contract_view_result.php',$data);
			$this->load->view('verify/footer'); 
		}
		public function terminatecontracts()
		{
			$this->load->model('select');
			$data['terminate_contract']=$this->select->terminate_contract();
			$this->load->view('verify/terminate_contract_view.php',$data);
		}
		public function view_terminate_contract($contract_id)
		{
			$this->load->model('select');
			$data['renter_contract_information']=$this->select->renter_contract_information($contract_id);
			foreach($data['renter_contract_information']-> result() as $row)
			{
				$customer_id=$row->customer_id; 
				$landlord_id=$row->landlord_id; 
			}
			$data['result']=$this->List_Of_Admins_Core();
			$data['renter_customer_information']=$this->select->select_view_mycustomers($customer_id);
			$data['renter_landlord_information']=$this->select->select_view_mylandlords($landlord_id);
			$data['renter_account_information']=$this->select->contract_calculation($contract_id);
			$data['select_admin_core']=$this->select->select_admin_core($contract_id);
			$this->load->view('verify/terminate_contract_view_result.php',$data);
			$this->load->view('verify/footer');
		}
		public function cancel_amount_calculation()
		{
			$data['flag']="Cancellation_amount_owed";
			$data['contract_id']= $this->input->post('contract_id'); 
			$data['rent']= $this->input->post('rent'); 
			$this->load->library('admin_calculator_core');
			$result=$this->admin_calculator_core->calculatorcore($data);
			echo $result['cancellation_amount'];
		}
		public function cancel_approved_update()
		{
			$customer_id= $this->input->post('customer_id'); 
			$this->load->model('select');
			$this->select->cancel_approved_update($customer_id); 
		}
		public function pending_amount_calculation()
		{
			$customer_id= $this->input->post('customer_id'); 
			$this->load->model('select');
			$result=$this->select->pending_amount_calculation($customer_id);
			$rent=0;
			foreach($result-> result() as $row)
			{
				$amount=$row->Amount; 
				$rental_amount=$row->rental_amount; 
				$rent=$rental_amount+$rent;
			}	
				$pending_amount=$amount-$rent;
				echo $pending_amount;
		}
		public function terminate_status_update()
		{
			$customer_id= $this->input->post('customer_id'); 
			$this->load->model('select');
			$this->select->terminate_status_update($customer_id); 
		}
		public function packagedefaulters()
		{
			$this->load->model('select');
			$data['packagedefaulters']=$this->select->packagedefaulters();
			$this->load->view('verify/package_view.php',$data);
			$this->load->view('verify/footer'); 
		}
		public function packagedefaulters_update()
		{
			$chkBoxArray= $this->input->post('chkBoxArray');
			$this->load->model('select');
			$data['packagedefaulters_update']=$this->select->packagedefaulters_update($chkBoxArray);
		}
	}
?>