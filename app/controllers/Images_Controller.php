<?php
/**
* @route:images
*/
class Images_Controller extends CI_Controller
{	
	/**
	 * @route:name/(:any)
	 */
	function name( $name = '' )
	{	
		$file = FCPATH . '../private/storage/uploads/'.$name;
		if( !file_exists($file))
		{	
			show_error('Page not found', 404 );
		}
		$this->template->render_image( $file );
	}
	
	/**
	 * @route:patient/(:num)
	 */
	function patient( $patient_id )
	{	
		$this->load->model(['Patient_Model' => 'Patient_DB']);
		$patient = $this->Patient_DB->get( $patient_id );
		$file = FCPATH . '../private/uploads/patient/'.$patient->imagen;
		if( $patient->imagen==='' || !file_exists($file))
		{		
			$file = FCPATH . '../private/storage/uploads/patient.png';
		}
		
		$this->template->render_image( $file );
	}
}