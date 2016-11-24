<br>
<div class="col-md-10">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3>Company</h3></div>
        <div class="panel-body">
            <div class="container-fluid">

            	<div class="row">
   
  <div class="col-lg-12  ">
   
    <div class="input-group col-md-4 col-md-offset-6   text-right">
      <input type="text" class="form-control col-md-4" placeholder="Filter rows with search expression here...">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
<br>
         <div class="container-fluid">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#inuse" aria-controls="inuse" role="tab" data-toggle="tab">In Use</a></li>
             <li role="presentation"  ><a href="#available" aria-controls="available" role="tab" data-toggle="tab">Available</a></li>
             <li role="presentation"  ><a href="#all" aria-controls="all" role="tab" data-toggle="tab">All</a></li>
              <li role="presentation"  ><a href="#AddNewPower" aria-controls="personalinfo" role="tab" data-toggle="modal" data-target="#myAddCompany">Add Company</a></li>
            
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="inuse">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Number</th>
                                <th>Amount</th>
                                <th>BM Case</th>
                                <th>Assigned</th>
                                <th>Expires</th>
                                <th>Surety Supplier</th>
                                 
                            </tr>
                            <tr>
                                <td> P1223456</td>
                                <td> $ 2000</td>
                                <td> 228-567</td>
                                <td>2016-02-28 </td>
                                <td> 2016-02-31</td>
                                <td>Bail Surety Inc</td>
                                 
                            </tr>
                            <tr>
                                <td>P2000001 </td>
                                <td>$ 5000 </td>
                                <td> 228-568</td>
                                <td> 2016-02-28</td>
                                <td> 2016-02-31</td>
                                 <td>Bail Surety Inc</td>
                            </tr>
                            <tr>
                                <td> - </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> Add a new power here</td>
                                 
                            </tr>
                             
                             
                        
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane " id="available">
                         <table class="table table-striped table-bordered">
                            <tr>
                                <th>Number</th>
                                <th>Amount</th>
                                <th>BM Case</th>
                                <th>Assigned</th>
                                <th>Expires</th>
                                <th>Surety Supplier</th>
                                 
                            </tr>
                            <tr>
                                <td> P1223456</td>
                                <td> $ 2000</td>
                                <td> 228-567</td>
                                <td>2016-02-28 </td>
                                <td> 2016-02-31</td>
                                <td>Bail Surety Inc</td>
                                 
                            </tr>
                            <tr>
                                <td>P2000001 </td>
                                <td>$ 5000 </td>
                                <td> 228-568</td>
                                <td> 2016-02-28</td>
                                <td> 2016-02-31</td>
                                 <td>Bail Surety Inc</td>
                            </tr>
                            <tr>
                                <td> - </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> Add a new power here</td>
                                 
                            </tr>
                             
                             
                        
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="all">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>CompanyID</th>
                                <th>Company Name</th>
                            </tr>
                           <?php foreach($AllOrganization as $key => $value){ ?>
                            <tr>
                                <td>
                                <?php print_r($value['organization_id'])?>
                                </td>
                                <td><?php print_r($value['organization_name']) ?></td>
                            </tr>
                           <?php } ?>
                        </table>
                           
                    </div>
                    <div role="tabpanel" class="tab-pane" id="addnew">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Number</th>
                                <th>Amount</th>
                                <th>BM Case</th>
                                <th>Assigned</th>
                                <th>Expires</th>
                                <th>Surety Supplier</th>
                                 
                            </tr>
                            <tr>
                                <td> P1223456</td>
                                <td> $ 2000</td>
                                <td> 228-567</td>
                                <td>2016-02-28 </td>
                                <td> 2016-02-31</td>
                                <td>Bail Surety Inc</td>
                                 
                            </tr>
                            <tr>
                                <td>P2000001 </td>
                                <td>$ 5000 </td>
                                <td> 228-568</td>
                                <td> 2016-02-28</td>
                                <td> 2016-02-31</td>
                                 <td>Bail Surety Inc</td>
                            </tr>
                            <tr>
                                <td> - </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> Add a new power here</td>
                                 
                            </tr>
                             
                             
                        
                        </table>
                    </div>
                     
                  
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myAddCompany" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New Power</h4>
      </div>
      <div class="modal-body">
        <table>
            <tr>
               <?php  echo form_open('company/addcompany')?>
                <?php echo form_input(array('id'=>'company','name'=>'company','value'=>'True','type'=>'hidden'));?>
                <td>Organization Name</td>
                <td  style="padding:20px"><?php echo form_input(array('id'=> 'organization_name',
                    'name'=>'organization_name',
                    'placeholder'=>'Orgnization',
                    'autofocus' => 'autofocus',
                    'class' =>'form-control'));?>
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
 