<input type="hidden" id="prevent-default-loading" value="1" />

<div class="row">
	<div class="col-lg-8" >
	<?php $this->load->view('patient/detail/view-panel-about') ?>
	</div>
	<div class="col-lg-4" >
		<?php $this->load->view('patient/detail/view-panel-insurance-primary') ?>
		<?php $this->load->view('patient/detail/view-panel-insurance-secondary') ?>
	</div>
</div>
 
<div class="row">
	<div class="col-lg-4" ><?php $this->load->view('patient/detail/view-panel-address') ?></div>
	<div class="col-lg-4" ><?php $this->load->view('patient/detail/view-panel-member') ?></div>
	<div class="col-lg-4" ><?php $this->load->view('patient/detail/view-panel-preventions') ?></div>
</div>

<div class="row">
	<div class="col-lg-4" ><?php $this->load->view('patient/detail/view-panel-responsible') ?></div>
	<div class="col-lg-4" ><?php $this->load->view('patient/detail/view-panel-contact-emergency') ?></div>
	<div class="col-lg-4" ><?php $this->load->view('patient/detail/view-panel-warnings') ?></div>
</div>