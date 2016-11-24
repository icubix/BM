<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Print_Buttons extends Domain_Presentation {
    function echoFields($location = 'document') {
        if (!$this->counterpart) {
            $this->counterpart = new Domain();
        }
        #for reference pages are ['application', 'indemnitor', 'receipt', 'bailbond', 'power']
        echo '            <div class="row" style="margin-left:4.17%">' . "\n";
        echo '              <table class="tableborder">' . "\n";
        echo '                <tr>' . "\n";
        echo '                  <td rowspan="2" class="tableborder col-sm-3 col-md-1">' . "\n";
        echo '                    <input type="checkbox" id="group1" onclick="groupCheckToggle(1)">' . "\n";
        echo '                  </td>' . "\n";
        echo '                  <td class="tableborder floatcenter">' . "\n";
        echo '                    <input type="checkbox" id="application" onclick="individualUnchecked(this, 1)">' . "\n";
        echo '                  </td>' . "\n";
        echo '                  <td class="tableborder col-sm-3 col-md-11">' . "\n";
        echo '                    <div class="col-sm-3 col-md-4">' . "\n";
        echo '                      <a type="button" class="btn" target="_blank"' . "\n";
        echo '                          href="' . base_url() . 'document/printDocument/100000/">' . "\n";
        echo '                          Application</a>' . "\n";
        echo '                    </div>' . "\n";
        echo '                  </td>' . "\n";
        echo '                </tr>' . "\n";
        echo '                <tr>' . "\n";
        echo '                  <td class="tableborder floatcenter">' . "\n";
        echo '                    <input type="checkbox" id="indemnitor" onclick="individualUnchecked(this, 1)">' . "\n";
        echo '                  </td>' . "\n";
        echo '                  <td class="tableborder col-sm-3 col-md-11">' . "\n";
        echo '                    <div class="col-sm-3 col-md-4">' . "\n";
        echo '                      <span id="printIndemnitor" onclick="myNewTab(\'010000\')"' . "\n";
        echo '                                type="submit" class="btn"' . "\n";
        echo '                                name="submitForm" value="printIndemnitor">' . "\n";
        echo '                        Indemnitor Promises</span>' . "\n";
        echo '                    </div>' . "\n";
        echo '                    <div class="col-sm-3 col-md-8">' . "\n";
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        $default = '0';
        if (count($selectLists['indemnitorList']) > 1) {
            $default = $selectLists['indemnitorList'][1]['key'];
        }
        #TODO: fix hacky id
        $this->echoSelect($location, 'printIndemnitorSelect', $selectLists['indemnitorList'], $default, 'col-sm-2 col-md-12" id="indemnitorSelect');
        echo '                    </div>' . "\n";
        echo '                  </td>' . "\n";
        echo '                </tr>' . "\n";
        echo '                <tr>' . "\n";
        echo '                  <td rowspan="2" class="tableborder col-sm-3 col-md-1">' . "\n";
        echo '                    <input type="checkbox" id="group2" onclick="groupCheckToggle(2)">' . "\n";
        echo '                  </td>' . "\n";
        echo '                  <td class="tableborder floatcenter">' . "\n";
        echo '                    <input type="checkbox" id="receipt" onclick="individualUnchecked(this, 2)">' . "\n";
        echo '                  </td>' . "\n";
        echo '                  <td class="tableborder col-sm-3 col-md-11">' . "\n";
        echo '                    <div class="col-sm-3 col-md-4">' . "\n";
        echo '                      <a type="button" class="btn" target="_blank"' . "\n";
        echo '                          href=' . base_url() . 'document/printDocument/001000/>' . "\n";
        echo '                          Receipt</a>' . "\n";
        echo '                    </div>' . "\n";
        echo '                  </td>' . "\n";
        echo '                </tr>' . "\n";
        echo '                <tr>' . "\n";
        echo '                  <td class="tableborder floatcenter">' . "\n";
        echo '                    <input type="checkbox" id="payment" onclick="individualUnchecked(this, 2)">' . "\n";
        echo '                  </td>' . "\n";
        echo '                  <td class="tableborder col-sm-3 col-md-11">' . "\n";
        echo '                    <div class="col-sm-3 col-md-4">' . "\n";
        echo '                      <span id="printIndemnitor" onclick="myNewTab(\'000100\')"' . "\n";
        echo '                                type="submit" class="btn"' . "\n";
        echo '                                name="submitForm" value="printPaymentPlan">' . "\n";
        echo '                        Payment Plan Agreement</span>' . "\n";
        echo '                    </div>' . "\n";
        echo '                  </td>' . "\n";
        echo '                </tr>' . "\n";
        echo '                <tr>' . "\n";
        echo '                  <td rowspan="2" class="tableborder col-sm-3 col-md-1">' . "\n";
        echo '                    <input type="checkbox" id="group3" onclick="groupCheckToggle(3)">' . "\n";
        echo '                  </td>' . "\n";
        echo '                  <td class="tableborder floatcenter">' . "\n";
        echo '                    <input type="checkbox" id="bailbond" onclick="individualUnchecked(this, 3)">' . "\n";
        echo '                  </td>' . "\n";
        echo '                  <td class="tableborder col-sm-3 col-md-11">' . "\n";
        echo '                    <div class="col-sm-3 col-md-4">' . "\n";
        echo '                      <a type="button" class="btn" target="_blank"' . "\n";
        echo '                          href=' . base_url() . 'document/printDocument/000010/>' . "\n";
        echo '                          Placeholder Bailbond</a>' . "\n";
        echo '                    </div>' . "\n";
        echo '                  </td>' . "\n";
        echo '                </tr>' . "\n";
        echo '                <tr>' . "\n";
        echo '                  <td class="tableborder floatcenter">' . "\n";
        echo '                    <input type="checkbox" id="power" onclick="individualUnchecked(this, 3)">' . "\n";
        echo '                  </td>' . "\n";
        echo '                  <td class="tableborder col-sm-3 col-md-11">' . "\n";
        echo '                    <div class="col-sm-3 col-md-4">' . "\n";
        echo '                      <span id="printPower" onclick="myNewTab(\'000001\')"' . "\n";
        echo '                                type="submit" class="btn"' . "\n";
        echo '                                name="submitForm" value="printPower">' . "\n";
        echo '                        Power</span>' . "\n";
        echo '                    </div>' . "\n";
        echo '                    <div class="col-sm-3 col-md-8">' . "\n";
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        $default = '0';
        if (count($selectLists['powerList']) > 1) {
            $default = $selectLists['powerList'][1]['key'];
        }
        #TODO: fix hacky id
        $this->echoSelect($location, 'printPowerSelect', $selectLists['powerList'], $default, 'col-sm-2 col-md-8" id="powerSelect');
        echo '                    </div>' . "\n";
        echo '                  </td>' . "\n";
        echo '                </tr>' . "\n";
        echo '              </table>' . "\n";
        echo '             <br><button onclick="newTabForAll()"' . "\n";
        echo '                         type="submit" class="btn btn-primary"' . "\n";
        echo '                         name="submitForm" value="printPower">' . "\n";
        echo '                <span class="glyphicon glyphicon-print"></span>' . "\n";
        echo '                Print Checked</button>' . "\n";
        echo '            </div>' . "\n";
        /*echo '            <div class="row">' . "\n";
        echo '                <div class="col-sm-3 col-md-11">' . "\n";
        echo '                    <a type="button" class="btn btn-primary"' . "\n";
        echo '                        href=' . base_url() . 'document/printIndemnitor>' . "\n";
        echo '                        <span class="glyphicon glyphicon-print"></span>' . "\n";
        echo '                        Print Indemnitor Promises</a>' . "\n";
        echo '                </div>' . "\n";
        echo '            </div>' . "\n";*/
    }
}

/* End of file print_buttons.php */
/* Location: ./application/libraries/print_buttons.php */