<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        
        <meta name="robot" content="noindex, nofollow" />

        <title><?php echo $config['title'] ?></title>
        
        <?php echo $config['css']; ?>
       	
    </head> 
    <body cz-shortcut-listen="true" >
        
        
        <?php
            echo($this->notify->get_messages());
            echo($config['modals']);
        ?>

        <div class="container">
            <?php  $this->load->view( $config['view'] , ['_' => $_ ]  );  ?>
        </div>
		  
		<?php echo $config['js']; ?>
</body>
</html>

