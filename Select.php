<?php  
	class Select extends CI_Model  
	{  
		function __construct()  
		{  
			//Call the Model constructor  
			parent::__construct();  
		}  
      
		public function select() 
		{  
			//data is retrive from this query  
			$query = $this->db->get('ra_rental_amount');  
			return $query;
		}
		public function select_cancelation() 
		{  
			//data is retrive from this query  
			$query = $this->db->get('ra_cancel_amount');  
			return $query;
		}
		public function update($data)
		{
			extract($data);
			$this->db->where('username',$this->session->userdata('username'));
			$this->db->update($table_name, array('password' =>$password));
			return true;
		}
		public function forgot_pass($data,$username)
		{
			extract($data);
			$this->db->where('username',$username);
			$this->db->update('ra_admin_login', array('password' =>""));
			return true;  
		}
		public function select_landlord($limit,$start) 
		{  
			//data is retrive from this query 
			//$this->db->where('status','To be verified');
			$this->db->limit($limit,$start);
			$where = "status='T' OR status='D'";
			$this->db->where($where);					
			$query = $this->db->get('ra_landlords');  
			return $query;
		}
		public function select_land_myusers($admin_id)
		{  
			$this->db->select('ra_landlords.landlord_id,ra_landlords.last_name,ra_landlords.first_name,ra_landlords.phone_number,ra_landlords.address_id,ra_landlords.zipcode,ra_landlords.accept_direct_deposit,ra_landlords.land_account_id,ra_landlords.status,ra_landlords.date_created,ra_landlords.date_modified,ra_landlords.comments');
			$this->db->from('ra_landlords');
			$this->db->where('ra_landlords_verification.admin_id',$admin_id);
			$this->db->where('ra_landlords.status!=',"V"); 
			$this->db->where('ra_landlords.status',"A");
			$this->db->join('ra_landlords_verification','ra_landlords.landlord_id=ra_landlords_verification.landlord_id');
			$query=$this->db->get();
			return $query;
		}
		public function landlord_myuser_row($admin_id)
		{  
			$this->db->select('ra_landlords.landlord_id,ra_landlords.last_name,ra_landlords.first_name,ra_landlords.phone_number,ra_landlords.address_id,ra_landlords.zipcode,ra_landlords.accept_direct_deposit,ra_landlords.account_id,ra_landlords.status,ra_landlords.date_created,ra_landlords.date_modified,ra_landlords.comments');
			$this->db->from('ra_landlords');
			$this->db->where('ra_landlords_verification.admin_id',$admin_id);
			$this->db->where('ra_landlords.status!=',"V"); 
			$this->db->where('ra_landlords.status',"A");
			$this->db->join('ra_landlords_verification','ra_landlords.landlord_id=ra_landlords_verification.landlord_id');
			return $this->db->count_all_results();
		}
		public function select_admin() 
		{  						
			$query = $this->db->get('ra_admin_login');  
			return $query;
		}	
		public function renter_contract_information($contract_id) 
		{
			$this->db->where('contract_id=',$contract_id);
			$query = $this->db->get('ra_contracts');
			return $query;
		}
		public function contract_calculation($contract_id) 
		{  
			$this->db->select('*');    
			$this->db->from('ra_account_details');
			$this->db->where('ra_contract_account_details.contract_id=',$contract_id);
			$this->db->join('ra_contract_account_details', 'ra_account_details.account_id = ra_contract_account_details.account_id');
			$this->db->join('ra_contracts', 'ra_contract_account_details.contract_id = ra_contracts.contract_id');
			$this->db->join('ra_landlords', 'ra_landlords.landlord_id = ra_contracts.landlord_id');
			$query = $this->db->get();
			return $query;
		}	
		public function select_myusers($admin_id,$limit,$start)
		{  
			$this->db->select('ra_customers.id,ra_customers.customer_id,ra_customers.first_name,ra_customers.last_name,ra_customers.date_of_birth,ra_customers.ssn,ra_customers.status,ra_customers.comments,ra_customers.date_created');	
			$this->db->from('ra_customers');
			$this->db->where('ra_customer_verification.admin_id',$admin_id);
			$this->db->where('ra_customers.status',"A");
			$this->db->order_by("date_created","asc");
			$this->db->join('ra_customer_verification','ra_customers.customer_id=ra_customer_verification.customer_id');
			$this->db->limit($limit,$start);
			return $this->db->get()->result();
		}
		public function select_mycontract($admin_id,$limit,$start)
		{  
			$this->db->select('*');	
			$this->db->from('ra_contracts');
			$this->db->where('ra_contracts_verification.admin_id',$admin_id);
			$this->db->where('ra_contracts.status',"Assigned");
			$this->db->order_by("ra_contracts_verification.date_created","asc");
			$this->db->join('ra_contracts_verification','ra_contracts.customer_id=ra_contracts_verification.customer_id');
			$this->db->join('ra_customers','ra_contracts.customer_id=ra_customers.customer_id');
			$this->db->limit($limit,$start);
			return $this->db->get();
		}
		public function select_acc_myusers($admin_id)
		{  
			$this->db->select('ra_customer_account_details.customer_account_id,ra_customer_account_details.customer_id,ra_customer_account_details.account_id,ra_customer_account_details.date_created,ra_customer_account_details.date_modified,ra_customer_account_details.Account_name,ra_customer_account_details.routing_no,ra_customers.first_name,ra_customers.last_name');
			$this->db->from('ra_customer_account_details');
			$this->db->where('ra_account_verification.admin_id',$admin_id);
			$this->db->where('ra_customer_account_details.status!=','V'); 
			$this->db->where('ra_customer_account_details.status',"A"); 
			$this->db->join('ra_customers','ra_customer_account_details.customer_id=ra_customers.customer_id');
			$this->db->join('ra_account_verification','ra_customer_account_details.customer_id=ra_account_verification.customer_id');
			$query=$this->db->get();
			return $query;
		}
		public function select_view_myusers($customer_id)
		{  
			$this->db->select('ra_customer_account_details.customer_account_id,ra_customer_account_details.customer_id,ra_customer_account_details.account_id,ra_customer_account_details.date_created,ra_customer_account_details.date_modified,ra_customer_account_details.Account_name,ra_customer_account_details.routing_no,ra_customers.first_name,ra_customers.last_name');
			$this->db->from('ra_customer_account_details');
			$this->db->where('ra_customer_account_details.customer_id',$customer_id);
			//$this->db->where('ra_customer_account_details.status="T"'); 					
			$this->db->join('ra_customers','ra_customer_account_details.customer_id=ra_customers.customer_id');
			$query=$this->db->get();
			return $query;
		}
		public function select_customer1($limit,$offset)			 
		{  
			//data is retrive from this query 
			$this->db->select("customer_id,comments,first_name,last_name,date_created");					
			$this->db->from('ra_customers');
			$this->db->where('status','U');	
			$this->db->order_by("date_created", "desc");	
			$this->db->limit($limit, $offset);					
			$query = $this->db->get();  
			return $query->result();
		} 
		public function get_values($limit,$offset)
		{
			$data = array();
			$this->db->select("customer_id,first_name,last_name,date_created,comments");	
			$this->db->where('status','U');	
			$this->db->order_by("date_created", "desc");	
			$this->db->limit($limit, $offset);
			$Q = $this->db->get('ra_customers',$limit,$offset);
			if($Q->num_rows() > 0)
			{
				foreach ($Q->result_array() as $row)
				{
					$data[] = $row;
				}
			}
			$Q->free_result();
			return $data;
		}
		public function totalcustomers()
		{
			return $this->db->count_all_results('ra_customers');
		}
		public function assign_customer_confirm($admin_id,$chkBoxArray,$current_date,$session_id)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$query="INSERT INTO ra_customer_verification(admin_id,customer_id,date_created,assign_by) VALUES ('".$admin_id."','".$valuearray."','".$current_date."','".$session_id."')"; 
				$this->db->query($query);
			}
			return true;
		}	
		public function assign_contract_confirm($admin_id,$chkBoxArray,$current_date,$session_id)
		{  	
			$ans =explode(',',$chkBoxArray);
		
			foreach( $ans as $valuearray)
			{
				$query="INSERT INTO ra_contracts_verification(admin_id,customer_id,date_created,assigned_by) VALUES ('".$admin_id."','".$valuearray."','".$current_date."','".$session_id."')"; 
				$this->db->query($query);
			}
			return true;
		}		
		public function Reassign_customer($admin_id,$customer_id,$current_date,$session_id)
		{  	
			$query="INSERT INTO ra_customer_verification(admin_id,customer_id,date_created,assign_by) VALUES ('".$admin_id."','".$customer_id."','".$current_date."','".$session_id."')"; 
			$this->db->query($query);
		}
		public function Reassign_customer_update($customer_id,$admin_id) 
		{
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_customers',array('status' =>"A"));
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_customer_verification',array('admin_id' =>"$admin_id"));
		}
		public function Reassign_landlord_update($landlord_id,$admin_id) 
		{
			$this->db->where('landlord_id',$landlord_id);
			$this->db->update('ra_landlords',array('status' =>"A"));
			$this->db->where('landlord_id',$landlord_id);
			$this->db->update('ra_landlords_verification',array('admin_id' =>"$admin_id"));
		}
		public function Reassign_account_update($customer_id,$admin_id) 
		{
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_customer_account_details',array('status' =>"A"));
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_account_verification',array('admin_id' =>"$admin_id"));
		}
		public function select_account($limit,$start) 
		{ 
			$this->db->select('ra_customer_account_details.customer_account_id,ra_customer_account_details.customer_id,ra_customer_account_details.account_id,ra_customer_account_details.date_created,ra_customer_account_details.date_modified,ra_customer_account_details.Account_name,ra_customer_account_details.routing_no,ra_customers.first_name,ra_customers.last_name');
			$this->db->from('ra_customer_account_details');
			//$this->db->where('ra_account_verification.admin_id',$admin_id);
			$this->db->limit($limit,$start);
			$this->db->where('ra_customer_account_details.status="T"'); 					
			$this->db->join('ra_customers','ra_customer_account_details.customer_id=ra_customers.customer_id');
			$query=$this->db->get();
			return $query;
		} 
		public function select_account_aftersave() 
		{ 
			$this->db->select('ra_customer_account_details.customer_account_id,ra_customer_account_details.customer_id,ra_customer_account_details.account_id,ra_customer_account_details.date_created,ra_customer_account_details.date_modified,ra_customer_account_details.Account_name,ra_customer_account_details.routing_no,ra_customers.first_name,ra_customers.last_name');
			$this->db->from('ra_customer_account_details');
			//$this->db->where('ra_account_verification.admin_id',$admin_id);
			$this->db->where('ra_customer_account_details.status="V"'); 					
			$this->db->join('ra_customers','ra_customer_account_details.customer_id=ra_customers.customer_id');
			$query=$this->db->get();
			return $query;
		} 
		public function assign_landlord_confirm($admin_id,$chkBoxArray,$current_date,$session_id)
		{  
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$query="INSERT INTO ra_landlords_verification(admin_id,landlord_id,date_created,assigned_by) VALUES ('".$admin_id."','".$valuearray."','".$current_date."','".$session_id."')"; 
				$this->db->query($query);
			}
			return true;
		}	
		public function customer_account_confirm($admin_id,$chkBoxArray,$current_date,$session_id)
		{  
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$query="INSERT INTO ra_account_verification(admin_id,customer_id,date_created,assigned_by) VALUES ('".$admin_id."','".$valuearray."','".$current_date."','".$session_id."')"; 
				$this->db->query($query);
			}
			return true;
		}	
		public function assign_landlord_save($landlord_id,$comments,$land_value)
		{  
			$this->db->where('landlord_id',$landlord_id);
			$this->db->update('ra_landlords', array('status' =>"$land_value"));
			$this->db->where('landlord_id',$landlord_id);
			$this->db->update('ra_landlords',array('comments'=>"$comments"));
			return true;
		}
		public function save_customer($customer_type,$customer_id,$comments)
		{
			if($customer_type=="Qualified")
			{
				$this->db->where('customer_id',$customer_id);
				$this->db->update('ra_customers', array('status' =>"C"));
			}
			else
			{
				$this->db->where('customer_id',$customer_id);
				$this->db->update('ra_customers', array('status' =>"D"));
			}
				$this->db->where('customer_id',$customer_id);
				$this->db->update('ra_customers',array('comments'=>"$comments"));
				return true;
			}
		public function customer_account_save($holder_id,$amount)
		{ 
			$this->db->where('customer_id',$holder_id);
			$this->db->update('ra_customer_account_details',array('status'=>"V"));
			$this->db->where('customer_id',$holder_id);
			$this->db->update('ra_account_verification',array('amount'=>"$amount"));
			return true;
		}
		public function activate_Customer($customer_id,$comment)
		{ 
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_customers', array('status' =>"C",'comments'=>"$comment"));
			return true;
		}
		public function deactivate_Customer($customer_id,$comment)
		{  
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_customers', array('status' =>"D",'comments'=>"$comment"));
			return true;
		}
		public function select_sample() 
		{  
			$this->db->select('candidate.firstname,candidate.user_id,candidate.lastname,candidate_details.email,candidate_details.phoneno');
			$this->db->from('candidate');
			$this->db->join('candidate_details', 'candidate.user_id = candidate_details.user_id');
			$query=$this->db->get();
			return $query;
		}	
		public function sample_search($fname_search,$email_search,$phoneno_search)
		{
			$this->db->select('candidate.firstname,candidate.user_id,candidate.lastname,candidate_details.email,candidate_details.phoneno');
			$this->db->where("`candidate.firstname` LIKE '%$fname_search%'");
			$this->db->where("`candidate_details.phoneno` LIKE '%$phoneno_search%'");
			$this->db->where("`candidate_details.email` LIKE '%$email_search%'");
			$this->db->from('candidate');
			$this->db->join('candidate_details','candidate.user_id=candidate_details.user_id');
			$query = $this->db->get();
			return $query;
		}
		public function sample_Add($data,$data1) 
		{  	
			$this->db->insert('candidate',$data);					
			$this->db->insert('candidate_details',$data1);
			return true;
		}	
		public function sample_Delete($user_id) 
		{
			$this->db->where('user_id', $user_id);
			$this->db->delete('candidate_details');
			$this->db->where('user_id', $user_id);
			$this->db->delete('candidate');
		}
		public function sample_Update($data,$user_id) 
		{
			$this->db->where('user_id', $user_id);
			$this->db->update('candidate', $data);
		}
		public function comments_Update($comments,$customer_id) 
		{
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_customers', array('comments' =>"$comments"));
		}
		public function contract_comments_update($comments,$customer_id) 
		{
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_contracts', array('contract_comments' =>"$comments"));
		}
		public function landlord_comments_Update($comments,$landlord_id) 
		{
			$this->db->where('landlord_id',$landlord_id);
			$this->db->update('ra_landlords', array('comments' =>"$comments"));
		}
		public function account_comments_Update($amount,$customer_id) 
		{
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_customer_account_details`',array('amount' =>"$amount"));
		}
		public function select_value($data,$data1) 
		{  
			$this->db->select('candidate.firstname,candidate.user_id,candidate.lastname,candidate_details.email,candidate_details.phoneno');
			$this->db->from('candidate');
			$this->db->where('email', $data1['email']);
			$this->db->join('candidate_details', 'candidate.user_id = candidate_details.user_id');
			$query=$this->db->get(); 
			return $query;
		}	
		public function select_value_delete($user_id) 
		{
			$this->db->where('user_id',$user_id);					
			$query = $this->db->get('candidate_details');  
			return $query;
		}	
		public function activate_myusers()
			{  
				$this->db->where('status',"C");
				$this->db->order_by("date_created", "asc");
				$query = $this->db->get('ra_customers');  
				return $query;
			}	
		public function deactivate_myusers()
		{   
			$this->db->where('status',"D");	
			$this->db->order_by("date_created", "asc");
			$query = $this->db->get('ra_customers');  
			return $query;
		}	
		public function verified_landlords()
		{   
			$this->db->where('status',"V");	
			$query=$this->db->get('ra_landlords');
			return $query;
		}
		public function verified_Customer_Accounts()
		{  
			$this->db->where('status',"V");	
			$query=$this->db->get('ra_customer_account_details');
			return $query;
		}
		public function select_view_mycustomers($customer_id) 
		{   
			$this->db->where('customer_id',$customer_id);					
			$query = $this->db->get('ra_customers');  
			return $query;
		}	
		public function ViewContracts($customer_id) 
		{   
			$this->db->where('customer_id',$customer_id);					
			$query = $this->db->get('ra_customers');  
			return $query;
		}	
		public function select_view_mylandlords($landlord_id) 
		{   
			$this->db->select('ra_landlords.landlord_id,ra_landlords.last_name,ra_landlords.first_name,ra_landlords.phone_number,ra_landlords.address_id,ra_landlords.zipcode,ra_landlords.accept_direct_deposit,ra_landlords.land_account_id,ra_landlords.status,ra_landlords.date_created,ra_landlords.date_modified,ra_landlords.comments,ra_addresses.city,ra_addresses.state,ra_addresses.address_line_1,ra_addresses.address_line_2');
			$this->db->from('ra_landlords');
			$this->db->where('landlord_id',$landlord_id);
			$this->db->join('ra_addresses','ra_addresses.address_id=ra_landlords.address_id');
			$query=$this->db->get();
			return $query;
		}		
		public function select_value_assign($chkBoxArray) 
		{   
			$count=1;
			$ans =explode(',',$chkBoxArray);
			$query="SELECT customer_id,first_name,last_name,date_of_birth,ssn,comments FROM ra_customers WHERE customer_id="; 
			foreach( $ans as $valuearray)
			{
				if($count>1)
				{
				$query=$query." OR 	customer_id=";
				}
			$query=$query.$valuearray;
			$count++;
			}
			return $this->db->query($query);
		}
		public function assign_customer_update($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$this->db->where('customer_id',$valuearray);
				$this->db->update('ra_customers', array('status' =>"A"));
			}
			 return true;
		}
		public function assign_contract_update($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$this->db->where('customer_id',$valuearray);
				$this->db->update('ra_contracts', array('status' =>"Assigned"));
			}
			return true;
		}
		public function assign_landlord_update($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$this->db->where('landlord_id',$valuearray);
				$this->db->update('ra_landlords', array('status' =>"A"));
			}
			return true;
		}
		public function customer_account_update($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$this->db->where('customer_id',$valuearray);
				$this->db->update('ra_customer_account_details', array('status' =>"A"));
			}
			return true;
		}
		public function select_value_customer($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$this->db->where('customer_id',$valuearray);
				$query = $this->db->get('ra_customers');  
			}
			return $query;
		}
		public function select_value_contract($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$this->db->select('*');
				$this->db->from('ra_contracts');
				$this->db->where('ra_contracts.customer_id',$valuearray);
				$this->db->join('ra_customers','ra_customers.customer_id=ra_contracts.customer_id');
				$query = $this->db->get();  
			}
			return $query;
		}
		public function select_value_landlord($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$this->db->select('ra_landlords.landlord_id,ra_landlords.last_name,ra_landlords.first_name,ra_landlords.phone_number,ra_landlords.address_id,ra_landlords.zipcode,ra_landlords.accept_direct_deposit,ra_landlords.account_id,ra_landlords.status,ra_landlords.date_created,ra_landlords.date_modified,ra_landlords.comments');
				$this->db->from('ra_landlords');
				$this->db->where('ra_landlords.landlord_id',$valuearray); 					
				$this->db->join('ra_landlords_verification','ra_landlords.landlord_id=ra_landlords_verification.landlord_id');
				$query=$this->db->get();
				return $query; 
			 }
		}
		public function select_value_customer_account($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$this->db->select('ra_customer_account_details.customer_account_id,ra_customer_account_details.customer_id,ra_customer_account_details.account_id,ra_customer_account_details.date_created,ra_customer_account_details.date_modified,ra_customer_account_details.Account_name,ra_customer_account_details.routing_no,ra_customers.first_name,ra_customers.last_name');
				$this->db->from('ra_customer_account_details');
				$this->db->where('ra_customer_account_details.customer_id=',$valuearray); 
				$this->db->join('ra_customers','ra_customer_account_details.customer_id=ra_customers.customer_id');
				$this->db->join('ra_account_verification','ra_customer_account_details.customer_id=ra_account_verification.customer_id');
				$query=$this->db->get();
				return $query;
			}
		}
		public function record_count()
		{
			$this->db->where('status',"U");
			$this->db->from('ra_customers');
			return $this->db->count_all_results();

		}
		public function approve_contract_count()
		{
			$this->db->where('status',"Document_submitted");
			$this->db->from('ra_contracts');
			return $this->db->count_all_results();
		}
		public function assign_customer_row()
		{
			$this->db->where('status',"A");
			$this->db->from('ra_customers');
			return $this->db->count_all_results();
		}
		public function landlord_row()
		{
			$this->db->where('status',"T");
			$this->db->from('ra_landlords');
			return $this->db->count_all_results();

		}
		public function account_row()
		{
			$this->db->where('status',"T");
			$this->db->from('ra_customer_account_details');
			return $this->db->count_all_results();

		}
		public function fetch_data($limit,$id) 
		{
			$offset = $this->uri->segment(4);
			$this->db->limit($limit,$offset);
			$this->db->where('status', "U");
			$this->db->order_by("date_created", "desc");	
			$query = $this->db->get("ra_customers");
			if ($query->num_rows() > 0) 
			{
				foreach ($query->result() as $row)
				{
					$data[] = $row;
				}
				return $data;
			}
			return false;
		}
		public function verifycustomer($limit,$start)
		{
			$this->db->limit($limit,$start);
			$this->db->select('*');
			$this->db->where('status',"U");
			$this->db->order_by("date_created","asc");
			$this->db->from('ra_customers');
			return $this->db->get()->result();
		}
		public function allcontract($limit,$start)
		{
			$this->db->limit($limit,$start);
			$this->db->select('*');
			$this->db->from('ra_contracts');
			$this->db->where('ra_contracts.status =',"Document-Submitted");
			$this->db->order_by("ra_contracts.date_created","asc");
			$this->db->join('ra_customers','ra_customers.customer_id=ra_contracts.customer_id');
			return $this->db->get();
		}
		public function cancellation_creditamount_owe()
		{
			$now=date('Y-m-d');
			$query = $this->db->select('sum(rental_amount)as credit_amount');
			$where = "status='Verified' OR status='Notdebited'";
			$this->db->where($where);	
			$this->db->where('date_created <',$now);
			$query = $this->db->get('ra_contract_amount_credit_details');
			return $query;
		} 
		public function packagedefaulters()
		{
			$now=date('Y-m-d');
			$prevdate= strtotime('-1 month', strtotime($now)); 
			$date=date('Y-m-d',$prevdate);
			$this->db->select('ra_customers.first_name,ra_customers.phone_number,ra_addresses.address_line_1,ra_customers.email_id,ra_customers.ssn,ra_contract_amount_debit_details.date_created,ra_customers.customer_id,ra_contract_amount_debit_details.rental_amount');    
			$this->db->from('ra_contract_amount_debit_details');
			$this->db->where('ra_contract_amount_debit_details.status',"Not Debited");
			$this->db->where('ra_contract_amount_debit_details.date_created<',$date);
			$this->db->group_by('ra_contract_amount_debit_details.contract_account_id');
			$this->db->join('ra_contract_account_details','ra_contract_amount_debit_details.contract_account_id=ra_contract_account_details.contract_account_id');
			$this->db->join('ra_contracts','ra_contracts.customer_id=ra_contract_account_details.customer_id');
			$this->db->join('ra_customers','ra_contracts.customer_id=ra_customers.customer_id');
			$this->db->join('ra_addresses','ra_addresses.address_id=ra_customers.primary_address_id');
			
			$query = $this->db->get();
			return $query;
		} 
		public function cancellation_debitamount_owe()
		{
			$now=date('Y-m-d');
			$this->db->select('sum(rental_amount)as debit_amount');
			$this->db->where('status',"Verified");
			$this->db->where('date_created <',$now);
			$query = $this->db->get('ra_contract_amount_debit_details'); 					
			return $query;
		} 
		public function search_contract($data)
		{
			$this->db->select('*');
			$this->db->from('ra_customers');
			$this->db->where('ra_contracts.contract_startdate=',$data['startdate']);
			$this->db->where('ra_contracts.contract_enddate=',$data['enddate']);
			//$this->db->where('ra_contracts.contract_type=',$data['contract_type']);
			$this->db->join('ra_contracts','ra_contracts.customer_id=ra_customers.customer_id');
			$query = $this->db->get();
			return $query;
		}
		public function search_user($data)
		{
			$this->db->select('ra_contracts.contract_id,ra_contracts.contract_startdate,ra_contracts.contract_enddate,ra_contracts.description,ra_customers.first_name,ra_customers.last_name,ra_contracts.status,ra_contracts.contract_type');
			$this->db->from('ra_customers');
			$this->db->where('ra_customers.first_name=',$data['firstname']);
			$this->db->where('ra_customers.last_name=',$data['lastname']);
			$this->db->where('ra_contracts.status=',$data['user_status']);
			$this->db->join('ra_contracts','ra_contracts.customer_id=ra_customers.customer_id');
			$query = $this->db->get();
			return $query;
		}
		public function renter_customer_information($customer_id)
		{
			$this->db->select('ra_customers.first_name,ra_customers.lastname_name,');
			$this->db->from('ra_customers');
			$this->db->where('ra_customers.customer_id=',$customer_id);
			$this->db->join('ra_contracts','ra_contracts.customer_id=ra_customers.customer_id');
			$query = $this->db->get();
			return $query;
		}
		public function select_admin_core($contract_id)
		{  
			$this->db->select('*');
			$this->db->from('ra_customers');
			$this->db->where('ra_contracts.contract_id=',$contract_id);
			$this->db->join('ra_customer_verification','ra_customers.customer_id=ra_customer_verification.customer_id');
			$this->db->join('ra_contracts','ra_contracts.customer_id=ra_customers.customer_id');
			return  $this->db->get();
		}
		public function reassign_renter_information($customer_id,$admin_id) 
		{
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_customer_verification', array('admin_id' =>"$admin_id"));
		}
		public function contract_amount_credit_details($session_id) 
		{
			$current_date=date('Y-m-d');
			$this->db->select('*');
			$this->db->from('ra_contracts');
			$this->db->where('ra_contract_amount_credit_details.status',"To be Verified");
			$this->db->where('ra_contracts.status',"Active");
			$this->db->where('ra_contracts_verification.admin_id=',$session_id);
			$this->db->where('ra_contract_amount_credit_details.rental_date <=',$current_date); 
			$this->db->group_by('ra_contracts.contract_id');
			$this->db->order_by('`ra_contract_amount_credit_details`.`rental_date`','asc');
			$this->db->join('ra_contracts_verification','ra_contracts.customer_id=ra_contracts_verification.customer_id');
			$this->db->join('ra_contract_account_details','ra_contracts.customer_id=ra_contract_account_details.customer_id');
			$this->db->join('ra_contract_amount_credit_details','ra_contract_amount_credit_details.contract_amount_credit_details_id=ra_contract_account_details.contract_account_id');
			$this->db->join('ra_account_details','ra_account_details.account_id=ra_contract_account_details.account_id'); 
			$query = $this->db->get()->result(); 					
			return $query;
		}
		public function contract_amount_debit_details($session_id) 
		{
			$current_date=date('Y-m-d');
			$this->db->select('*');
			$this->db->from('ra_contracts');
			$this->db->where('ra_contract_amount_debit_details.status',"To be Verified");
			$this->db->where('ra_contracts.status',"Active");
			$this->db->where('ra_contracts_verification.admin_id=',$session_id);
			$this->db->where('ra_contract_amount_debit_details.rental_date <=',$current_date);
			$this->db->group_by('ra_contracts.contract_id');
			$this->db->order_by('`ra_contract_amount_debit_details`.`rental_date`','asc');
			$this->db->join('ra_contracts_verification','ra_contracts.customer_id=ra_contracts_verification.customer_id');
			$this->db->join('ra_contract_account_details','ra_contracts.customer_id=ra_contract_account_details.customer_id');
			$this->db->join('ra_contract_amount_debit_details','ra_contract_amount_debit_details.contract_amount_debit_details_id=ra_contract_account_details.contract_account_id');
			$this->db->join('ra_account_details','ra_account_details.account_id=ra_contract_account_details.account_id');
			$query = $this->db->get()->result(); 					
			return $query;
		}
		public function credit_update($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$query="UPDATE ra_contract_amount_credit_details AS t1 INNER JOIN ra_contract_account_details AS t2 ON t1.contract_account_id= t2.contract_account_id SET t1.status ='Credited' WHERE t2.customer_id ='".$valuearray."'";
				$this->db->query($query);
			} 
			return true;
		}
		public function Notcredit_update($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$query="UPDATE ra_contract_amount_credit_details AS t1 INNER JOIN ra_contract_account_details AS t2 ON t1.contract_account_id= t2.contract_account_id SET t1.status ='NotCredited' WHERE t2.customer_id ='".$valuearray."'";
				$this->db->query($query);
			}
			return true;
		}
		public function debit_update($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$query="UPDATE ra_contract_amount_debit_details AS t1 INNER JOIN ra_contract_account_details AS t2 ON t1.contract_account_id= t2.contract_account_id SET t1.status ='Debited' WHERE t2.customer_id ='".$valuearray."'";
				$this->db->query($query);
			}
		return true;
		}
		public function Notdebit_update($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$query="UPDATE ra_contract_amount_debit_details AS t1 INNER JOIN ra_contract_account_details AS t2 ON t1.contract_account_id= t2.contract_account_id SET t1.status ='NotDebited' WHERE t2.customer_id ='".$valuearray."'";
				$this->db->query($query);
			}
			return true;
		}
		public function approved_contract_update($customer_id) 
		{
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_contracts`',array('status' =>"Approved"));
		}
		public function rejection_contract_update($customer_id) 
		{
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_contracts`',array('status' =>"Rejected"));
		}
		public function cancel_approved_update($customer_id) 
		{
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_contracts`',array('status' =>"Cancel-Approved"));
		}
		public function terminate_contract() 
		{
			$this->db->select('*');
			$this->db->from('ra_contracts');
			$this->db->where('ra_contracts.status',"Terminated");
			$this->db->join('ra_customers','ra_contracts.customer_id=ra_customers.customer_id');
			$query = $this->db->get();
			return $query;
		}
		public function cancel_contract() 
		{
			$this->db->select('*');
			$this->db->from('ra_contracts');
			$this->db->where('ra_contracts.status',"Cancelled");
			$this->db->join('ra_customers','ra_contracts.customer_id=ra_customers.customer_id');
			$query = $this->db->get();
			return $query;
		}
		public function pending_amount_calculation($customer_id) 
		{
			$this->db->select('*');    
			$this->db->from('ra_contract_amount_debit_details');
			$this->db->where('ra_contracts.customer_id',$customer_id);
			$this->db->where('ra_contract_amount_debit_details.status',"Not Debited");
			$this->db->join('ra_contract_account_details','ra_contract_amount_debit_details.contract_account_id=ra_contract_account_details.contract_account_id');
			$this->db->join('ra_contracts','ra_contracts.customer_id=ra_contract_account_details.customer_id');
			$query = $this->db->get();
			return $query;
		}
		public function terminate_status_update($customer_id) 
		{
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_contracts',array('status' =>"Terminated–Approved"));
			$this->db->where('customer_id',$customer_id);
			$this->db->update('ra_customers',array('status' =>"B"));
		}
		public function packagedefaulters_update($chkBoxArray)
		{  	
			$ans =explode(',',$chkBoxArray);
			foreach( $ans as $valuearray)
			{
				$this->db->where('customer_id',$valuearray);
				$this->db->update('ra_customers', array('status' =>"D"));
			}
			return true;
		}
		public function verify_landlord_update($landlord_id)
		{
			$this->db->where('landlord_id',$landlord_id);
			$this->db->update('ra_landlords', array('status' =>"V"));
		}
	}
    ?> 