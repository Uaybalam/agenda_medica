<?php
/**
* @route:encounter/compare
*/
class Encounter_Compare_Controller extends APP_User_Controller
{	

	public $meses = ["Ener","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"];
	/**
	 * @route:(:num)/(:num)/(_vitals_basic)
	 * @route:(:num)/(:num)/(_vitals_heart)
	 * @route:(:num)/(:num)/(_vitals_physical)
	 * @route:(:num)/(:num)/(_vitals_eyes)
	 * @route:(:num)/(:num)/(_vitals_audio)
	 * @route:(:num)/(:num)/(_vitals_urinalysis)
	 * @route:(:num)/(:num)/(_illness_history)
	 * @route:(:num)/(:num)/(_physical_exam)
	 * @route:(:num)/(:num)/(_diagnosis)
	 * @route:(:num)/(:num)/(_procedure)
	 * @route:(:num)/(:num)/(_medications)
	 * @route:(:num)/(:num)/(_results)
	 * @route:(:num)/(:num)/(_referrals)
	 * @route:(:num)/(:num)/(_blood_pressure)
	 */
	function compare( $patient_id, $encounter_id , $filter )
	{
		$this->validate_access(['manager','medic','billing']);
		$this->lang->load('encounter_compare');
		
		$this->current_encounter_id = $encounter_id;
		$this->load->model([
			'Encounter_Model' => 'Encounter_DB',
			'Encounter_Compare_Model' => 'Encounter_Compare_DB',
		]);
		
		$this->{$filter}($patient_id);
	}

	private function _blood_pressure( $patient_id )
	{
		$encounters = $this->Encounter_Compare_DB->blood_pressure( $patient_id );
		if(!count($encounters))
		{
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Signos Vitales > Presión arterial (sistólica and diastólica.)');
		}
		else
		{
			$this->template
				->render_view('/encounter/compare/view.compare.blood_pressure',[
					'current_encounter_id' => $this->current_encounter_id,
					'encounters' => $encounters
				]);
		}	
	}
	

	private function _vitals_basic( $patient_id )
	{
		$encounters = $this->Encounter_Compare_DB->vitals_basic( $patient_id );
		if(!count($encounters))
		{
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Signos Vitales > Basico');
		}
		else
		{
			$this->template
				->render_view('/encounter/compare/view.compare.vitals_basic',[
					'current_encounter_id' => $this->current_encounter_id,
					'encounters' => $encounters, 
					"months"  => $this->meses
				]);
		}
	}
	
	private function _vitals_heart( $patient_id )
	{

		$encounters = $this->Encounter_Compare_DB->vitals_heart( $patient_id );
		if(!count($encounters))
		{
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Signos Vitales > Físico (Pulso, Frecuencia respiratoria, Temperatura) ');
		}
		else
		{
			$this->template
				->render_view('/encounter/compare/view.compare.vitals_heart',[
					'current_encounter_id' => $this->current_encounter_id,
					'encounters' => $encounters
				]);
		}	
	}

	private function _vitals_physical( $patient_id )
	{

		$encounters =  $this->Encounter_Compare_DB->vitals_physical( $patient_id );
		if(!count($encounters))
		{
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Signos Vitales > Físico (Peso y Altura)');
			exit;
		}
		else
		{
			$this
			->template
			->render_view('/encounter/compare/view.compare.vitals_physical',[
					'current_encounter_id' => $this->current_encounter_id,
					'encounters' => $encounters
				]);
		}
	}

	private function _vitals_eyes( $patient_id )
	{
		$encounters =  $this->Encounter_Compare_DB->vitals_eyes( $patient_id );
		if(!count($encounters))
		{
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Signos Vitales > Ojos');
		}
		else
		{	
			$this->template
				->render_view('/encounter/compare/view.compare.vitals_eyes',[
					'current_encounter_id' => $this->current_encounter_id,
					'encounters' => $encounters, 
					"months"  => $this->meses
				]);
		}
	}

	private function _vitals_audio( $patient_id )
	{
		$encounters =  $this->Encounter_Compare_DB->vitals_audio( $patient_id );
		if(!count($encounters))
		{	
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Signos Vitales > oídos');
		}
		else
		{
			$this->template
				->render_view('/encounter/compare/view.compare.vitals_audio',[
					'current_encounter_id' => $this->current_encounter_id,
					'encounters' => $encounters, 
						"months"  => $this->meses
				]);
		}
	}

	private function _vitals_urinalysis( $patient_id )
	{
		$encounters =  $this->Encounter_Compare_DB->vitals_urinalysis( $patient_id );
		if(!count($encounters))
		{
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Signos Vitales > Uroanálisis ');
		}
		else
		{
			$this->template
				->render_view('/encounter/compare/view.compare.vitals_urinalysis',[
					'current_encounter_id' => $this->current_encounter_id,
					'encounters' => $encounters, 
						"months"  => $this->meses
				]);
		}
	}

	private function _illness_history( $patient_id )
	{
		$encounters =  $this->Encounter_Compare_DB->illness_history( $patient_id );
		if(!count($encounters))
		{
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Presente historial de enfermedades');
		}
		else
		{
			$this->template
				->render_view('/encounter/compare/view.compare.illness_history',[
						'current_encounter_id' => $this->current_encounter_id,
						'encounters' => $encounters, 
						"months"  => $this->meses
					]);
		}
	}

	private function _physical_exam( $patient_id )
	{
		$physical_exams =  $this->Encounter_Compare_DB->physical_exam( $patient_id );
		if(!count($physical_exams))
		{
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Examen físico');
		}
		else
		{
			$this->template
				->render_view('/encounter/compare/view.compare.physical_exam',[
						'current_encounter_id' => $this->current_encounter_id,
						'physical_exams' => $physical_exams, 
						"months"  => $this->meses
					]);
		}
	}

	private function _diagnosis( $patient_id )
	{
		$diagnosis =  $this->Encounter_Compare_DB->diagnosis( $patient_id );
		if(!count($diagnosis))
		{
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Diagnostico');
			exit;
		}

		$this->template
			->render_view('/encounter/compare/view.compare.diagnosis',[
					'current_encounter_id' => $this->current_encounter_id,
					'diagnosis' => $diagnosis, 
					"months"  => $this->meses
				]);
	}

	private function _procedure( $patient_id )
	{
		$encounters =  $this->Encounter_Compare_DB->procedure( $patient_id );
		if(!count($encounters))
		{
			echo sprintf($this->lang->line('encounter_compare_not_data'),'');
			exit;
		}
		else
		{	
			$this->template
				->render_view('/encounter/compare/view.compare.procedure',[
						'current_encounter_id' => $this->current_encounter_id,
						'encounters' => $encounters, 
						"months"  => $this->meses
					]);
		}
	}

	private function _medications( $patient_id )
	{
		$encounters =  $this->Encounter_Compare_DB->medications( $patient_id );
		if(!count($encounters))
		{
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Medicaciones');
		}
		else
		{	
			$this->template
				->render_view('/encounter/compare/view.compare.medications',[
						'current_encounter_id' => $this->current_encounter_id,
						'encounters' => $encounters, 
						"months"  => $this->meses
					]);
		}
	}

	private function _results( $patient_id )
	{
		$encounters =  $this->Encounter_Compare_DB->results( $patient_id );
		if(!count($encounters))
		{	
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Solicitudes');
		}
		else
		{
			$this
				->template
				->render_view('/encounter/compare/view.compare.results',[
						'current_encounter_id' => $this->current_encounter_id,
						'encounters' => $encounters, 
						"months"  => $this->meses
					]);
		}	
	}

	private function _referrals( $patient_id )
	{

		$encounters =  $this->Encounter_Compare_DB->referrals( $patient_id );
		if(!count($encounters))
		{	
			echo sprintf($this->lang->line('encounter_compare_not_data'),'Derivaciones');
		}
		else
		{	
			$this
				->template
				->render_view('/encounter/compare/view.compare.referrals',[
						'current_encounter_id' => $this->current_encounter_id,
						'encounters' => $this->Encounter_Compare_DB->referrals( $patient_id ), 
						"months"  => $this->meses
					]);
		}	
	}

}