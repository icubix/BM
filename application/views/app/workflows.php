<br>
<div class="col-md-10">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3>Work Flow</h3></div>
        <div class="panel-body">
            <div class="container-fluid">

            	<div class="row">
   
  <div class="col-lg-12  ">
  	<form class="form-horizontal">
  	 

    <div class="form-group col-md-6">
    <label for="workflow-name" class="col-sm-4 control-label">Work Flow Name:</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="workflow-name"  >
    </div>
  </div>
   
    <div class="input-group col-md-4 col-md-offset-6   text-right">
      <input type="text" class="form-control col-md-4" placeholder="Filter rows with search expression here...">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span>
    </div><!-- /input-group -->
<br>
    <div class="form-group col-md-6">
    <label for="workflow" class="col-sm-4 control-label">Work Flow #:</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="workflow"  >
    </div>
  </div>
</form>
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
<br>
         <div class="container-fluid">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#catalog" aria-controls="catalog" role="tab" data-toggle="tab">Catalog</a></li>
                    <li role="presentation"  ><a href="#workFlowDetails" aria-controls="workFlowDetails" role="tab" data-toggle="tab">Work Flow Detailss</a></li>
            
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="catalog">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th width="15%">Number</th>
                                <th width="85%">Name</th>
                                 
                            </tr>
                            <tr>
                                <td>W0100</td>
                                <td>Alabama - Circuit 15 </td>
                                 
                            </tr>
                            <tr>
                               <td>W0200</td>
                                <td>Louisiana</td>
                            </tr>
                             <tr>
                               <td>W0201</td>
                                <td>New Mexico - General</td>
                            </tr>
                             <tr>
                               <td><a href="<?php echo site_url("workflows#workFlowDetails"); ?>" class="btn btn-success">W0301</a></td>
                                <td> Texas - Country 14</td>
                            </tr>
                             <tr>
                               <td>Wnnnn</td>
                                <td>Add a new work flow name here </td>
                            </tr>
                             
                             
                             
                             
                        </table>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="workFlowDetails">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th width="5%">Rank </th>
                                <th width="5%">Milestone</th>
                                <th width="15%">Phase</th>
                                <th width="35%">To Do Next</th>
                                <th width="40%">Comment</th>
                                 
                            </tr>
                            <tr>
                                <td>2</td>
                                <td> </td>
                                <td>Info</td>
                                <td>Ad defendant cntact info</td>
                                <td> </td>
                                 
                            </tr>
                            <tr>
                               <td>4</td>
                                <td> </td>
                                <td>Info</td>
                                <td>Add defendant employment info</td>
                                <td> </td>
                            </tr>
                             <tr>
                               <td>6</td>
                                <td> </td>
                                <td>Info</td>
                                <td>Add defendant employement info</td>
                                <td></td>
                            </tr>
                             <tr>
                               <td>8</td>
                                <td> <input type="checkbox" class="center" checked="checked"> </td>
                                <td>Info</td>
                                <td>Mark Defendant Phase Complete</td>
                                <td>Commment</td>
                            </tr>
                             <tr>
                               <td>10</td>
                                <td> </td>
                                <td>Indemnitor</td>
                                <td>Add Indemnitor 2info</td>
                                <td>Commment</td>
                            </tr>
                            <tr>
                               <td>12</td>
                                <td> </td>
                                <td>Indemnitor</td>
                                <td>Add Indemnitor 1 info</td>
                                <td>Commment</td>
                            </tr>
                            <tr>
                              <td>13</td>
                                <td> <input type="checkbox" class="center" checked="checked"> </td>
                                <td>Info</td>
                                <td>Mark Indemnitor Phase Complete</td>
                                <td>Commment</td>
                            </tr>
                             <tr>
                              <td>14</td>
                                <td>  </td>
                                <td> Power</td>
                                <td>Assign Power</td>
                                <td> </td>
                            </tr>
                             <tr>
                              <td>16</td>
                                <td> <input type="checkbox" class="center" checked="checked">  </td>
                                <td> Power</td>
                                <td>Mark Power Phase Complete</td>
                                <td> </td>
                            </tr>
                            <tr>
                              <td>18</td>
                                <td> <input type="checkbox" class="center" checked="checked">  </td>
                                <td> Bond</td>
                                <td>Assign Bond ad Mark Phase Completed</td>
                                <td> </td>
                            </tr>

                           <tr>
                              <td>20</td>
                                <td>  </td>
                                <td> Payment</td>
                                <td>Collect Payment</td>
                                <td> </td>
                            </tr>    
                             <tr>
                              <td>22</td>
                                <td> <input type="checkbox" class="center" checked="checked"> </td>
                                <td> Payment</td>
                                <td>Mark All Payments Received Complete</td>
                                <td> </td>
                            </tr>                            
                              <tr>
                              <td>24</td>
                                <td>  </td>
                                <td> Docs</td>
                                <td>Scan Drivers License</td>
                                <td> </td>
                            </tr>  
                            <tr>
                              <td>26</td>
                                <td>  </td>
                                <td> Docs</td>
                                <td>Scan Court Documents</td>
                                <td> </td>
                            </tr>  

                            <tr>
                              <td>28</td>
                                <td><input type="checkbox" class="center" checked="checked">  </td>
                                <td> Docs</td>
                                <td>Mark Documents Phase Complete</td>
                                <td> </td>
                            </tr>  
                            <tr>
                              <td>30 </td>
                                <td><input type="checkbox" class="center" checked="checked">  </td>
                                <td> Case </td>
                                <td>Mark Entire Case Completed and Cloased</td>
                                <td> </td>
                            </tr> 
                            <tr>
                              <td>- </td>
                                <td> </td>
                                <td> </td>
                                <td>Insert a new workflow step here</td>
                                <td> </td>
                            </tr> 
                            <tr>
                              <td>-</td>
                                <td> </td>
                                <td>   </td>
                                <td>Use add numbers to insert between existing steps</td>
                                <td> </td>
                            </tr> 
                        </table>
                    </div>
                  
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
 