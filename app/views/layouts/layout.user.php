<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="Healhty" />
        <meta name="author" content="Jonathan Q" />
        
        <link rel="icon" type="image/png" href="/favicon.png" />
        
        <title><?php echo $config['title'] ?></title>
        
        <?php echo $config['css']; ?>
       	<style type="text/css">
            #loading
            {
                top              : 50px;
                position         : absolute;
                background-color : rgba(10,10,10,0.95);
                margin           : 0px;
                padding          : 0px;
                height           : calc( 100% - 50px );
                width            : calc( 100% - 150px );
                z-index          : 99999;
                color            : white;
                text-align       : center;
            }
            #loading #loading-content
            {
                padding-bottom: 20px;
                border : 1px solid #2C3E50;
                border-radius: 4px;
                width  : 200px;
                margin : 70px auto;
                border-radius: 10px;
            }
            .logout{
                background: #FF6046;
                margin-bottom: -6px;
                border-bottom-left-radius: 3px;
                border-bottom-right-radius: 6px;
                margin-left: -1px;
                margin-right: -1px;
            }

            .logout:hover a{
                background: #AD2E00 !important; 
                border-bottom-left-radius: 5px;
                border-bottom-right-radius: 5px; 
            }
            @media(max-width: 767px ){
                #loading
                {
                    width : 100%;
                }
            }
        </style>
    </head> 
    <body  <?= $config['body'] ?>  data-layout="user">
        <?php
            $pendingAlerts      = $this->Menu_DB->get_pending_alerts();
            $totalPendingAlerts = count($pendingAlerts);
        ?>
        <div id="wrapper">
            <nav class="navbar navbar-default  navbar-fixed-top" role="navigation" >
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header" style="width:100%;">
                    <!--
                    <a class="navbar-brand" href="/">Las Palmas Medical Group</a>
                    --> 
                    <!-- Top Menu Items -->
                    <span class="navbar-brand hidden-xs hidden-sm"  ><?= strtoupper(\libraries\Administration::getValue('name')); ?></span>
                    <ul class="nav navbar-right top-nav pull-right" >
                        
                        <!-- notifications pendings -->
                        <?php if($totalPendingAlerts) : ?>
                            <li class="dropdown hidden-xs" >
                                <a href="#" 
                                    class="dropdown-toggle" 
                                    data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-bell" ></i>  
                                    <?= $totalPendingAlerts;?>  Notificationes
                                    <b class="caret"></b> 
                                </a>
                                <ul class="dropdown-menu" id="navbar-collapse-pending" style="width:380px;">
                                    <?php 
                                    $limitShow = 20;
                                    foreach ($pendingAlerts as $key => $value) : 
                                        if($value->type === 'pendingEncounterSign')
                                        {
                                            $textAlert = "Consulta pendiente de firma, Paciente. <b>{$value->name} {$value->last_name}</b>";
                                            $urlAlert  = "/encounter/detail/{$value->id}";
                                        }
                                        else if( $value->type === 'pendingRequestCheckedOut')
                                        {
                                            $textAlert = "Consulta pendiente de revisión, paciente <b>{$value->name} {$value->last_name}</b>";
                                            $urlAlert  = "/encounter/request/{$value->id}";
                                        }
                                        else if($value->type === 'pendingBillingComplete')
                                        {
                                            $textAlert = "Bill pending finished, paciente <b>{$value->name} {$value->last_name}</b>";
                                            $urlAlert  = "/billing/detail/{$value->id}";
                                        }
                                        else 
                                        {
                                            $textAlert = "Consulta pendiente de crear para el paciente <b>{$value->name} {$value->last_name}</b>";
                                            $urlAlert  = "/patient/chart/{$value->id}";
                                        }
                                       
                                    ?>
                                        <li style="font-size:12px;">
                                            <a href="<?= $urlAlert;?> "><?= $textAlert; ?> <br><small class="alert-date-created"><?= $value->date;?></small></a>
                                        </li>
                                    <?php 
                                        if($key==$limitShow and $totalPendingAlerts>$limitShow){
                                            echo "<li class='disable disabled text-center'> <a href='#'>More pendings (".($totalPendingAlerts- $limitShow).")....</a></li>";
                                            break;
                                        } 
                                    endforeach; 
                                    ?>
                                </ul>   
                            </li>
                        <?php endif; ?>
                        <li class="hidden-xs" >
                            <a href="/appointment/create"> <i class="fa fa-calendar-plus-o"></i> Nueva cita</a>
                        </li>
                        <!-- settings user-->
                        <li class="dropdown" >
                            <a href="#" 
                                class="dropdown-toggle" 
                                data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-cogs" ></i> Ajustes <b class="caret"></b></a>
                            <ul class="dropdown-menu" id="navbar-collapse-user" style="width:280px;">
                                <?php if(validate_access_type(['admin','root']) ) : ?>
                                    <li><a href="/user/manager/index">Control de usuarios <i class="fa fa-users pull-right" aria-hidden="true"></i></a></li>
                                    <!-- <li><a href="/payment/">Payments<i class="fa fa-line-chart pull-right" aria-hidden="true"></i></a></li>-->
                                    <li><a href="/encounter/invoice/report">Facturas<i class="fa fa-money pull-right" aria-hidden="true"></i></a></li>
                                <?php endif; ?>
                                <?php if(validate_access_type(['manager','root']) ) : ?>
                                    <li><a href="/administration">Administración<i class="fa fa-lock pull-right" aria-hidden="true"></i> </a></li>
                                    <li><a href="/settings/">Administración de valores<i class="fa fa-cog pull-right" aria-hidden="true"></i> </a></li>
                                <?php endif; ?>
                                <li class="divider"></li>
                                <li><a href="/patient/related-files/">Archivos Relacionados <i class="fa fa-picture-o pull-right" aria-hidden="true"></i></a></li>
                                <li><a href="/user/examinations">Examenes fisicos<i class="fa fa-user-md pull-right"></i></a></li>
                                <li><a href="/user/profile">Perfil<i class="fa fa-user pull-right"></i></a></li>
                                <li class="logout"><a href="/login/close" style="/* color: #FF6046; */"><i class="fa fa-power-off pull-right"></i> Cerrar sesión</a></li>
                            </ul>   
                        </li>
                        <!-- navigation mobile-->
                        <li>
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </li>
                    </ul> 
                </div>
                
                
                    
                 <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <?php echo $this->template->get_menu( 'user',  $this->current_user ); ?>
                </div>
            </nav>
            

            <?php
                echo($this->notify->get_messages());
                echo($config['modals']);
            ?>
            
            <div  id="page-wrapper" >
                <div id="loading">
                    <div id="loading-content">
                        <div class="uil-squares-css" style="transform:scale(0.6);"><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div></div>
                        <h3 >Cargando...</h3>
                    </div>
                </div>
                <div class="container-fluid">
                    <?php  $this->load->view( $config['view'] , ['_' => $_ ]  );  ?>
                </div>
               
            </div>
		  
        </div>

        <?php 
            $siteUrl = site_url('');
        
            echo $config['js']; 
        ?>
        
        <script>
            window.addEventListener("focus", event =>
            { 
                $.get("/login/verifyLogin").success( response => 
                { 
                    response = JSON.parse(response);

                    if(response.status)
                    { 
                        location.href = "/";
                    } 
                });
            }, false);
        </script>   
</body>
</html>

