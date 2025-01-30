<?php
class Patient_History_Model extends APP_Model
{	
	private $_datos = [
		'left' =>  [
		    'Básicos' => [
		        'DM', // Diabetes Mellitus
		        'HTN', // Hipertensión
		        'Asma',
		        'Cáncer' => '__add_coments__',
		        'Hospitalización reciente' => '__add_coments__',
		    ],
		    'Problemas cardíacos' => [
		        'Dolor en el pecho',
		        'Palpitaciones',
		        'Historial de infarto de miocardio',
		    ],
		    'Problemas en el pecho' => [
		        'Tuberculosis',
		        'Tos crónica',
		        'Dificultad para respirar',
		        'Bronquitis',
		        'Problemas mamarios',
		    ],
		],
		'middle' => [
		    'Genitourinario' => [
		        'Dolor al orinar',
		        'Sangre en la orina',
		        //'Cálculos renales',
		        'Enfermedades renales' => '__add_coments__',
		        'Problemas de próstata',
		        'Enfermedades ginecológicas' => '__add_coments__',
		    ],
		    'Gastrointestinal' => [
		        'Diarrea crónica',
		        'Estreñimiento',
		        'Úlceras estomacales',
		        'Gastritis',
		        'Enfermedades hepáticas' => '__add_coments__',
		        'Sangrado rectal',
		        'Cálculos biliares',
		        'Pérdida de peso',
		    ]
		],
		'right' => [
		    'Neurológico' => [
		        'Accidentes cerebrovasculares',
		        'Convulsiones',
		        'Pérdida de conciencia',
		        /*
		        'Problemas mentales',
		        'Problemas visuales',
		        'Problemas auditivos',
		        'Problemas de tiroides',
		        'Lesiones cutáneas',
		        */
		        //'Glaucoma',
		        'Migraña' => '__add_coments__',
		        'Lesión en la cabeza' => '__add_coments__',
		    ],
		    'Musculoesquelético' => [
		        'Problemas en las articulaciones',
		        'Fracturas',
		        'Artritis',
		        'Dolor de espalda',
		    ],
		    'Otros' => [
		        'Problemas mentales' => '__add_coments__',
		        'Problemas visuales' => '__add_coments__',
		        'Problemas auditivos' => '__add_coments__',
		        'Problemas de tiroides' => '__add_coments__',
		        'Lesiones cutáneas' => '__add_coments__',
		        'Enfermedades autoinmunes' => '__add_coments__',
		    ]
		]
	];

	function get_catalog_history( $patient_id = 0 )
	{
		$response = [];
		foreach ($this->_datos as $position => $data_position ) {

			foreach ($data_position as $group => $data) {
				$data_tmp = [];
				foreach ($data as $key => $title) {
					$tmp          = new StdClass;
					$tmp->group   = $group;
					if($title==='__add_coments__')
					{
						$tmp->title         = $key;
						$tmp->show_comments = 1;
					}
					else
					{
						$tmp->title         = $title;
						$tmp->show_comments = 0;
					}
					$tmp->comments = '';
					$tmp->patient  = '';
					$tmp->family   = '';

					if($patient_id)
					{
						$this->db->select('*')->from('patient_history')
							->where(['patient_id' => $patient_id])
							->where(['title' => $tmp->title ])
							->where(['group_history' => $tmp->group ]);

						$data = $this->db->get()->row_array();
						if($data)
						{
							$tmp->comments = $data['comments'];
							$tmp->patient  = $data['patient'];
							$tmp->family   = $data['family'];
						}
					}

					$data_tmp[]    = $tmp;
				}

				$response[$position][] = [
					'group' => $group,
					'data'  => $data_tmp
				];
			}	
		}

		
		return $response;
	}

	function get_info( $patient_id )
	{
		$data = $this->db
			->select('patient_history.*')
			->from('patient_history')
			->where(['patient_history.patient_id' => $patient_id ])
			->get()->result();

		return [
			'data' => $data
		];
	}

	function get_data_pdf( $patient_id )
	{

		$positions 	= $this->db
			->select('position')
			->from('patient_history')
			->where(['patient_history.patient_id' => $patient_id ])
			->group_by('position')
			->get()->result();
		
		$group 	= $this->db
			->select('group_history as group, position')
			->from('patient_history')
			->where(['patient_history.patient_id' => $patient_id ])
			->group_by('group, position')
			->get()->result();

		$data = $this->get_info( $patient_id );
		$data['positions'] 	= $positions;
		$data['group'] 		= $group;

		return $data;
	}

	function get_active_diseases( $ID )
	{
		
		return $this->db
			->select('patient_history.*')
			->from('patient_history')
			->where( ['patient_id' => $ID ])
			->where( " (patient_history.patient='Yes' OR patient_history.family='Yes' ) " )
			->get()->result()
		;
	}

}