<?php
namespace libraries;
/**
* 
*/

class Administration
{
	protected static $_instance = null;

	public static function init()
	{
		if (self::$_instance === null  ) {
            self::$_instance = new Administration();
        }

		$CI = \get_instance();

		$CI->load->model('Administration_Model');

		self::$_instance->CI = &$CI;

		return self::$_instance;
	}

	public static function getValue( $item )
	{
		self::init();
		
		$result= self::$_instance->CI->db->select('value')->from('administration')
			->where(['name' => $item ])->get()->row();
		
		if( $result )
		{
			return $result->value;
		}
		else
		{
			return '';
		}
	}
	
	public static function install()
	{
		self::init();

		$defaultValues = [];
		//Billing
		$defaultValues[] = [
			'group'  => 'Billing',
			'name'   => 'billing_alert_time',
			'title'  => 'Días para notificar',
			'value'  => 30,
			'helper' => 'Si hay facturas pendientes finalizadas, puedes ver las notificaciones en la barra de menú',
		];
		$defaultValues[] = [
			'group'  => 'Billing',
			'name'   => 'billing_facility_telephone',
			'title'  => 'Teléfono de la instalación',
			'value'  => '',
			'helper' => '',
		];

		$defaultValues[] = [
			'group' => 'Billing',
			'name' => 'billing_font_size',
			'value' => 8,
			'helper' => '',
			'title' => 'Tamaño de fuente en PDF',
		];
		$defaultValues[] = [
			'group' => 'Billing',
			'name' => 'PDF_Margin_Top',
			'value' => 42,
			'helper' => 'Margen superior para imprimir Facturacion PDF',
			'title' => 'Margen superior para imprimir'
		];
		$defaultValues[] = [
			'group' => 'Billing',
			'name' => 'PDF_Margin_Left',
			'value' => 6,
			'helper' => 'Margen izquierdo para imprimir Facturacion PDF',
			'title' => 'Margen izquierdo para imprimir'
		];
		/*$defaultValues[] = [
			'group' => 'Billing',
			'name' => 'billing_provider_insurances',
			'value' => 'Medical, family pact',
			'helper' => 'NPI y Nombre de la configuración del sistema, de lo contrario establezca NPI y Nombre del Dr. que firma el encuentro',
			'title'  => 'Provider Insurances'
		];
		$defaultValues[] = [
			'group' => 'Billing',
			'name' => 'billing_provider_npi',
			'title' => 'Provider NPI'
		];
		$defaultValues[] = [
			'group' => 'Billing',
			'name' => 'billing_provider_name',
			'helper' => 'Name Owner',
			'title' => 'Provider Name'
		];*/
		$defaultValues[] = [
			'group' => 'Billing',
			'name' => 'billing_provider_signature',
			'helper' => 'Para imprimir remisiones',
			'title' => 'Firma de proveedor',
		];
		$defaultValues[] = [
			'group' => 'Billing',
			'name' => 'billing_provider_fax',
			'helper' => 'Para imprimir remisiones',
			'title' => 'Fax del proveedor'
		];
		$defaultValues[] = [
			'group' => 'Billing',
			'name' => 'billing_office_contact',
			'helper' => 'Para imprimir remisiones',
			'title' => 'Contacto de oficina'
		];
		/*$defaultValues[] = [
			'group' => 'Billing',
			'name' => 'billing_group_npi',
			'value' => 'ABXWDT',
			'title' => 'Group NPI/ID'
		];
		$defaultValues[] = [
			'group' => 'Billing',
			'name' => 'billing_federal_tax',
			'value' => 987654321,
			'title' => 'Billing Federal Tax'
		];*/
		
		//Facility
		/*$defaultValues[] = [
			'group' => 'Billing Facility',
			'name' => 'billing_facility_id',
			'title' => 'Facility ID'
		];
		$defaultValues[] = [
			'group' => 'Billing Facility',
			'name' => 'billing_facility_name',
			'title' => 'Facility Name'
		];
		$defaultValues[] = [
			'group' => 'Billing Facility',
			'name' => 'billing_facility_streetAddr',
			'title' => 'Facility Street Address'
		];
		$defaultValues[] = [
			'group' => 'Billing Facility',
			'name' => 'billing_facility_city',
			'title' => 'Facility City'
		];
		$defaultValues[] = [
			'group' => 'Billing Facility',
			'name' => 'billing_facility_state',
			'title' => 'Facility State'
		];
		$defaultValues[] = [
			'group' => 'Billing Facility',
			'name' => 'billing_facility_zip',
			'title' => 'Facility Zip'
		];
		$defaultValues[] = [
			'group' => 'Billing Facility',
			'name' => 'billing_facility_citystatezip',
			'title' => 'Facility City-State-Zip'
		];
		$defaultValues[] = [
			'group' => 'Billing Facility',
			'name' => 'billing_facility_npi',
			'title' => 'Facility NPI'
		];*/

		//Suppliers
		/*
		$defaultValues[] = [
			'group' => 'Billing Supplier',
			'name' => 'billing_supplier_npi',
			'title' => 'Supplier NPI'
		];
		$defaultValues[] = [
			'group' => 'Billing Supplier',
			'name' => 'billing_supplier_name',
			'title' => 'Supplier Name'
		];
		$defaultValues[] = [
			'group' => 'Billing Supplier',
			'name' => 'billing_supplier_streetAddr',
			'title' => 'Supplier Street Addres'
		];
		$defaultValues[] = [
			'group' => 'Billing Supplier',
			'name' => 'billing_supplier_city',
			'title' => 'Supplier City'
		];
		$defaultValues[] = [
			'group' => 'Billing Supplier',
			'name' => 'billing_supplier_state',
			'title' => 'Supplier State'
		];
		$defaultValues[] = [
			'group' => 'Billing Supplier',
			'name' => 'billing_supplier_zip',
			'title' => 'Supplier Zip'
		];
		$defaultValues[] = [
			'group' => 'Billing Supplier',
			'name' => 'billing_supplier_citystatezip',
			'title' => 'Supplier City-State-Zip'
		];
		$defaultValues[] = [
			'group' => 'Billing Supplier',
			'name' => 'billing_supplier_phone',
			'title' => 'Supplier Phone'
		];*/

		

		$defaultValues[] = [
			'group'  => 'Encounter',
			'name'   => 'chief_complaint_default',
			'value'  => 'Paciente #{id}, {gender} de {age}, Esta aqui para una cita {visit_type}',
			'helper' => 'Palabras claves dispoible: {id}, {gender}, {age}, {visit_type}, {name}, {last_name}, {date_of_birth}',
			'title'  => 'Motivo de consulta inicial'
		];
		
		$defaultValues[] = [
			'group'  => 'Encounter',
			'name'   => 'available_months_child',
			'value'  => 240,
			'helper' =>  'Ejemplo: 240 meses es lo mismo que 20 años; después de este tiempo, no se mostrará la opción Examen Físico Infantil',
			'title' => 'Examen Físico Infantil Habilitado',
		];

		/*$defaultValues[] = [
			'group'  => 'Encounter',
			'name'   => "questions_ins_inmigration",
			'value'  => "Lawyer:\nSSN:\nA Number:\nYears in USA:\nCity:\nCountry:\nHX of PPD:\nTreatment:\nVaricella:\n",
			'helper' => '',
			'title' => 'Questions INS Inmigration',
			'type' => 'textarea'
		];*/

		$defaultValues[] = [
			'group'  => 'Appointment',
			'name'   => 'minutes_waiting_doctor',
			'value'  => 5,
			'helper' => 'La lista del Libro de Citas muestra un color rojo después de este tiempo en minutos',
			'title'  => 'Minutos para la alerta',
		];
		
		$defaultValues[] = [
			'group'  => 'Appointment',
			'name'   => 'minutes_late_to_appointment',
			'value'  => 5,
			'helper' => 'Minutos para mostrar el ícono en rojo si el paciente llega tarde a la cita',
			'title'  => 'Minutos de retraso para la cita.',
		];

		$defaultValues[] = [
			'group'  => 'Appointment',
			'name'   => 'opend',
			'value'  => "09:00",
			'helper' => 'Horario de inicio de servicio de citas',
			'title'  => 'Inicio de horario de citas.',
			'type'   => 'time'
		];

		$defaultValues[] = [
			'group'  => 'Appointment',
			'name'   => 'closed',
			'value'  => "18:00",
			'helper' => 'Horario de finalización de servicio de citas',
			'title'  => 'Finalización de horario de citas.',
			'type'   => 'time'
		];

		$defaultValues[] = [
			'group'  => 'Appointment',
			'name'   => 'appointment_time',
			'value'  => "30",
			'helper' => 'Tiempo de duración de citas',
			'title'  => 'Tiempo por cita',
			'type'   => 'number'
		];
		
		$defaultValues[] = [
			'group'  => 'Contact Patient',
			'name'   => 'communication_whitout_answer',
			'value'  => 'Mensaje para {name} {last_name}, ultimo contacto fue {last_date}',
			'helper' => 'Palabras claves dispoibles: {last_date}, {name}, {last_name}',
			'title'  => 'Sin respuesta',
			'type'   => 'textarea'
		];
		/*
		$defaultValues[] = [
			'group'  => 'Billing',
			'name'   => 'reserved_for_local_use',
			'value'  => '',
			'helper' => 'Name of PA or Dr. Lopez, this is selected in billing',
			'title'  => 'Reservado para uso local',
		];
		
		$defaultValues[] = [
			'group'  => 'Billing',
			'name'   => 'phys_npi',
			'value'  => '',
			'helper' => ' NPI of Dr. Lopez',
			'title'  => 'Phys NPI for Refer and Super',
		];
		*/

		$defaultValues[] = [
			'group'  => 'Clinica o consultorio',
			'name'   => 'name',
			'value'  => 'Dirmedal',
			'helper' => 'Nombre de tu consultorio o clinica',
			'title'  => 'Nombre de clinica o consultorio',
		];

		$defaultValues[] = [
			'group'  => 'Clinica o consultorio',
			'name'   => 'logo',
			'value'  => 'isotipo.png',
			'helper' => 'Este logo aparecera en los archivos pdf',
			'title'  => 'Logo de clinica o consultorio', 
			'type'   => 'file'
		];

		$defaultValues[] = [
			'group'   => 'Clinica o consultorio',
			'name'    => 'time_zone',
			'value'   => 'America/Mexico_City',
			'helper'  => 'Zona horaria donde se encuentra la clinica',
			'title'   => 'Zona horaria', 
			'type'    => 'select',
			'options' => '{"America\/Chihuahua":"(GMT-07:00) Chihuahua","America\/Mazatlan":"(GMT-07:00) Mazatlan","America\/Mexico_City":"(GMT-06:00) Mexico City","America\/Monterrey":"(GMT-06:00) Monterrey"}'
		];

		$defaultValues[] = [
			'group'  => 'Clinica o consultorio',
			'name'   => 'address_clinic',
			'value'  => '',
			'helper' => 'Dirección de tu consultorio o clinica',
			'title'  => 'Dirección de clinica o consultorio',
			'type'   => 'textarea'
		];
		
		foreach ($defaultValues as $config ) {
			self::$_instance->insertConfig( $config );
		}
		
	}
	
	private function insertConfig( $config = null )
	{

		$group   = isset($config['group']) ?  $config['group'] : 'General';
		$name    = isset($config['name']) ?  $config['name'] : FALSE;
		$title   = isset($config['title']) ?  $config['title'] : $name;
		$value   = isset($config['value']) ?  $config['value'] : '';
		$type    = isset($config['type']) ?  $config['type'] : 'text';
		$helper  = isset($config['helper']) ?  $config['helper'] : '';
		$options = isset($config['options']) ?  $config['options'] : '';

		if(!$name)
		{
			return false;
		}

		$result = self::$_instance->CI->db->from('administration')->where([ 'name' => $name ])->get()->row();

        if( !$result ) 
        {	
        	self::$_instance->CI->db->insert('administration',[
				'group'   => $group,
				'name'    => $name,
				'value'   => $value,
				'helper'  => $helper,
				'title'   => $title,
				'type'    => $type,
				'options' => $options,
				'instance_id' => $_SESSION['User_DB']->instance_id 
			]);
        }
        else
        {
        	self::$_instance->CI->db->where([ 'name' => $name ])->update('administration',[
				'group'   => $group,
				'helper'  => $helper,
				'title'   => $title,
				'type'    => $type, 
				'options' => $options,
				'instance_id' => $_SESSION['User_DB']->instance_id 
			]);
        }
	}

}