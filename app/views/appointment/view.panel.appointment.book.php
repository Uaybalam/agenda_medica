<input type="hidden" id="prevent-default-loading" value="1" />
<div class="row">
	<div class="col-lg-12">

		<div class="panel panel-default panel-custom">
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-4">
						<label>Citas <span ng-cloak data-toggle="tooltip" data-placement="right" title="Total appointments" class="badge"> {{ appointments.length}} </span></label>
					</div>
					<div class="col-sm-8" >
						<div class="form-group form-grpup-sm" style="margin:0px;max-width: 320px !important;">
							<div class="input-group"  >
								<span data-toggle="tooltip" data-placement="top" title="Previo día" class="input-group-addon input-group-addon-link" style="padding:0px 10px;" ng-click="backDay()"><i class="fa fa-arrow-left"></i></span>
								<input type="text" style="font-size: 20px;text-align: center;" 
									class="form-control input-sm create-datepicker" 
									readonly="true"
									ng-change="updateData(date_appointment, true );"
									ng-model="date_appointment" >
								<span data-toggle="tooltip" data-placement="top" title="Siguiente día" class="input-group-addon input-group-addon-link" style="padding:0px 10px;" ng-click="nextDay()"><i class="fa fa-arrow-right"></i></span>
								<span data-toggle="tooltip" data-placement="top" title="Hoy" class="input-group-addon input-group-addon-link" style="padding:0px 10px;" ng-click="currentDate()"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>

				</div>
			</div>
			
			<?php  
				if($this->mobile_detect->isMobile())  
					$this->load->view('appointment/view.panel.appointment.book.mobile.php');
				else
					$this->load->view('appointment/view.panel.appointment.book.pc.php');
			?>
			<div class="panel-footer">
				<a class="btn btn-sm btn-primary" href="/appointment/create"> <i class="full-icon fa fa-calendar-plus-o"></i> Crear cita</a>
			</div>
		</div>
	</div>
</div>


