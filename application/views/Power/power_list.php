<br>

<div class="col-md-10">
<div class="panel panel-primary">
<div class="panel-heading"><h3>Power</h3></div>
<div class="panel-body">          
<div class="container-fluid">

<!-- Body goes here -->

<ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#InUse" aria-controls="contactinfo" role="tab" data-toggle="tab">In Use</a></li>
        <li role="presentation"><a href="#Available" aria-controls="personalinfo" role="tab" data-toggle="tab">Available</a></li>
        <li role="presentation"><a href="#All" aria-controls="personalinfo" role="tab" data-toggle="tab">All</a></li>
        <li role="presentation"><a href="#AddNewPower" aria-controls="personalinfo" role="tab" data-toggle="modal" data-target="#myAddNewPower">Add New Power</a></li>
        
</ul>
 <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="InUse">
	        <table class="table table-striped">
	        	<tr>
	        		<td>Number</td>
	        		<td> Amount</td>
	        		<td> Case # </td>
	        		<td> Defendant </td>
	        		<td> Assigned </td>
	        		<td> Expires </td>
	        		<td> Surety Supplier </td>
	        	</tr>
	        	<!-- <?php foreach ($data as $key => $value) {?>
	        		<tr>
	        		<td> <?php print_r($value['number']); ?></td>
	        		<td> <?php print_r($value['Amount']); ?></td>
	        		<td> <?php print_r($value['case']); ?></td>
	        		<td> <?php print_r($value['defendant']); ?></td>
	        		<td> <?php print_r($value['assigned']); ?></td>
	        		<td> <?php print_r($value['expires']); ?></td>
	        		<td> <?php print_r($value['surety']); ?></td>
	        		</tr>
	        	<?php }?>  -->
	        </table>
        </div>
         <div role="tabpanel" class="tab-pane" id="Available">
        	<table class="table table-striped">
	        	<tr>
	        		<td>Number</td>
	        		<td> Amount</td>
	        		<td> Expires </td>
	        		<td> Surety Supplier </td>
	        	</tr>
	        	<!-- <?php foreach ($data as $key => $value) {?>
	        		<tr>
	        		<td> <?php print_r($value['number']); ?></td>
	        		<td> <?php print_r($value['Amount']); ?></td>
	        		<td> <?php print_r($value['expires']); ?></td>
	        		<td> <?php print_r($value['surety']); ?></td>
	        		</tr>
	        	<?php }?>  -->
	        </table>
        </div>
         <div role="tabpanel" class="tab-pane" id="All">
       		<table class="table table-striped">
	        	<tr>
	        		<td>Number</td>
	        		<td> Amount</td>
	        		<td> Expires </td>
	        		<td> Surety Supplier </td>
	        	</tr>
	        	<?php foreach ($AllPowers as $key => $value) {?>
	        		<tr>
	        		<td> <?php print_r($value['power_number']); ?></td>
	        		<td> <?php print_r($value['power_amount']); ?></td>
	        		<td></td>
	        		<td></td>
	        		</tr>
	        	<?php }?> 
	        </table>
        </div>
         <div role="tabpanel" class="tab-pane" id="AddNewPower">
        	<table class="table table-striped">
	        	<tr>
	        		<td>Number</td>
	        		<td> Amount</td>
	        		<td> Expires </td>
	        		<td> Surety Supplier </td>
	        		<td></td>
	        	</tr>
	        	<tr>
	        		<td colspan="4">
	        			
	        		</td>
	        		<td>
	        			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myAddNewPower">Add</button>
	        		</td>
	        	</tr>
	        	<!-- <?php foreach ($data as $key => $value) {?>
	        		<tr>
	        		<td> <?php print_r($value['number']); ?></td>
	        		<td> <?php print_r($value['Amount']); ?></td>
	        		<td> <?php print_r($value['expires']); ?></td>
	        		<td> <?php print_r($value['surety']); ?></td>
	        		<td> <button type="button" class="btn btn-primary">Edit</button></td>
	        		<td> <button type="button" class="btn btn-primary">Cancel</button></td>
	        		</tr>
	        	<?php }?>  -->
	        </table>
        </div>
</div>

<!-- Model dialog -->

 <!-- Modal -->
<div class="modal fade" id="myAddNewPower" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New Power</h4>
      </div>
      <div class="modal-body">
        <table>
        	<tr>
        		<?php  echo form_open('powers/addpower')?>
        		<?php echo form_input(array('id'=>'power','name'=>'power','value'=>'True','type'=>'hidden'));?>
        		<td>Number</td>
        		<td  style="padding:20px"><?php echo form_input(array('id'=> 'power_number',
        			'name'=>'power_number',
        			'placeholder'=>'Number',
        			'autofocus' => 'autofocus',
        			'class' =>'form-control'));?>
        	</tr>
        	<tr>
        		<td>
        			Amount
        		</td>
        		<td style="padding:20px">
        			<?php echo form_input(array('id'=> 'power_amount','name'=>'power_amount',
        				'placeholder'=>'Amount',
        			'autofocus' => 'autofocus',
        			'class' =>'form-control'));?>
        		</td>
        	</tr>
        	<tr>
        		<td>Expiry</td>
        		<td style="padding:20px">
        			<?php echo form_input(array('id'=> 'pDate','name'=>'pDate','placeholder'=>'Expiry',
        			'autofocus' => 'autofocus',
        			'class' =>'form-control'));?>
        		</td>
        	</tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" name="submitForm" value="formSave" class="btn btn-primary">Save changes</button>
      </div>
      <!-- <input type="hidden" name="isNew" value="False" /> -->
      <?php echo form_input(array('id'=>'isNew','name'=>'isNew','value'=>'True','type'=>'hidden'));?>

      <?php echo form_close()?>
    </div>
  </div>
</div>



<!-- ending of thream -->
</div>
</div>
</div>

</div>