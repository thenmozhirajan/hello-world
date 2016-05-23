<?php echo link_tag('assets/css/main.css')?>
<?php
		foreach ($customer->result() as $row)  
			{  
			?>
				<tr id="<?php echo $row->customer_id;?>" class="edit_tr1">
										
								<td><?php echo $row->first_name;?></td>  
								<td><?php echo $row->last_name;?></td>  
								<td><?php echo $row->ssn;?></td>
								<td class="edit_td1"><span id="first1_<?php echo $row->customer_id;?>; ?>"class="text"></span>
								<input value="<?php echo $row->comments;?>" type="text" value="" class="editbox" id="first_input1_<?php echo $row->customer_id;?>"></td>
								<td><button onClick="window.location.href = '<?php echo site_url();?>/verify/ViewCustomer/<?php echo $row->customer_id;?>';return false;" class="amount btn-select btn btn-lg" id="">ViewMore</button></td>
								
							 </tr>   
<?php 
}


