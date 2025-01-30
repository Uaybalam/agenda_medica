<?php

class Menu_Model extends CI_Model
{	
	public $current_user;
	
	function get_pending_results()
	{
		$not_in =   ( in_array($this->current_user->access_type,['admin','medic']  ) ) ?  [4] : [2, 4];
			
		$this->db
			->from('encounter_results')
			->where(["checked_out_id > " => 0 ])
			->where_in( 'encounter_results.status', $not_in )
		;
		
		return $this->db->count_all_results();
	}

	function get_pending_contacts()
	{

		$this->db
			->from('patient_contact')
			->where(['patient_contact.status' => 0 ])
		;

		return  $this->db->count_all_results();
	}
	
	function get_pending_warnings()
	{
		$status = ( in_array($this->current_user->access_type,['admin','medic']  ) ) ? 2 : 3;
		
		$this->db
			->from('patient_warnings')
			->where([
				'patient_warnings.status' => $status,
			])
		;
		
		return  $this->db->count_all_results();
	}

	function get_pending_alerts()
	{

		$currentDate          = date('Ymd');
		
		$dateFormat           = 'DATE_FORMAT(encounter.create_at,"%Y%m%d")';

		$pendingEncounterSign
			= $pendingRequestCheckedOut 
			= $pendingBillingComplete 
			= $pendingCreateEncounter 
			= [];

		if(in_array($this->current_user->access_type, ['admin','medic']))
		{
			$this->db->select([
					'encounter.id',
					'patient.name',
					'patient.last_name',
					'DATE_FORMAT(encounter.create_at,"%Y-%m-%d") as date',
					'"pendingEncounterSign" as type'
				])
				->from('encounter')
				->join('patient', 'patient.id=encounter.patient_id', 'inner')
				->where([
					'encounter.status' => 1,
					"{$dateFormat} < " => $currentDate,
				])
				->order_by("{$dateFormat} ASC");

			$pendingEncounterSign = $this->db->get()->result();
		}
		
		if(in_array($this->current_user->access_type, ['admin','nurse','secretary'] ))
		{
			$this->db->select([
					'encounter.id',
					'patient.name',
					'patient.last_name',
					'DATE_FORMAT(encounter.create_at,"%Y-%m-%d") as date',
					'"pendingRequestCheckedOut" as type'
				])
				->from('encounter')
				->join('patient', 'patient.id=encounter.patient_id', 'inner')
				->where([
					'encounter.status' => 2,
					'encounter.checked_out_id' => 0,
					"{$dateFormat} < " => $currentDate,
				])
				->order_by("{$dateFormat} ASC");

			$pendingRequestCheckedOut =  $this->db->get()->result();
		}
		
		if(in_array($this->current_user->access_type, ['admin','billing'] ))
		{
			
			$alertTimeBilling = (int)\libraries\Administration::getValue('billing_alert_time');
			$alertTimeBilling = ($alertTimeBilling) ? abs($alertTimeBilling) : 1;
			
			$this->db->select( [
					'billing.encounter_id as id',
					'billing.status',
					'patient.name',
					'patient.last_name',
					'DATE_FORMAT(billing.create_at,"%Y-%m-%d") as date',
					'"pendingBillingComplete" as type'
				])
	        	->from('billing')
	        	->join('patient', 'patient.id=billing.patient_id' , 'inner')
	        	->where([
					'billing.status' => 0,
					'DATE_FORMAT(billing.create_at,"%Y%m%d") < ' => date('Ymd', strtotime('-'.$alertTimeBilling.' Days')),
				]);

	        $pendingBillingComplete =  $this->db->get()->result();
		}

		if(in_array($this->current_user->access_type, ['admin','nurse','secretary']))
		{
			$this->db->select([
					'patient.id',
					'patient.name',
					'patient.last_name',
					'DATE_FORMAT(appointment.create_at,"%Y-%m-%d") as date',
					'"pendingCreateEncounter" as type'
				])
				->from('appointment')
				->join('patient', 'patient.id=appointment.patient_id' , 'inner')
				->where([
					'appointment.status' => 3,
					"DATE_FORMAT('appointment.create_at','%Y%m%d') < " => $currentDate,
				])
				->order_by("DATE_FORMAT('appointment.create_at','%Y%m%d') ASC");

			$pendingCreateEncounter =  $this->db->get()->result();
		}


		$mergeData = array_merge(
			$pendingEncounterSign, 
			$pendingRequestCheckedOut, 
			$pendingBillingComplete, 
			$pendingCreateEncounter
		);

		usort($mergeData, function($dataA, $dataB){
			return ($dataA->date < $dataB->date) ? 0 : 1;
		});

		return $mergeData;
	}
	
	function get_pending_results_waiting()
	{
		
		$this->db
			->from('encounter_results')
			->where(["checked_out_id > " => 0 ])
			->where_in( 'encounter_results.status', [3] )
		;
		
		return $this->db->count_all_results();
	}

	function get_pending_results_check()
	{
		
		$this->db
			->from('encounter_results')
			->where( 'encounter_results.status', 4 )
		;
		
		$totalResults = $this->db->count_all_results();
		
		$this->db
			->from('patient_related_files')
			->where("document_for_done" , 1 );

		$totalChart = $this->db->count_all_results();
		
		return $totalResults + $totalChart;
	}
		
}
// ab -n 1000 -c 5 -C "somecookie=rawr" http://healthservice.dev/
