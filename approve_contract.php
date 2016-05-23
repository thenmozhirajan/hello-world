<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
			<title>Approve Contract</title>
				<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
				<script src="//code.jquery.com/jquery-1.10.2.js"></script>
				<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
				<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
				<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js"></script>
				<?php echo link_tag('assets/css/main.css')?>
		<script>
		$(function() {
		$( "#tabs" ).tabs();
		});
		</script>
	</head>
		<body>
		<div id="tabs">
		<ul>
			<li><a href="#tabs-1">All Contracts</a></li>
			<li><a href="#tabs-2">My Contracts</a></li>
		</ul>
		
		
				<div class="container">
				<div id="tabs-1">
					<div class="col-sm-12">
						<center><h3>Approve Contract</h3></center>
					</div>
					<center>
						<div class="col-sm-12">
						<h4>Un-Assigned Contracts</h4>
							<div class="table1">
							 <table class="table table-bordered" border="1">
									<tbody> 
										<tr>
											<th>Select </th>
											<th>Contract Startdate</th>
											<th>Contract Enddate</th>
											<th>Plantype</th>
											<th> Description</th>
											<th> Firstname</th>
											<th> Lastname</th>
											<th> Rental Amount</th>
											<th>Comments</th>
										</tr>
										<?php
										foreach ($allcontract->result() as $row)  
										{  
										?>
											<tr id="<?php echo $row->customer_id;?>" class="">
											<td> <input type="checkbox" name="contracts" id="<?php echo $row->customer_id;?>" class="contracts"></td>
											<td><?php echo $row->contract_startdate;?></td>  
											<td><?php echo $row->contract_enddate;?></td>   
											<td><?php echo $row->contract_type;?></td> 
											<td><?php echo $row->description;?></td> 
											<td><?php echo $row->first_name;?></td> 
											<td><?php echo $row->last_name;?></td>
											<td><?php echo $row->Amount;?></td> 											
											<td id="<?php echo $row->customer_id;?>" class="edit_contract"><span id="contract_<?php echo $row->customer_id;?>; ?>"class="text"></span>
											<input value="<?php echo $row->contract_comments;?>" type="text" value="" class="contract_editbox" id="contract_input<?php echo $row->customer_id;?>"></td>
												</tr>  
										<?php 
										}
										?>
									</tbody>  
								</table>  
							</div>
							  
						</div>
					</center>
					<br>
					<br>
					  	<?php echo $this->pagination->create_links();?>
					 
					<div class="col-sm-12 text-left">
					<h4 class="h4">list of Admins</h4>
					<div class="col-sm-12">
					<br>
					<div class="col-sm-12">
					  <?php
					  foreach ($result as $row1)  
							{  
						?>
					            <input type="radio" name="contract_admin" id="<?php echo $row1['id'];?>" class="<?php echo $row1['id'];?>" value="<?php echo $row1['id'];?>"><?php echo $row1['firstname'];?>
					  <?php
							}
							?>
					  <br>
					  <br>
					  </div>
					
					   <div class="clearfix"></div>
						<div class="col-sm-5 text-left">
					  <input type="button" name="assign_contract" id="assign_contract" class="assign_contract btn btn-md btn-assign" value="ASSIGN  ">
					
					  </div>
					<div id="showajaxresult"></div>	
					</div>
				</div> 
				
		
		</div>
		<div id="tabs-2">
	
			<center>
						<div class="col-sm-12">
						<h4>Assigned Contracts</h4>
							<div class="table1">
							 <table class="table table-bordered" border="1" id="insert">
									<tbody> 
										<tr>
										
											<th>Contract Startdate</th>
											<th>Contract Enddate</th>
											<th>Plantype</th>
											<th> Description</th>
											<th> Firstname</th>
											<th> Lastname</th>
											<th> Rental Amount</th>
											<th>Comments</th>
											<th>view </th>
										</tr>
										<?php
										foreach ($mycontract->result() as $row)  
										{  
										?>
											<tr id="<?php echo $row->contract_id;?>" class="edit_land">
											<td><?php echo $row->contract_startdate;?></td>  
											<td><?php echo $row->contract_enddate;?></td>   
											<td><?php echo $row->contract_type;?></td> 
											<td><?php echo $row->description;?></td> 
											<td><?php echo $row->first_name;?></td> 
											<td><?php echo $row->last_name;?></td>
											<td><?php echo $row->Amount;?></td> 											
											<td id="<?php echo $row->customer_id;?>" class="edit_contract1"><span id="contract1_<?php echo $row->customer_id;?>; ?>"class="text"></span>
											<input value="<?php echo $row->contract_comments;?>" type="text" value="" class="contract_editbox1" id="contract_input1<?php echo $row->customer_id;?>"></td>
											<td><button onClick="window.location.href = '<?php echo site_url();?>/verify/ViewContracts/<?php echo $row->contract_id;?>';return false;" class="view_contracts btn-select btn btn-lg" name="view_contracts">ViewMore</button></td>
												</tr>  
										<?php 
										}
										?>
									</tbody>  
								</table>  
							</div>
							<div id="showajaxresult1"></div>	  
						</div>
					</center>
				</div>
		</div>
		

</body>
</html>
			



