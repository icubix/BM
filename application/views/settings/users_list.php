<!-- User List -->
   <div class="container">
      <div class="row">
         <div class="col-sm-4 col-md-3">
            <h1 class="text-left defendant-title">Users</h1>
         </div>
         <div class="col-sm-4 col-md-4">
            <?php if (isset($update_message)) { echo($update_message); } ?>
         </div>
         <div class="col-sm-4 col-md-3 pull-right">
               <h1 class="text-right defendant-title">
               <a href=<?php echo site_url("settings/addNewUser"); ?> class="btn btn-default btn-s">
                   <img src="<?php echo base_url() ?>images/glyphicons_190_circle_plus.png" alt="">
                   Add User</a>
               </h1>
         </div>
      </div>
   </div>
   <div class="col-sm-4 col-md-10 col-md-offset-1">

   <script src="<?php echo base_url(); ?>js/i18n/grid.locale-en.js" type="text/javascript"></script>
   <script src="<?php echo base_url(); ?>js/jquery.jqGrid.src.js" type="text/javascript"></script>
   <script src="<?php echo base_url(); ?>js/jquery-ui-1.10.4.custom.js" type="text/javascript"></script>

    <script type="text/javascript">

        var baseurl = '<?php echo base_url(); ?>';

        jQuery(document).ready(function() {
        $("#users_jqGrid").jqGrid({
            url: baseurl + 'settings/browseUsers',
            datatype: 'json',
            mtype: 'get',
            loadError: function (jqXHR, textStatus, errorThrown) {
                alert('HTTP status code: ' + jqXHR.status + '\n' +
                    'textStatus: ' + textStatus + '\n' +
                    'errorThrown: ' + errorThrown);
                alert('HTTP message body (jqXHR.responseText): ' + '\n' + jqXHR.responseText);
            },
            //loadError: function(xhr,status,error){alert(status+" "+error);},
            ajaxGridOptions: { contentType: "application/json; charset=utf-8" },
            colNames:['Email', 'Level', 'Role', 'Role Description', 'Name'],
            colModel :[
                {name:'electronic_address',       index:'electronic_address',      width:60, search:true, stype:'text' },
                {name:'user_level',      index:'user_level',     width:40, search:true, stype:'text' },
                {name:'user_role',          index:'user_role',         width:40, search:true, stype:'text' },
                {name:'description',     index:'description',    width:80, search:true, stype:'text' },
                {name:'name',  index:'name', width:50, search:true, stype:'text' }
            ],
            pager: jQuery('#users_pager'),
            autowidth: true,
            height: "100%",
            rowNum: 10,
            rowList:[10, 20, 40],
            sortname: 'party_role_id',
            sortorder: 'asc',
            viewrecords: true,
            gridview: false,
            height: "auto",
            caption: 'Users',
            ondblClickRow: function(party_role_id){ self.location=baseurl + 'settings/loadUserToView/' + party_role_id; },
            errorCell : function(){
                alert(triggered)
                $('#message').text('An error has occurred while processing your request. Please check the manual for more information');
            }
        });
        $('#users_jqGrid').jqGrid('filterToolbar', { searchOnEnter: true, enableClear: true });

        $('#users_jqGrid').jqGrid( 'navGrid', '#defendant_pager', { add:false, edit: false, view: false, refresh:true, del:false, search:false } );

        $('#users_jqGrid').jqGrid ('navButtonAdd', '#users_pager', { caption: "", buttonicon: "ui-icon-pencil", title: "Edit Selected Defendant",
            onClickButton: function() { var rowid = $('#users_jqGrid').jqGrid('getGridParam', 'selrow');
                if ( rowid ) { self.location=baseurl + 'settings/loadUserToView/' + rowid; }
                else { alert( 'Please Select an Defendant' ) };
                } } );

        $('#users_jqGrid').jqGrid ('navButtonAdd', '#users_pager', { caption: "", buttonicon: "ui-icon-plus", title: "Add New Defendant",
                    onClickButton: function() { self.location=baseurl + 'settings/addUser/' + segment; } } );
        $('#users_jqGrid').jqGrid ('navButtonAdd', '#users_pager', { caption: "", buttonicon: "ui-icon-trash", title: "Set Defendant inactive",
            onClickButton: function() { var rowid = $('#users_jqGrid').jqGrid('getGridParam', 'selrow');
                if ( rowid ) { self.location=baseurl + 'settings/setactive/' + rowid + '/0'; }
                else { alert( 'Please Select a Row' ) };
                } } );
        });
    </script>

    <div id="grid">
        <table id="users_jqGrid"> <tr><td></td></tr> </table>
        <div id="users_pager"></div>
    </div>
    </div>