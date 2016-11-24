<!-- Settings: User Detail Screen -->
<div class="container">
    <?php if ($page_title == 'settings/user_view_title') {
            $viewEditOrNew = 'view';
            } else if ($page_title == 'settings/user_edit_title') {
            $viewEditOrNew = 'edit';
        } else {
            $viewEditOrNew = 'new';
    } ?>
    <form action="<?php echo base_url() ?>settings/<?php echo $viewEditOrNew . 'User/' . $this->uri->segment(3, ''); ?>"
            enctype="multipart/form-data" method="post">
      <div class="row">
        <?php   $page_data['error'] = $error;
                $this->load->view($page_title, $page_data); ?>
      </div>
      <div id="contentScroll" class="col-sm-3 col-md-12 inline-scroll hide-x">
        <div id="user_instance" class="row">
            <div class="col-sm-2 col-md-4">
            <h1 class="text-left defendant-title">
                User Information
            </h1>
            </div>
        </div>
        <?php $user_presentation =
                new User_Presentation($user_instance);
                if ($viewEditOrNew == 'view') {
                    $user_presentation->setReadOnlyFields(TRUE);
                }
                $user_presentation->echoFields('user_instance', $isSelf);
        ?>
        <div class="row">
            <button type="submit" class="btn btn-default btn-xs" name="submitForm"
                value="addEmail-user_instance-contacts"
                <?php if ($viewEditOrNew == 'view') { echo 'style="display: none"'; } ?> >
                <img src="<?php echo base_url() ?>images/glyphicons_190_circle_plus.png" alt="">
                Add Email</button>
            <button type="submit" class="btn btn-default btn-xs" name="submitForm"
                value="addPhone-user_instance-contacts"
                <?php if ($viewEditOrNew == 'view') { echo 'style="display: none"'; } ?> >
                <img src="<?php echo base_url() ?>images/glyphicons_190_circle_plus.png" alt="">
                Add Phone</button>
            <button type="submit" class="btn btn-default btn-xs" name="submitForm"
                value="addAddress-user_instance-contacts"
                <?php if ($viewEditOrNew == 'view') { echo 'style="display: none"'; } ?> >
                <img src="<?php echo base_url() ?>images/glyphicons_190_circle_plus.png" alt="">
                Add Postal Address</button>
        <hr class="hr-line"></hr>
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-md-4 col-sm-2">
                <button type="submit" class="btn btn-primary" name="submitForm" value="pwdEmail">
                    <span class="glyphicon glyphicon-download-alt"></span>
                    Send Password Reset Email</button>
            </div>
            <div class="col-md-4 col-sm-2">
                <div class="row">
                    <button type="submit" class="btn btn-primary" name="submitForm" value="attemptsReset">
                        <span class="glyphicon glyphicon-download-alt"></span>
                        Login Attemps Reset</button>
                </div>
                <div class="row">
                    <?php $user_presentation->echoWait('user_instance'); ?>
                </div>
            </div>
            <?php if ($viewEditOrNew == 'edit') {
                    if ($editPassword == 'Yes') { ?>
            <div class="col-md-4 col-sm-2">
                    <button type="submit" class="btn btn-primary" name="submitForm" value="formSave">
                        <span class="glyphicon glyphicon-download-alt"></span>
                        Save User</button>
            </div>
                <?php } elseif ($editPassword == 'No') { ?>
            <div class="col-md-4 col-sm-2">
                    <button type="submit" class="btn btn-primary" name="submitForm" value="pwdChange">
                        <span class="glyphicon glyphicon-download-alt"></span>
                        Change Password</button>
            </div>
                <?php } #else $editPassword == 'Not able' so don't show button
            } ?>
        </div>
      </div>
      <?php if ($editPassword == 'Yes') { ?>
<script type="text/javascript">
function onloadScroll() {
    document.getElementById("contentScroll").scrollTop =
            document.getElementById("pwdChange").offsetTop;
}
window.onload = onloadScroll;
</script>
      <div class="row" id="pwdChange">
          <div class="col-sm-2 col-md-4"><h4 class="text-left defendant-title">
              <b>Password Change</b>
          </h4></div>
              <?php $user_presentation->echoPassword($isSelf);
                  echo '      </div>' . "\n";
              } else {
                  echo '      <input type="hidden" name="passwordBoxShown" value="0" />';
              }
          ?>
      <div class="row">&nbsp;</div>
      <div class="row">&nbsp;</div>
      <div class="row">&nbsp;</div>
      <div class="row">&nbsp;</div>
    </div>
  </form>
</div>
