<?php foreach ($_['administration'] as $groupName => $configs)  : ?>
		<div class="panel panel-default">
		<div class="panel-heading" style="font-size: 12px;">
			<b><?= $groupName ?></b>
		</div>
		<div class="panel-body">
			<div class="form-horizontal" >
				<?php foreach ($configs as $key => $setting) : ?>
					<div class="col-md-4">
						<form class="submit-name" >
							<div class="form-group form-group-sm">
								<label class="col-sm-4 control-label" >
									<?php if($helper = $setting->helper): ?>
										<i class="fa fa-question-circle-o" data-toggle="tooltip" title="<?= $helper ?>"></i>
									<?php endif; ?><?= $setting->title ?>
								</label>
								<div class="col-sm-8 text-center">
									<?php if($setting->type === 'text' || $setting->type === 'time' || $setting->type === 'number'){ ?>
										<input autocomplete="off" 
											type="<?= $setting->type ?>" 
											name="<?= $setting->name ?>" 
											class="form-control input-setting" 
											value="<?= $setting->value ?>" 
											data-last-value="<?= $setting->value ?>"  />
									<?php }elseif( $setting->type === 'textarea' ){?>
										<textarea class="form-control input-setting" name="<?= $setting->name ?>" data-last-value="<?= $setting->value ?>"  ><?= $setting->value;?></textarea>
									<?php } 
										elseif( $setting->type === 'file'  && $setting->name == "logo")
										{
											$info = pathinfo($setting->value);
											$info = explode("/",mime_content_type($setting->value))[0];
											
											if($info == "image")
											{
									?>
												<img src="<?= $setting->value == "isotipo.png" ? $setting->value : "/administration/getLogo?logo=".$setting->value ;?>" width="80" id="<?= $setting->name?>" style="margin: auto; margin-bottom: 5px;"><br>
									<?php
											} 
									?>	
										<label class="btn btn-danger">
											<i class="fa fa-plus"></i>
											Cambiar Logo
											<input 
												type="<?= $setting->type ?>" 
												name="<?= $setting->name ?>"  
												data-last-value="<?= $setting->value ?>"
												onchange="updateLogo(this)" style="display:none;" type="file">
										</label>
									<?php }elseif($setting->type === 'select'){ ?> 
										<select name="<?= $setting->name ?>" id="<?= $setting->name ?>" class="form-control input-setting" data-last-value="<?= $setting->value ?>">
										<?php foreach (json_decode($setting->options) as $k => $value) {?>
											<option value="<?=$k ?>" <?= $k === $setting->value ? "selected" : "" ?>><?=$value ?></option>
										<?php } ?>
										</select>
									<?php } ?>
								</div>
							</div>
						</form>
					</div>
					<?php 
						
					if( ($key + 1)  % 3 === 0)
					{
						echo "<div class='clearfix'></div>";
					}

					?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php endforeach ?>
<?php 
if(in_array($this->current_user->nick_name, ['josue','jonathanq'])) :
?>
<div class="panel panel-warning">
	<div class="panel-heading" style="font-size: 12px;">
		<b>ADVANCED SETTINGS</b>
		<small class="text-dark"> Take this action when no one is using the system </small>
	</div>
	<div class="panel-body">
		
				<a class="btn btn-warning" href="<?= site_url('administration/downloadDatabase')?>">Download Database</a>
				<br>
				<hr>
				<!--
				<?php if($_['existBackupDocuments']) : ?>
				<a target="_blank" class="btn btn-danger" href="<?= site_url('administration/downloadDocuments')?>">Download Patients Backup</a>
				
				<?php endif; ?>
				<br><small class="text-danger">CRONTAB at 11PM Sun to Sat.</small>
				<?php 
				$pathZip  = FCPATH . '../private/uploads/backupDocuments.zip';
				$pathDocs = FCPATH . '../private/uploads/patients/';
				?>
				<pre style="font-size:10px;">0 23 * * 1-6 zip -r <?= $pathZip; ?> <?= $pathDocs; ?> >/dev/null 2>&1 </pre>
				-->
		
	</div>
</div>
<?php endif;?>