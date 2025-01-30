<!DOCTYPE html>
<html lang="es">
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="" />
        <meta name="author" content="Jonathan" />     
        
        <title>Schema codeigniter</title>
        
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="<?= $this->libschema->getTemplate();?>">
            
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
         
        <script type="text/javascript">
            $.base_url = function( url ){return '<?= base_url();?>' + url };
            $.site_url = function( url ){return '<?= site_url();?>/' + url };
        </script>
        
    </head> 

    <body cz-shortcut-listen="true">
        <!-- Static navbar -->
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Schema CI</a>
                </div>
                <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active">    
                        <a href="#">
                        <i class="glyphicon glyphicon-indent-right"></i>
                        Information schema</a>
                    </li>
                </ul>     
                <ul class="nav navbar-nav navbar-right">   
                    <li><a style="font-weight:bold;" href="#" data-target="#help" data-toggle="modal"> Demo JSON </a></li>
                    <li><a style="font-weight:bold;" href="#"><?= $user?></a></li>
                    <li><a  href="<?= site_url('app-admin/schema/logout');?>">Logout</a></li>
                </ul>       
              </div>
            </div>
        </nav>  
        
        <div class="container"> 
            <div class="row">
                <div class="col-lg-4">
                    <table class="table">
                        <caption>List schema pending</caption>
                        <thead>
                            <tr>
                                <th >Name</th>
                                <th >
                                    <button type="button" id="migrate-all" class="btn btn-warning btn-xs">Migrate all</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>     
                            <?php foreach ($list_schemas as $name => $file) : ?>
                                <tr>
                                    <td><?= $name ?></td>
                                    <td> <button type="button" class="btn btn-success btn-xs migrate-schema" data-name="<?= $name?>" title="generate migration schema"> 
                                        migrate <i class="glyphicon glyphicon-record"></i>
                                    </button></td>
                                </tr>   
                            <?php  endforeach; ?>
                            
                        </tbody>
                    </table>
                    <h4>Remember Back up database (ssh)</h4>
                    <div class="bg-danger text-light p-2" >
                        mysqldump -h localhost -u  <?= $username?> -p <?= $database?> --single-transaction --quick --lock-tables=false > db_<?= $database?>_$(date +%F).sql
                    </div>
                </div>
                <div class="col-lg-8">
                   <table class="table">
                        <caption>List schema migrated </caption>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody id="add-new-schema"> 

                            <?php   

                                foreach ($list_schemas_migrated as $key => $value) : 
                                    if( isset($last_modify[$value->name])){
                                        $class= ($last_modify[$value->name]!=$value->last_modify) ? ' btn-danger' : 'btn-warning ';    
                                    }else{
                                        $class= 'btn-default';
                                    }
                            ?>
                                <tr>    
                                    <td><?= $value->name?></td>
                                    <td><span id="date-<?= $value->name ?>"><?= $value->date?></span></td>
                                    <td><?= $value->user?></td>
                                    <td> <button type="button" class="btn <?= $class?> btn-xs migrate-schema" data-remigrate="1" data-name="<?= $value->name?>" title="generate migration schema"> 
                                        re-migrate <i class="glyphicon glyphicon-record"></i>
                                    </button></td>
                                </tr>
                            <?php  endforeach; ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal" id="help">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title">Help JSON, create file on: </h4>
                  <span style="font-size:14px;"><?= $this->libschema->getPath() ?></span>
                </div>      
                <div class="modal-body">
                    <div class="text-info" >        
                        <pre>
{"user":[
    {
        "name": "id",
        "type": "int",
        "constraint": 8,
        "unsigned": true,
        "auto_increment": true,
        "primary": true
    },
    {
        "name":"email",
        "type":"varchar",
        "constraint": 60,
        "null": false
    },
    {
        "name":"password",
        "type": "varchar",
        "constraint": 60,
        "null": false
    },
    {
        "name":"name",
        "type":"varchar(70)",
        "null":false,
        "default": ""
    },
    {
        "name":"company_id",
        "type":"int",
        "constraint": 8,
        "default": 0,
        "null": false,
        "_createindex" : true
    },
    {
        "name": "comments",
        "type": "text",
        "null": "true"
    },
    {
        "name": "status",
        "type": "smallint(1)",
        "null": false,
        "default": 1
    },
    {
        "name": "date_update",
        "type": "datetime",
        "null": false,
        "default" : "0000-00-00"
    }
]}
                        </pre>
                    </div>  
                </div>
                <div class="modal-footer">  
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
        </div>

        <div class="modal" id="messages">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title">Error</h4>
                </div>
                <div class="modal-body">
                    <div class="text-danger"  id="activeError" style="display:none;">
                        <h2>Error: </h2>
                        <div id="msg-error"></div>
                    </div>  
                    <div class="text-success" id="activeSuccess" style="display:none;">
                        <h2>Success: </h2>
                        <div id="msg-success"></div>
                    </div>
                </div>
                <div class="modal-footer">  
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
        </div>
    </body>

    <script >   
        $(document).ready(function(){   

            $("#migrate-all").on("click", function(){

                $(".migrate-schema").each(function(){
                    var url       = $.site_url('app-admin/schema/runmigration/'),
                    self      = this,
                    data      = {
                        name : $(this).data("name")
                    };

                    $(self).attr("disabled", "disabled");

                    $.get(url, data, function(response){

                        $(".modal-title").html("File: <b>" + data.name+".yml</b>");
                        $("#activeError,#activeSuccess").css('display','none');
                        if(response.status){        
                                    
                            var item = '<tr>';      
                            item+= '<td>'+response.schema_log.name+'</td>';
                            item+= '<td> <span id="date-'+response.schema_log.name+'">'+response.schema_log.date+'</span></td>';
                            item+= '<td>'+response.schema_log.user+'</td>';
                            item+= '<td> <button type="button" class="btn btn-warning btn-xs migrate-schema" data-remigrate="1" data-name="'+response.schema_log.name+'" title="generate migration schema"> re-migrate <i class="glyphicon glyphicon-record"></i></button></td>';
                            item+= '</tr>'; 
                            $("#add-new-schema").prepend(item);
                            $($(self).parent().parent()).remove();
                        
                            $("#activeSuccess").css('display','block');
                            $("#msg-success").html(response.message_success);
                      
                        } else{     
                            if(response.message_success!=''){
                                $("#activeSuccess").css('display','block');
                            }
                            $("#activeError").css('display','block');
                            $("#msg-error").html(response.msg);
                        }   

                        $("#messages").modal();

                    }, 'json');

                    $(self).removeAttr("disabled");  
                });
            });

            $("body").delegate( ".migrate-schema", "click", function() {
            //$('.migrate-schema').on("click",function(){
                var url       = $.site_url('app-admin/schema/runmigration/'),
                    self      = this,
                    remigrate = $(this).data("remigrate"),
                    data      = {
                        name : $(this).data("name")
                    };
                    
                if($(this).hasClass('btn-default')){
                    alert('This file not found');
                    return false;
                }  

                if(remigrate){
                    if(!confirm('Are you sure remigrate this file?')){
                        return false;
                    }   
                }

                $(self).attr("disabled", "disabled");
                
                $.get(url, data, function(response){
                    console.log(response);
                    $(".modal-title").html("File: <b>" + data.name+".yml</b>");
                    $("#activeError,#activeSuccess").css('display','none');
                    if(response.status){        
                        
                        if(remigrate){      
                            $("#date-"+response.schema_log.name).html(response.schema_log.date);
                            $(self).removeAttr("disabled");
                        } else{     
                            var item = '<tr>';      
                            item+= '<td>'+response.schema_log.name+'</td>';
                            item+= '<td> <span id="date-'+response.schema_log.name+'">'+response.schema_log.date+'</span></td>';
                            item+= '<td>'+response.schema_log.user+'</td>';
                            item+= '<td> <button type="button" class="btn btn-warning btn-xs migrate-schema" data-remigrate="1" data-name="'+response.schema_log.name+'" title="generate migration schema"> re-migrate <i class="glyphicon glyphicon-record"></i></button></td>';
                            item+= '</tr>'; 
                            $("#add-new-schema").prepend(item);
                            $($(self).parent().parent()).remove();
                        } 

                        $("#activeSuccess").css('display','block');
                        $("#msg-success").html(response.message_success);
                  
                    } else{     
                        if(response.message_success!=''){
                            $("#activeSuccess").css('display','block');
                        }
                        $("#activeError").css('display','block');
                        $("#msg-error").html(response.msg);
                    }   
                    $("#messages").modal();

                }, 'json');

               
                $(this).removeAttr("disabled");  
            });
        });
    </script>
<html> 
