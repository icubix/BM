<!--  Add Title -->
                    <div class="col-sm-3 col-md-4">
                        <h1 class="text-left defendant-title">
                            <a href=<?php echo site_url('settings/index') ?>>
                                Settings</a> \
                            <a href=<?php echo site_url('settings/listUsers') ?>>
                                Users</a> \ View
                        </h1>
                    </div>
                    <div class="col-sm-3 col-md-2">
                        <font color="red">
                        <?php echo $error; ?>
                        </font>
                    </div>
                    <div class="col-sm-3 col-md-4 col-md-offset-2" align="right" style="padding-top: 10px">
                        <a type="button" class="btn btn-primary"
                            href=<?php echo base_url() ?>settings/listUsers>
                            <span class="glyphicon glyphicon-remove-circle"></span>
                            Cancel</a>
                        <button type="submit" class="btn btn-primary" name="submitForm" value="formEdit">
                            <span class="glyphicon glyphicon-download-alt"></span>
                            Edit User</button>
                    </div>
                    <input type="hidden" name="isNew" value="False" />
                    <br>
