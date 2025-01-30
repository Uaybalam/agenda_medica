<?php

class Billing_Charges_Model extends APP_Model
{
	public function updateCharges( $charges )
	{
		foreach ($charges as $position => $charge) {
			
			$totalCharge = 0.00;
			
			for( $i=1; $i<7; $i++)
			{
				$cpt = $charge['procedure_cpt_hcpcs_'.$i];

				$this->{"emg_$i"}                   = $charge['emg_'.$i];
				$this->{"place_of_service_$i"}      = $charge['place_of_service_'.$i];
				$this->{"procedure_cpt_hcpcs_$i"}   = $charge['procedure_cpt_hcpcs_'.$i];
				$this->{"modifier_a_$i"}            = $charge['modifier_a_'.$i];
				$this->{"modifier_b_$i"}            = $charge['modifier_b_'.$i];
				$this->{"modifier_c_$i"}            = $charge['modifier_c_'.$i];
				$this->{"modifier_d_$i"}            = $charge['modifier_d_'.$i];
				$this->{"diagnosis_pointer_$i"}     = $charge['diagnosis_pointer_'.$i];
				$this->{"charges_$i"}               = $charge['charges_'.$i];
				//$this->{"days_units_$i"}            = // ($cpt) ? 1 : '';
				$this->{"days_units_$i"}            = isset($charge['days_units_'.$i]) ? $charge['days_units_'.$i] : $cpt;
				$this->{"family_plan_$i"}           = '';//$charge['family_plan_'.$i];
				$this->{"id_qual_$i"}               = $charge['id_qual_'.$i];
				$this->{"rendering_provider_id_$i"} = $charge['rendering_provider_id_'.$i];
				$this->{"active_$i"}                = $charge['active_'.$i];
				$this->{"date_of_service_$i"}        = $charge['date_of_service_'.$i];
				
				$totalCharge+= floatval($charge['charges_'.$i]);
			}

			$this->total_charge = $totalCharge;
			$this->save($charge['id']);
		}
	}
}