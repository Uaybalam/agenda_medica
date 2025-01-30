<div clas="row">
	<div class="col-lg-12">
		<div class="panel panel-danger">
			<div class="panel-heading">
				Records not inserted
			</div>
			<div class="panel-body">
				<ul  class="nav nav-pills" >
					<?php
					$classActive = "active"; 
					foreach ($_['errorData'] as $key => $value) : ?>
						<li class="<?= $classActive?>">
	        				<a  href="#errorData-<?= $key?>" data-toggle="tab"><?= $key?> <span class="badge"><?= count($value)?></span> </a>
						</li>
					<?php $classActive = ""; endforeach;  ?>
				
				</ul>

				<div class="tab-content">
					<?php
					$classActive = "active"; 
					foreach ($_['errorData'] as $key => $value) : ?>
						<div class="tab-pane <?= $classActive?>" id="errorData-<?= $key?>">	
							<?php foreach ($value as $v) {echo "<p>{$v}</p>";}?>
						</div>
					<?php $classActive = ""; endforeach;  ?>
				</div>
  			</div>
		</div>
	</div>
</div>