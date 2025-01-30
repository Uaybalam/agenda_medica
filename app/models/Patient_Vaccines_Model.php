<?php
/**
* 
*/
class Patient_Vaccines_Model extends APP_Model
{

	public $init_options = [
	    [
	        'quantity' => 4,
	        'title' => 'HEPATITIS B',
	    ],
	    [
	        'quantity' => 3,
	        'title' => 'ROTAVIRUS (RV)',
	    ],
	    [
	        'quantity' => 6,
	        'title' => 'DIFTERIA TÉTANOS PERTUSSIS',
	        'subtitle' => 'DTap,Tdap,DT/Td',
	    ],
	    [
	        'quantity' => 4,
	        'title' => 'HAEMOPHILUS INFLUENZAE TIPO B (HIB)',
	    ],
	    [
	        'quantity' => 4,
	        'title' => 'NEUMOCÓCICA',
	        'subtitle' => 'PCV,PPV',
	    ],
	    [
	        'quantity' => 4,
	        'title' => 'POLIO',
	        'subtitle' => 'IPV,OPV'
	    ],
	    [
	        'quantity' => 2,
	        'title' => 'SARAMPIÓN PAPERAS RUBÉOLA (SPR)',
	    ],
	    [
	        'quantity' => 2,
	        'title' => 'VARICELA (varicela)'
	    ],
	    [
	        'quantity' => 2,
	        'title' => 'HEPATITIS A'
	    ],
	    [
	        'quantity' => 6,
	        'title' => 'INFLUENZA',
	        'subtitle' => 'TIV,LAIV'
	    ],
	    [
	        'quantity' => 3,
	        'title' => 'VIRUS DEL PAPILOMA HUMANO (VPH)'
	    ],
	    [
	        'quantity' => 2,
	        'title' => 'MCV,MPV'
	    ],
	    [
	        'field_name' => 'other',
	        'title' => '',
	        'quantity' => 4
	    ]
	];

	function filter( $search,  $data )
	{
		$response  = [];
		foreach ( $search as $value) {
			$value  = (array)$value;
			$add 	= TRUE;
			foreach ($data as $key => $data_tmp) {
				if($value[$key] != $data_tmp )
				{
					$add = FALSE;
					break;
				}
			}	
			if($add) $response[] = $value;
		}
		return $response;
	}

	function get_data( $patient_id = 0 )
	{	
		
		//$info = $this->getResultsBy( ['patient_id' => $patient_id ] );
		$filter = Array(
			'patient_id' => $patient_id,
			'title' => '' 
		); 

		foreach ($this->init_options as $key => $value) {
			
			if(isset($value['field_name']))
			{
				$fieldName = $filter['field_name'] = $value['field_name'];
				unset($filter['title']);
				$title = "";
			}
			else
			{
				$title = $filter['title'] = $value['title'];
				$fieldName 	= '';
				unset($filter['field_name']);
			}

			for( $n=0 ; $n < $value['quantity']; $n++) {
				
				$number = $n + 1;

				$filter['number'] = $number;

				$code  = $vis_date = $exp_date = $date_given =  $site =  $administered_by = $intern = $subtitle = '';
				
				if($found = $this->search($filter) )
				{
					$code            = $found['code'];
					$date_given      = $found['date_given'];
					$site            = $found['site'];
					$administered_by = $found['administered_by'];
					$intern 		 = $found['intern'];
					$subtitle 		 = $found['subtitle'];
					$vis_date 		 = $found['vis_date'];
					$exp_date 		 = $found['exp_date'];
					$title 			 = $found['title'];
				}

				$response_data[] = [
					'title'=> $title,
					'number'=> $number,
					'code'=> $code,
					'date_given' => $date_given,
					'vis_date' => $vis_date,
					'exp_date' => $exp_date,
					'administered_by' => $administered_by,
					'site'=> $site,
					'intern'=> $intern,
					'subtitle' => $subtitle,
					'edit_title' => isset($value['field_name']) ? 1 : 0,
					'field_name' => $fieldName
				];
			}
		}
		//add 4 records

		return $response_data;

	}

	function search( $where )
	{
		$this->db->from('patient_vaccines')
			->where($where);

		return $this->db->get()->row_array();
	}
}
