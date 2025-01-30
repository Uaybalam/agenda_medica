<?php
class Encounter_Child_Model extends APP_Model
{	
	/**
	 * 
	 */
	protected $primarykey = 'encounter_id';

	/**
	 * 
	 */
	public $settings_development_options = [
    'more_000_under_001' => [
        'Levanta la cabeza boca abajo',
        'Responde al ruido',
        'Sigue hacia la línea media',
        'Observa la cara',
        'Gira la cabeza de lado a lado',
        'Interacción padre/hijo'
    ],
    'more_001_under_003' => [
        'Vocaliza',
        'Sonríe en respuesta',
        'Patadas',
        'Levanta la cabeza 45°',
        'Sigue más allá de la línea media'
    ],
    'more_003_under_005' => [
        'Levanta la cabeza boca abajo 90°',
        'Ríe/chilla',
        'Sigue hasta 180°',
        'Cabeza estable sentado',
        'Agarra la sonajero',
        'Gira en una dirección'
    ],
    'more_005_under_007' => [
        'Sentado sin apoyo',
        'Se alimenta solo con galletas',
        'Ansiedad ante desconocidos',
        'Transfiere objetos de una mano a otra',
        'Gira hacia el sonido',
        'Golpea objetos',
        'Gatea'
    ],
    'more_007_under_010' => [
        'Jalado para sentarse, sin inclinación de cabeza',
        'Alcanza objetos',
        'Sonríe espontáneamente',
        'Gira en ambas direcciones',
        'Se sienta brevemente solo',
        'Muerde objetos',
        'Babbling',
        'Gira hacia el sonido'
    ],
    'more_010_under_013' => [
        'Se para momentáneamente',
        'Camina sosteniendo muebles',
        'Golpea objetos',
        '"Mamá", "Papá" (ahora específico)',
        'Agarre de pulgar-dedo',
        'Sostiene la taza para beber',
        'Busca objetos que caen',
        'Juega a las palmadas/adiós con la mano, entiende que no'
    ],
    'more_013_under_016' => [
        'Se para solo',
        'Camina',
        'Construye torre de dos cubos',
        'Indica deseos/jala, señala',
        'Taza - derrame mínimo',
        'Inclina para recoger juguetes',
        'Vocabulario de 3 palabras'
    ],
    'more_016_under_024' => [
        'Imita tareas domésticas',
        'Apila 2-3 bloques',
        'Camina bien - trepa',
        'Vocabulario de 4-10 palabras',
        'Garabatos',
        'Responde a preguntas con preguntas'
    ],
    'more_024_under_036' => [
        'Da patadas a la pelota',
        'Señala partes del cuerpo',
        'Tareas domésticas sencillas',
        'Sube y baja escaleras',
        'Maneja bien la cuchara',
        'Juega al escondite',
        'Corre bien',
        'Sigue instrucciones simples'
    ],
    'more_036_under_048' => [
        'Conoce nombre, edad, sexo',
        'Frases cortas, discurso comprendido por la familia, plurales',
        'Construye torre de 9 cubos',
        'Se viste solo/ayuda a lavarse las manos',
        'Monta triciclo',
        'Sube escaleras alternando los pies',
        'Conoce un color'
    ],
    'more_048_under_072' => [
        'Abotona (4.2 años)',
        'Copia un cuadrado (4.4 años)',
        'Reconoce 3 colores',
        'Lanza la pelota',
        'Salta sobre un pie (4.9 años)',
        'Dibuja una persona',
        'Sigue 3 comandos',
        'Tolerancia a la separación',
        'Totalmente entrenado para ir al baño',
        'Discurso comprensible'
    ],
    'more_072_under_108' => [
        'Progreso escolar',
        'Grado',
        'Nombres de tres animales',
        'Deportes',
        'Relaciones con los compañeros',
        'Hobbies',
        'Monta bicicleta'
    ],
    'more_108_under_156' => [
        'Progreso escolar',
        'Grado',
        'Deportes',
        'Relaciones con los compañeros',
        'Hobbies'
    ],
    'more_156_under_204' => [
        'Progreso escolar',
        'Grado',
        'Imagen corporal',
        'Deportes',
        'Relaciones con los compañeros',
        'Hobbies',
        'Cuida de sí mismo y de los demás'
    ],
    'more_204_under_240' => [
        'Progreso escolar',
        'Grado',
        'Imagen corporal',
        'Deportes',
        'Relaciones con los compañeros',
        'Hobbies',
        'Trabajo/Planes futuros'
    ]
];

	
	/**
	 * 
	 */
	private $settings_development_plan = [
    'more_000_under_001' => [
        'Hepatitis B #1',
    ],
    'more_001_under_003' => [
        'Hepatitis B #1',
        'Hepatitis B #2',
        'DTaP #1',
        'Hib #1',
        'IPV #1',
    ],
    'more_003_under_005' => [
        'Hepatitis B #2',
        'DTaP #2',
        'Hib #2',
        'IPV #2'
    ],
    'more_005_under_010' => [
        'Hepatitis B #3',
        'DTaP #3',
        'Hib #3',
        'IPV #3'
    ],
    'more_010_under_013' => [
        'Prueba de plomo en la sangre',
        'Hepatitis B #3',
        'Hib #4',
        'IPV #3',
        'MMR #1',
        'Varicela'
    ],
    'more_013_under_016' => [
        'Hepatitis #3',
        'DTaP #4',
        'Hib #4',
        'IPV #3',
        'MMR #1',
        'Varicela'
    ],
    'more_016_under_024' => [
        'Hepatitis B #3',
        'DTaP #4',
        'IPV #3',
        'Varicela'
    ],
    'more_024_under_036' => [
        'Prueba de plomo en la sangre',
        'Hepatitis A #1'
    ],
    'more_036_under_048' => [
        'Remisión para cuidado dental preventivo',
    ],
    'more_048_under_072' => [
        'Remisión para cuidado dental preventivo',
        'DTaP #5',
        'IPV #4',
        'MMR #2',
        'PPD'
    ],
    'more_072_under_108' => [
        'Remisión para cuidado dental preventivo',
        'DTaP #5',
        'IPV #4',
        'MMR #2',
    ],
    'more_108_under_156' => [
        'Remisión para cuidado dental preventivo',
        'Historial de varicela',
        'Td',
        'PPD'
    ],
    'more_156_under_204' => [
        'Remisión para cuidado dental preventivo',
        'Td',
        'PPD'
    ],
    'more_204_under_240' => [
        'Remisión para cuidado dental preventivo',
    ]
];



	
	
	/**
	 * 
	 */
	public $settings_ethnic_codes = [
		1 => 'Indígena Americano',
	    2 => 'Asiático',
	    3 => 'Negro',
	    4 => 'Filipino',
	    5 => 'Mexicano Americano/Hispano',
	    6 => 'Blanco',
	    7 => 'Otro',
	    8 => 'Isleño del Pacífico'
	];

	/**
	 * 
	 */
	public $options_educations = [
	    1 => 'Nutrición',
	    2 => 'Tabaco',
	    3 => 'Seguridad',
	    4 => 'Crianza',
	    5 => 'Dental',
	    6 => 'Folleto "Crecer Sano" entregado',
	    7 => 'Otro...'
	];

	public function available_development_options( $patient_months )
	{
		$returnArrayValues = [];
		
		foreach ($this->settings_development_options as $key => $results ) {
			$returnArrayValues = array_merge( $returnArrayValues , $results );
			/*
			$params    = explode("_", $key );

			$min_value = (int)$params[1];
			$max_value = (int)$params[3];
			
			if ( $max_value <= $patient_months )
			{
				$returnArrayValues = array_merge( $returnArrayValues , $results );
			}
			else
			{
				break;
			}
			*/
		}

		$returnArrayValues = array_unique($returnArrayValues);
		
		return $returnArrayValues;
	}

	public function available_development_plans( $patient_months )
	{
		$returnArrayValues = [];
		
		foreach ($this->settings_development_plan as $key => $results ) {
			$returnArrayValues = array_merge( $returnArrayValues , $results );
			/*
			$params    = explode("_", $key );

			$min_value = (int)$params[1];
			$max_value = (int)$params[3];
			
			if ( $max_value <= $patient_months )
			{
				$returnArrayValues = array_merge( $returnArrayValues , $results );
			}
			else
			{
				break;
			}
			*/
		}


		$returnArrayValues = array_unique($returnArrayValues);
		
		return $returnArrayValues;
	}
	
	/**
	 * 
	 */
	function validate_data( $form_validation )
	{
		$form_validation
			->set_rules('treatment','Treatment','trim|xss_clean|max_length[256]')
			->set_rules('assessment','Assessment','trim|xss_clean|max_length[256]')
			->set_rules('tb_risk','TB risk',"in_list['',Yes,No]")
			->set_rules('lead_risk','Lead risk',"in_list['',Yes,No]")
			->set_rules('interval_history_diet','Diet','trim|xss_clean|max_length[128]')
			->set_rules('interval_history_illness','Illness','trim|xss_clean|max_length[128]')
			->set_rules('interval_history_problems','Problems','trim|xss_clean|max_length[128]')
			->set_rules('interval_history_immunization','Immunization','trim|xss_clean|max_length[128]')
			->set_rules('interval_history_parental_concerns','Parental concerns','trim|xss_clean|max_length[128]')
			
			->set_rules('development_result','Development result',"in_list['',Normal,Abnormal]")

			->set_rules('pm_160','PM 160',"in_list['',Yes,No]")
			->set_rules('referred_to_wic','Referred to WIC',"in_list['',Yes,No]")
			->set_rules('enrolled_in_wic','Enrolled in WIC',"in_list['',Yes,No]")
		;

		$form_validation
			->set_rules('physical_comments_general_appearance','General appearance','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_nutrition','Nitrition','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_skin','Skin','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_head_neck_nodes','Head neck nodes','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_eyes_eq_reflex','Eyes eq reflex','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_ent_hearing','Ent hearing','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_mouth_dental','Mouth dental','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_chest_lungs','Chest lungs','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_heart','Heart','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_abdomen','Abdomen','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_external_genitalia','External genitalia','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_back','Back','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_extremities_hips','Extremities Hips','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_neurological','Neurological','trim|xss_clean|max_length[256]')
			->set_rules('physical_comments_fem_pulses','Fem pulses','trim|xss_clean|max_length[256]')
		;

		$form_validation
			->set_rules('physical_result_general_appearance','General appearance',"in_list['',N,AB]")
			->set_rules('physical_result_nutrition','Nitrition',"in_list['',N,AB]")
			->set_rules('physical_result_skin','Skin',"in_list['',N,AB]")
			->set_rules('physical_result_head_neck_nodes','Head neck nodes',"in_list['',N,AB]")
			->set_rules('physical_result_eyes_eq_reflex','Eyes eq reflex',"in_list['',N,AB]")
			->set_rules('physical_result_ent_hearing','Ent hearing',"in_list['',N,AB]")
			->set_rules('physical_result_mouth_dental','Mouth dental',"in_list['',N,AB]")
			->set_rules('physical_result_chest_lungs','Chest lungs',"in_list['',N,AB]")
			->set_rules('physical_result_heart','Heart',"in_list['',N,AB]")
			->set_rules('physical_result_abdomen','Abdomen',"in_list['',N,AB]")
			->set_rules('physical_result_external_genitalia','External genitalia',"in_list['',N,AB]")
			->set_rules('physical_result_back','Back',"in_list['',N,AB]")
			->set_rules('physical_result_extremities_hips','Extremities Hips',"in_list['',N,AB]")
			->set_rules('physical_result_neurological','Neurological',"in_list['',N,AB]")
			->set_rules('physical_result_fem_pulses','Fem pulses',"in_list['',N,AB]")
		;

		$form_validation
			->set_rules('tobacco_patient_exposed','Tobacco patient exposed',"in_list['',Yes,No]")
			->set_rules('tobacco_used_by_patient','Tobacco used by patient',"in_list['',Yes,No]")
			->set_rules('tobacco_prevention_referred','Tobacco prevention referred',"in_list['',Yes,No]")
		;

		if($form_validation->run() === FALSE)
		{
			$msg_error = $form_validation->error_string();
			if($msg_error === '')
			{
				return 'No data get';
			} 
			else
			{	
				return $msg_error;
			}
		}
		else
		{
			return false;
		}
	}

	function get_data( $encounter_id )
	{

		$encounter_child = $this->get($encounter_id);
		if(!$encounter_child)
		{
			$encounter_child = new StdClass;
			$encounter_child->id = 0;

			$encounter_child->show_basic            = false;
			$encounter_child->show_interval_history = false;
			$encounter_child->show_development      = false;
			$encounter_child->show_examination      = false;
			$encounter_child->show_tobacco          = false;
		}
		else
		{
			$encounter_child->show_basic = ( 
				$encounter_child->ethnic_code ||
				$encounter_child->type_of_screen ||
				$encounter_child->referred_to_wic ||
				$encounter_child->enrolled_in_wic ||
				$encounter_child->treatment ||
				$encounter_child->assessment ||
				$encounter_child->tb_risk ||
				$encounter_child->lead_risk
			)  ? true : false;

			$encounter_child->show_interval_history  = (
				$encounter_child->interval_history_diet || 
				$encounter_child->interval_history_immunization ||
				$encounter_child->interval_history_problems || 
				$encounter_child->interval_history_illness ||
				$encounter_child->interval_history_parental_concerns 
			) ? true : false;

			$encounter_child->show_development  = (
				$encounter_child->development_result || 
				$encounter_child->development_options ||
				$encounter_child->development_plan ||
				$encounter_child->educations
			) ? true : false;

			$encounter_child->show_examination  = (
				$encounter_child->physical_comments_general_appearance ||
				$encounter_child->physical_comments_nutrition ||
				$encounter_child->physical_comments_skin ||
				$encounter_child->physical_comments_head_neck_nodes ||
				$encounter_child->physical_comments_eyes_eq_reflex ||
				$encounter_child->physical_comments_ent_hearing ||
				$encounter_child->physical_comments_mouth_dental ||
				$encounter_child->physical_comments_chest_lungs ||
				$encounter_child->physical_comments_heart ||
				$encounter_child->physical_comments_abdomen ||
				$encounter_child->physical_comments_external_genitalia ||
				$encounter_child->physical_comments_back ||
				$encounter_child->physical_comments_extremities_hips ||
				$encounter_child->physical_comments_neurological ||
				$encounter_child->physical_comments_fem_pulses ||
				$encounter_child->physical_result_general_appearance ||
				$encounter_child->physical_result_nutrition ||
				$encounter_child->physical_result_skin ||
				$encounter_child->physical_result_head_neck_nodes ||
				$encounter_child->physical_result_eyes_eq_reflex ||
				$encounter_child->physical_result_ent_hearing ||
				$encounter_child->physical_result_mouth_dental ||
				$encounter_child->physical_result_chest_lungs ||
				$encounter_child->physical_result_heart ||
				$encounter_child->physical_result_abdomen ||
				$encounter_child->physical_result_external_genitalia ||
				$encounter_child->physical_result_back ||
				$encounter_child->physical_result_extremities_hips ||
				$encounter_child->physical_result_neurological ||
				$encounter_child->physical_result_fem_pulses 
			) ? true : false;
			
			$encounter_child->show_tobacco = (
				$encounter_child->tobacco_patient_exposed || 
				$encounter_child->tobacco_used_by_patient || 
				$encounter_child->tobacco_prevention_referred 
			) ? true : false;
			
			$encounter_child->ethnic_string = isset($this->settings_ethnic_codes[$encounter_child->ethnic_code]) ? $this->settings_ethnic_codes[$encounter_child->ethnic_code] : '';

		}

		return $encounter_child;
	}

}
