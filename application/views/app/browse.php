<br>
<div class="col-md-10">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3>Browse Cases</h3></div>
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
                    <li role="presentation" class="active"><a href="#recent" aria-controls="recent" role="tab" data-toggle="tab">Recent</a></li>
                    <li role="presentation"><a href="#open" aria-controls="open" role="tab" data-toggle="tab">Open</a></li>
                    <li role="presentation"><a href="#closed" aria-controls="closed" role="tab" data-toggle="tab">Closed</a></li>
                     <li role="presentation"><a href="#all" aria-controls="all" role="tab" data-toggle="tab">All</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="recent">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Date</th>
                                <th>Court</th>
                                <th>Status</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Suffix</th>
                                <th>Phone</th>
                                <th>Address</th>
                            </tr>
                            <tr>
                                <td>2016-02-20</td>
                                <td>TX 1234</td>
                                <td><a href="<?php echo site_url("newcase/defendentdetails"); ?>" class="btn btn-success">open</a></td>
                                <td>Giaomo</td>
                                <td>Vinnie</td>
                                <td>Jr</td>
                                <td>281-777-1020</td>
                                <td>123 Anywhere Street Houston</td>
                            </tr>
                            <tr>
                               <td>2016-02-20</td>
                                <td>TX 1234</td>
                                <td>open</td>
                                <td>Giaomo</td>
                                <td>Vinnie</td>
                                <td>Jr</td>
                                <td>281-777-1020</td>
                                <td>123 Anywhere Street Houston</td>
                            </tr>
                             
                             
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="open">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Date</th>
                                <th>Court</th>
                                <th>Status</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Suffix</th>
                                <th>Phone</th>
                                <th>Address</th>
                            </tr>
                            <tr>
                                <td>2016-02-21</td>
                                <td>TX 1234</td>
                                <td><a href="<?php echo site_url("newcase/defendentdetails"); ?>" class="btn btn-success">open</a></td>
                                <td>Giaomo</td>
                                <td>Vinnie</td>
                                <td>Jr</td>
                                <td>281-777-1020</td>
                                <td>123 Anywhere Street Houston</td>
                            </tr>
                            <tr>
                               <td>2016-02-21</td>
                                <td>TX 1234</td>
                                <td>forfeit</td>
                                <td>Giaomo</td>
                                <td>Vinnie</td>
                                <td>Jr</td>
                                <td>281-777-1020</td>
                                <td>123 Anywhere Street Houston</td>
                            </tr>
                             
                             
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="closed">
                         <table class="table table-striped table-bordered">
                            <tr>
                                <th>Date</th>
                                <th>Court</th>
                                <th>Status</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Suffix</th>
                                <th>Phone</th>
                                <th>Address</th>
                            </tr>
                            <tr>
                                <td>2016-02-22</td>
                                <td>TX 1234</td>
                                <td><a href="<?php echo site_url("newcase/defendentdetails"); ?>" class="btn btn-success">open</a> </td>
                                <td>Giaomo</td>
                                <td>Vinnie</td>
                                <td>Jr</td>
                                <td>281-777-1020</td>
                                <td>123 Anywhere Street Houston</td>
                            </tr>
                            <tr>
                               <td>2016-02-23</td>
                                <td>TX 1234</td>
                                <td>forfeit</td>
                                <td>Giaomo</td>
                                <td>Vinnie</td>
                                <td>Jr</td>
                                <td>281-777-1020</td>
                                <td>123 Anywhere Street Houston</td>
                            </tr>
                             
                             
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="all">
                         <table class="table table-striped table-bordered">
                            <tr>
                                <th>Date</th>
                                <th>Court</th>
                                <th>Status</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Suffix</th>
                                <th>Phone</th>
                                <th>Address</th>
                            </tr>
                            <tr>
                                <td>2016-02-22</td>
                                <td>TX 1234</td>
                                <td><a href="<?php echo site_url("newcase/defendentdetails"); ?>" class="btn btn-success">open</a> </td>
                                <td>Giaomo</td>
                                <td>Vinnie</td>
                                <td>Jr</td>
                                <td>281-777-1020</td>
                                <td>123 Anywhere Street Houston</td>
                            </tr>
                            <tr>
                               <td>2016-02-23</td>
                                <td>TX 1234</td>
                                <td>forfeit</td>
                                <td>Giaomo</td>
                                <td>Vinnie</td>
                                <td>Jr</td>
                                <td>281-777-1020</td>
                                <td>123 Anywhere Street Houston</td>
                            </tr>
                             
                             
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
 