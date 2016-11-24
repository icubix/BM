<br>
<div class="col-md-10" onload="myFunction()">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Defendent</h3></div>
        <div class="panel-body">
            <div class="container-fluid">
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3 ">
                            <p>
                                <img src="template/images/star.png" width="104" height="95" alt="">
                            </p>
                        </div>
                        <div class="col-md-5 text-justify">
                            <h4>Vinnie Giacomo<br>
  123 Anywhere Street, Texas 22185<br> 281-781-7770</h4>
                        </div>
                        <div class="col-md-4 text-right ">
                            <label>2016-06-20</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="col-md-12 text-center">
                            <p>
                                <img src="template/images/default_profile.png" width="150px" height="150px" class="img-rounded"></p>
                        </div>
                        <div class="col-md-12 text-center ">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-sm">
                                Update
                            </button>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="btn-group col-md-12" data-toggle="buttons">
                            <label class="btn btn-success col-md-2">
                                <input type="checkbox" id="defendantTab"> Defendant
                            </label>
                            <label class="btn btn-warning col-md-2">
                                <input type="checkbox" id="indemnitorsTab"> Indemnitors
                            </label>
                            <label class="btn btn-default col-md-1">
                                <input type="checkbox" id="courtsTab"> Courts
                            </label>
                            <label class="btn btn-default col-md-2">
                                <input type="checkbox" id="powersTab"> Powers
                            </label>
                            <label class="btn btn-default col-md-2">
                                <input type="checkbox" id="paymentsTab"> Payments
                            </label>
                            <label class="btn btn-default col-md-2">
                                <input type="checkbox" id="documentsTab"> Documents
                            </label>
                            <label class="btn btn-success col-md-1">
                                <input type="checkbox" id="logFileTab"> Log File
                            </label>
                        </div>
                        <br>
                        <br>
                        <table class="table table-striped table-bordered table-fixed">
                            <thead>
                                <tr>
                                    <th width="10%">Rank</th>
                                    <th width="10%">Done</th>
                                    <th width="10%">Milestone</th>
                                    <th width="15%">Phase</th>
                                    <th width="15%">Date</th>
                                    <th width="40">To Do Next</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> 14</td>
                                    <td class="text-center">
                                        <input type="checkbox"> </td>
                                    <td> </td>
                                    <td> Indemnitor</td>
                                    <td>2015-04-20 </td>
                                    <td>Get a Second Indemnitor Paper Work </td>
                                </tr>
                                <tr>
                                    <td> 18</td>
                                    <td class="text-center">
                                        <input type="checkbox"> </td>
                                    <td> </td>
                                    <td> Indemnitor</td>
                                    <td> </td>
                                    <td>Mark Indemnitor Phase Complete </td>
                                </tr>
                                <tr>
                                    <td>20 </td>
                                    <td class="text-center">
                                        <input type="checkbox"> </td>
                                    <td> </td>
                                    <td> Indemnitor</td>
                                    <td> </td>
                                    <td> Case Complete Close-it</td>
                                </tr>
                                <tr>
                                    <td>22 </td>
                                    <td class="text-center">
                                        <input type="checkbox"> </td>
                                    <td class="text-center">
                                        <input type="radio" name="optionsRadios"> </td>
                                    <td> Bond</td>
                                    <td> </td>
                                    <td>Issue power and bond to spring Vinnie </td>
                                </tr>
                                <tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="panel panel-default" id="defendantPanel">
        <div class="panel-heading">
            <h3> Defendent Details</h3></div>
        <div class="panel-body">
            <div class="container-fluid">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#contactinfo" aria-controls="contactinfo" role="tab" data-toggle="tab">Contact Info</a></li>
                    <li role="presentation"><a href="#personalinfo" aria-controls="personalinfo" role="tab" data-toggle="tab">Personal Info</a></li>
                    <li role="presentation"><a href="#employment" aria-controls="employment" role="tab" data-toggle="tab">Employment</a></li>
                    <li role="presentation"><a href="#creditauto" aria-controls="creditauto" role="tab" data-toggle="tab">Credit/Auto</a></li>
                    <li role="presentation"><a href="#spouse" aria-controls="spouse" role="tab" data-toggle="tab">Spouse/SO</a></li>
                    <li role="presentation"><a href="#children" aria-controls="children" role="tab" data-toggle="tab">Children</a></li>
                    <li role="presentation"><a href="#references" aria-controls="references" role="tab" data-toggle="tab">References</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="contactinfo">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Defendent</th>
                                <th>Description</th>
                                <th>Defendent</th>
                                <th>Description</th>
                            </tr>
                            <tr>
                                <td>First Name</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Enter First Name">
                                </td>
                                <td>Address 1</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Enter Address 1">
                                </td>
                            </tr>
                            <tr>
                                <td>Middle Name</td>
                                <td>
                                    <input type="email" class="form-control" placeholder="Enter Middle Name">
                                </td>
                                <td>Address 2</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Enter Address 2">
                                </td>
                            </tr>
                            <tr>
                                <td>Last Name</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Enter Last Name">
                                </td>
                                <td>City</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Enter City">
                                </td>
                            </tr>
                            <tr>
                                <td>Suffix</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Email">
                                </td>
                                <td>State</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Enter State">
                                </td>
                            </tr>
                            <tr>
                                <td>Work Phone</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Enter work phone">
                                </td>
                                <td>Zip Code</td>
                                <td>
                                    <input type="number" class="form-control" placeholder="Enter Zip Code">
                                </td>
                            </tr>
                            <tr>
                                <td>Home Phone</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Enter Home Phone">
                                </td>
                                <td>Rent/Own</td>
                                <td>
                                    <input type="number" class="form-control" placeholder="Enter Rent/own details">
                                </td>
                            </tr>
                            <tr>
                                <td>Email 1</td>
                                <td>
                                    <input type="email" class="form-control" placeholder="Enter Email 1">
                                </td>
                                <td>Landlord/Mortgage</td>
                                <td>
                                    <input type="number" class="form-control" placeholder="Enter ">
                                </td>
                            </tr>
                            <tr>
                                <td>Email 2</td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Enter Email 2">
                                </td>
                                <td></td>
                                <td>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="personalinfo">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Legal IDs</th>
                                <th>Description</th>
                                <th>Background Info</th>
                                <th>Description</th>
                            </tr>
                            <tr>
                                <td>SSN</td>
                                <td>123-456-789</td>
                                <td>Date of Birth</td>
                                <td>1970-10-31</td>
                            </tr>
                            <tr>
                                <td>Drivers License</td>
                                <td>DL 9876</td>
                                <td>Place of Birth</td>
                                <td>Houston</td>
                            </tr>
                            <tr>
                                <td>Drivers State</td>
                                <td>Texas</td>
                                <td>Offendes Previous</td>
                                <td>GTAuto Robbery Assault</td>
                            </tr>
                            <tr>
                                <td>Divers Expiry</td>
                                <td>2017-10-31</td>
                                <td>Offences Locations</td>
                                <td>Houston Galveston LA</td>
                            </tr>
                            <tr>
                                <td>Passport Country</td>
                                <td>USA</td>
                                <td>Probation Active?</td>
                                <td>Yes</td>
                            </tr>
                            <tr>
                                <td>Passport ID</td>
                                <td>US P789996S</td>
                                <td>Probation Officer</td>
                                <td>Hulk Hogon</td>
                            </tr>
                            <tr>
                                <td>Passport Expiry</td>
                                <td>2017-10-31</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="employment">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Job 1</th>
                                <th>Description</th>
                                <th>Job 2</th>
                                <th>Description</th>
                            </tr>
                            <tr>
                                <td>Employer</td>
                                <td>Dunkin Donuts</td>
                                <td>Employer 2</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Employer Phone</td>
                                <td>281-781-7000</td>
                                <td>Employer Phone</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Job Title</td>
                                <td>Baker</td>
                                <td>Job Titles</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Job Start</td>
                                <td>2013-07-24</td>
                                <td>Job Start</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Job Duration</td>
                                <td>2.5 Years</td>
                                <td>Job Duration</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Job Location</td>
                                <td>345 Food Fair Plaza</td>
                                <td>Job Location</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Job City</td>
                                <td>Houston</td>
                                <td>>Job City</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Supervisor/Phone</td>
                                <td>Bill Jones/281-781-7000</td>
                                <td>Supervisor/Phone</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Notes</td>
                                <td>None</td>
                                <td>Notes</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="creditauto">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Vehicle 1</th>
                                <th>Description</th>
                                <th>Vehicle 2</th>
                                <th>Description</th>
                            </tr>
                            <tr>
                                <td>Year</td>
                                <td>2005</td>
                                <td>Year</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Make</td>
                                <td>Ford</td>
                                <td>Make</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Model</td>
                                <td>F150</td>
                                <td>Model</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Color</td>
                                <td>Black</td>
                                <td>Color</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Tags</td>
                                <td>ABC 578S</td>
                                <td>Tags</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>State</td>
                                <td>TX</td>
                                <td>State</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Owned</td>
                                <td>2000</td>
                                <td>Owned</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Lien Holder</td>
                                <td>Big Ford Trucks</td>
                                <td>Lien Holder</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="spouse">Spouse/SO</div>
                    <div role="tabpanel" class="tab-pane" id="children">Children</div>
                    <div role="tabpanel" class="tab-pane" id="references">References</div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default" id="cpbcpdlPanel">
        <div class="panel-heading">
            <h3> Courts - Powers - Bonds - Checkins - Payments - Documents - Log File</h3></div>
        <div class="panel-body">
            <div class="container-fluid">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" id="courtsli"><a href="#courts" aria-controls="courts" role="tab" data-toggle="tab">Courts</a></li>
                    <li role="presentation" id="powersli"><a href="#powers" aria-controls="powers" role="tab" data-toggle="tab">Powers</a></li>
                    <li role="presentation" id="bondsli"><a href="#bonds" aria-controls="bonds" role="tab" data-toggle="tab">Bonds</a></li>
                    <li role="presentation" id="checkinsli"> <a href="#checkins" aria-controls="checkins" role="tab" data-toggle="tab">Checkins</a></li>
                    <li role="presentation" id="paymentsli"><a href="#payments" aria-controls="payments" role="tab" data-toggle="tab">Payments</a></li>
                    <li role="presentation" id="documentsli"><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Documents</a></li>
                    <li role="presentation" id="logfileli"><a href="#logfile" aria-controls="logfile" role="tab" data-toggle="tab">log File</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="courts">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Date</th>
                                <th>Court</th>
                                <th>Power</th>
                                <th>Amount</th>
                                <th>Bond</th>
                                <th>Comment</th>
                            </tr>
                            <tr>
                                <td>2016-06-15</td>
                                <td>TX 1234 </td>
                                <td>P12004</td>
                                <td>2000</td>
                                <td>BD2456</td>
                                <td>Felony</td>
                            </tr>
                            <tr>
                                <td>2016-06-16</td>
                                <td>TX 1234 </td>
                                <td>P12698</td>
                                <td>2000</td>
                                <td>BD2457</td>
                                <td>Assault</td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="powers">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Date</th>
                                <th>Court</th>
                                <th>Power</th>
                                <th>Amount</th>
                                <th>Bond</th>
                                <th>Comment</th>
                            </tr>
                            <tr>
                                <td>2016-06-15</td>
                                <td>TX 1234 </td>
                                <td>P12004</td>
                                <td>2000</td>
                                <td>BD2456</td>
                                <td>Felony</td>
                            </tr>
                            <tr>
                                <td>2016-06-16</td>
                                <td>TX 1234 </td>
                                <td>P12698</td>
                                <td>2000</td>
                                <td>BD2457</td>
                                <td>Assault</td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="bonds">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Date</th>
                                <th>Court</th>
                                <th>Power</th>
                                <th>Amount</th>
                                <th>Bond</th>
                                <th>Bond Details</th>
                            </tr>
                            <tr>
                                <td>2016-06-15</td>
                                <td>TX 1234 </td>
                                <td>P12004</td>
                                <td>2000</td>
                                <td>BD2456</td>
                                <td>Bond DetailsS</td>
                            </tr>
                            <tr>
                                <td>2016-06-16</td>
                                <td>TX 1234 </td>
                                <td>P12698</td>
                                <td>2000</td>
                                <td>BD2457</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="checkins">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Date</th>
                                <th>Checkin Type</th>
                                <th>Comment</th>
                            </tr>
                            <tr>
                                <td>2016-02-20</td>
                                <td>In-Person </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2016-02-27</td>
                                <td>Phone </td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="payments">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Date</th>
                                <th>Owing</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Pay Plan</th>
                                <th>Comment</th>
                            </tr>
                            <tr>
                                <td>2016-02-20</td>
                                <td>1000</td>
                                <td>500</td>
                                <td>500</td>
                                <td>Monthly</td>
                                <td>B</td>
                            </tr>
                            <tr>
                                <td>2016-03-15</td>
                                <td>500 </td>
                                <td>250</td>
                                <td>250</td>
                                <td>Monthly</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="documents">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Serial</th>
                                <th>Document</th>
                                <th>Comment</th>
                            </tr>
                            <tr>
                                <td>2016-02-20</td>
                                <td>Court</td>
                                <td>1234</td>
                                <td>Jail Doc</td>
                                <td>For the application</td>
                            </tr>
                            <tr>
                                <td>2016-03-20</td>
                                <td>Drivers </td>
                                <td>TX1234</td>
                                <td>Indemnitor 1</td>
                                <td>Bill Jones</td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="logfile">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Date Ime</th>
                                <th>Who</th>
                                <th>Phase</th>
                                <th>Workflow</th>
                                <th>Comment</th>
                            </tr>
                            <tr>
                                <td>2016-02-20 0900</td>
                                <td>Joe</td>
                                <td>Defendant</td>
                                <td>Created new defendant</td>
                                <td>Giacomo</td>
                            </tr>
                            <tr>
                                <td>2016-02-20 0910</td>
                                <td>Joe</td>
                                <td>Defendant</td>
                                <td>Add contact info</td>
                                <td>Giacomo</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default" id="indemnitorsPanel">
        <div class="panel-heading">
            <h3> Indemnitors</h3></div>
        <div class="panel-body">
            <div class="container-fluid">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#indemnitor1" aria-controls="indemnitor1" role="tab" data-toggle="tab">Indemnitor 1</a></li>
                    <li role="presentation"><a href="#indemnitor2" aria-controls="indemnitor2" role="tab" data-toggle="tab">Indemnitor 2</a></li>
                    <li role="presentation"><a href="#indemnitor3" aria-controls="indemnitor3" role="tab" data-toggle="tab">Indemnitor 3</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="indemnitor1">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Indemnitor</th>
                                <th>Description</th>
                                <th>Indemnitor</th>
                                <th>Description</th>
                            </tr>
                            <tr>
                                <td>First Name</td>
                                <td>Frank</td>
                                <td>Address 1</td>
                                <td>123 AnyWhere Street</td>
                            </tr>
                            <tr>
                                <td>Middle Name</td>
                                <td>J</td>
                                <td>City</td>
                                <td>Houston</td>
                            </tr>
                            <tr>
                                <td>Last Name</td>
                                <td>Baxter</td>
                                <td>State</td>
                                <td>TX</td>
                            </tr>
                            <tr>
                                <td>Suffix</td>
                                <td></td>
                                <td>ZipCode</td>
                                <td>22185</td>
                            </tr>
                            <tr>
                                <td>Primary Phone</td>
                                <td>281-781-7770</td>
                                <td>Employer</td>
                                <td>JP Morgan</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>fbaxter@gmail.com</td>
                                <td>Work Phone</td>
                                <td>Bill Jones 123-281-1234</td>
                            </tr>
                            <tr>
                                <td>Date of Birth</td>
                                <td>1980-02-24</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>SSN</td>
                                <td>788-789-780</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Drivers License</td>
                                <td>TX 23890</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="indemnitor2">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Indemnitor</th>
                                <th>Description</th>
                                <th>Indemnitor</th>
                                <th>Description</th>
                            </tr>
                            <tr>
                                <td>First Name</td>
                                <td>Joe</td>
                                <td>Address 1</td>
                                <td>123 AnyWhere Street</td>
                            </tr>
                            <tr>
                                <td>Middle Name</td>
                                <td>J</td>
                                <td>City</td>
                                <td>Houston</td>
                            </tr>
                            <tr>
                                <td>Last Name</td>
                                <td>Baxter</td>
                                <td>State</td>
                                <td>TX</td>
                            </tr>
                            <tr>
                                <td>Suffix</td>
                                <td></td>
                                <td>ZipCode</td>
                                <td>22185</td>
                            </tr>
                            <tr>
                                <td>Primary Phone</td>
                                <td>281-781-7770</td>
                                <td>Employer</td>
                                <td>JP Morgan</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>fbaxter@gmail.com</td>
                                <td>Work Phone</td>
                                <td>Bill Jones 123-281-1234</td>
                            </tr>
                            <tr>
                                <td>Date of Birth</td>
                                <td>1980-02-24</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>SSN</td>
                                <td>788-789-780</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Drivers License</td>
                                <td>TX 23890</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="indemnitor3">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Indemnitor</th>
                                <th>Description</th>
                                <th>Indemnitor</th>
                                <th>Description</th>
                            </tr>
                            <tr>
                                <td>First Name</td>
                                <td>Jae</td>
                                <td>Address 1</td>
                                <td>123 AnyWhere Street</td>
                            </tr>
                            <tr>
                                <td>Middle Name</td>
                                <td>J</td>
                                <td>City</td>
                                <td>Houston</td>
                            </tr>
                            <tr>
                                <td>Last Name</td>
                                <td>Baxter</td>
                                <td>State</td>
                                <td>TX</td>
                            </tr>
                            <tr>
                                <td>Suffix</td>
                                <td></td>
                                <td>ZipCode</td>
                                <td>22185</td>
                            </tr>
                            <tr>
                                <td>Primary Phone</td>
                                <td>281-781-7770</td>
                                <td>Employer</td>
                                <td>JP Morgan</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>fbaxter@gmail.com</td>
                                <td>Work Phone</td>
                                <td>Bill Jones 123-281-1234</td>
                            </tr>
                            <tr>
                                <td>Date of Birth</td>
                                <td>1980-02-24</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>SSN</td>
                                <td>788-789-780</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Drivers License</td>
                                <td>TX 23890</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function myFunction() {
    // $('#defendantPanel').show();
    // $('#cpbcpdlPanel').hide();
    // $('#indemnitorsPanel').hide();


}
$(document).ready(function() {
    $('#defendantPanel').show();
    $('#cpbcpdlPanel').hide();
    $('#indemnitorsPanel').hide();

    $("#defendantTab").click(function() {
        $('#defendantPanel').show();
        $('#cpbcpdlPanel').hide();
        $('#indemnitorsPanel').hide();
    });

    $("#indemnitorsTab").click(function() {
        $('#defendantPanel').hide();
        $('#cpbcpdlPanel').hide();
        $('#indemnitorsPanel').show();
    });

    $("#courtsTab").click(function() {
        //$('#cpbcpdlPanel .nav-tabs a[href="#courts"]').tab('show');
        //$('.nav-tabs a:first').tabs('show') 
        $('#defendantPanel').hide();
        $('#cpbcpdlPanel').show();
        $('#indemnitorsPanel').hide();
    });
    $("#powersTab").click(function() {
        $('#defendantPanel').hide();
        $('#cpbcpdlPanel').show();
        $('#indemnitorsPanel').hide();
    });
    $("#paymentsTab").click(function() {
        $('#defendantPanel').hide();
        $('#cpbcpdlPanel').show();
        $('#indemnitorsPanel').hide();
    });
    $("#documentsTab").click(function() {
        $('#defendantPanel').hide();
        $('#cpbcpdlPanel').show();
        $('#indemnitorsPanel').hide();
    });
    $("#logFileTab").click(function() {
        $('#defendantPanel').hide();
        $('#cpbcpdlPanel').show();
        $('#indemnitorsPanel').hide();
    });


});
// $(this).siblings('employment').removeClass('active');
//$(this).siblings('employment').addClass('active');
//$("#tabs").tabs("option", "selected", selected + 1);
// $(".nexttab").click(function() {
//     var selected = $("#tabs").tabs("option", "selected");
//     $("#tabs").tabs("option", "selected", selected + 1);
// });
</script>
