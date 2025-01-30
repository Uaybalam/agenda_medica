<?php 
	
	//PR($config);
	if( $config['open_on_load'] )
	{	
		echo '<script>'.
			'document.addEventListener("DOMContentLoaded", function() {
				$("#'.$config['id'].'").modal("show");
			});'.
			'</script>';
	}	
?>

<div 	class="modal fade" 
		id="<?= $config['id']; ?>" 
		tabindex="-1"  
		role="dialog" >
				
	<div class="modal-dialog <?= $config['size']; ?>">
		<div class="modal-content">
			<div class="modal-header" align="center">
					
				<h3><?= $config['title'] ?></h3>
				<button type="button" 
					class="close" 
					data-dismiss="modal" 
					aria-label="Close" 
					style="position:absolute;right:20px;top:20px;">
				<i class="fa fa-close"></i>
				</button>
			</div>
				
			<div  class="modal-body" style="padding-bottom:0px;">
				<?php $this->load->view( $config['view'], ['_' => $params ]); ?>
			</div>

			
		</div>
	</div>
</div>