<?php

/**
* @route:patient/related-files
*/
class Patient_Related_Files_Controller extends APP_User_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model([
			'Patient_Related_Files_Model' => 'Patient_Related_Files_DB',
			'Patient_Model' => 'Patient_DB'
		]);
	}

	/**
	 * @route:save
	 */
	function save()
	{	
		
		$list_types = implode(',',$this->Patient_Related_Files_DB->get_types());
		
		$this->form_validation
			->set_rules('patient_id', 'Paciente', 'required|xss_clean|trim|exist_data[patient.id]')
			->set_rules('type', 'Tipo', 'required|in_list['.$list_types.']')
			->set_rules('title', 'Titulo', 'trim|required|xss_clean|max_length[100]')
			->set_rules('document_for_done', 'Documento para Completar', 'trim|required|xss_clean|in_list[0,1]')
		;

		$encounterID = $this->input->post('encounter_id');
		
		if($encounterID>0)
		{
			$this->load->model(['Encounter_Model' => 'Encounter_DB']);
			$encounter = $this->Encounter_DB->get($encounterID);
			if(!$encounter)
			{
				return $this->template->json( [
					'status' => 0,
					'message' => 'Consulta no encontrada'
				] );
			}
			if($encounter->patient_id != $this->input->post('patient_id') )
			{
				return $this->template->json( [
					'status' => 0,
					'message' => 'La consulta no pertenece al paciente actual.'
				] );
			}
		}
		else
		{
			$encounterID = null;
		}

		$lastId = $this->db->select_max('id')->get('patient_related_files')->row();
		
		$lastID = $this->Patient_Related_Files_DB->getLastID();
		
		$conf = [
			'allowed_types'    => 'gif|jpg|png|pdf',
			'upload_path'      => $this->patient_path($this->input->post('patient_id')),
			'file_name'        => "patientchart_".uniqid()."_{$lastID}",
			'file_ext_tolower' => true,
			'overwrite'		   => true,
			'max_size'		   => '524288', //512M settings on .htaccess ##php_value post_max_size 256M
		];

		$this->load->library('upload', $conf);

		if( $this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();
		} 
		else if( !$this->upload->do_upload('file', $conf ) )
        {
        	$response['conf']  = $conf;
			$response['message']   = $this->upload->display_errors();
        }
		else
		{
			
			$data = $this->upload->data();
			
			$this->Patient_Related_Files_DB->encounter_id      = $encounterID;
			$this->Patient_Related_Files_DB->title             = $this->input->post('title');
			$this->Patient_Related_Files_DB->patient_id        = $this->input->post('patient_id');
			$this->Patient_Related_Files_DB->type              = $this->input->post('type');
			$this->Patient_Related_Files_DB->document_for_done = $this->input->post('document_for_done');
			$this->Patient_Related_Files_DB->file_name         = $data['file_name'];
			$this->Patient_Related_Files_DB->user_id_created   = $this->current_user->id;
			
			$id = $this->Patient_Related_Files_DB->save();
			$related_file = $this->Patient_Related_Files_DB->getInfo( $id );
			//$related_file->create_at = date('m/d/Y', strtotime($related_file->create_at)); 
			
			$response = [
				'message' => 'Archivo almacenado',
				'status' => 1,
				'related_file' => $related_file,
			];
		}

		return $this->template->json( $response );
	}

	/**
	 * @route:open/(:num)
	 */
	function open( $ID )
	{
		if ( !$file = $this->Patient_Related_Files_DB->get( $ID ) ) 
		{
			show_error('Archivo no encontrado',404);
		}

		$fstring = $this->patient_path( $file->patient_id ) .'/'. $file->file_name;
		
		if(!is_file($fstring))
		{
			show_error("Archivo de paciente no encontrado",404);
		}
		
		$this->template->render_file( $fstring );

	}

	/**
	 * @route:{post}remove
	 */
	function remove()
	{

		$id = $this->input->post('id');
		
		if ( !$file = $this->Patient_Related_Files_DB->get( $id ) ) 
		{
			return $this->template->json( [
				'message' => 'Paciente no encontrado'
			]);
		}

		$this->load->model([
			'Patient_Warnings_Model' => 'Patient_Warnings_DB',
			'Encounter_Results_Model' => 'Encounter_Results_DB'
		]);

		$this->form_validation
			->set_rules('pin','Pin de usuario','required|trim|pin_verify')
			->set_rules('reason_delete','Razón de eliminar','trim|xss_clean|required|max_length[120]')
		;

		if($this->form_validation->run() === FALSE )
		{
			return $this->template->json( [
				'message' => $this->form_validation->error_string()
			]);
		}
		else
		{	
			
			$result = $this->Encounter_Results_DB->getRowBy([
				'patient_id' => $file->patient_id,
				'file_name' => $file->file_name,
				'status' => 5
			]);


			if($result) //back to recive if exists
			{
				$this->Encounter_Results_DB->status        = 4;
				$this->Encounter_Results_DB->done_date     = '0000-00-00';
				$this->Encounter_Results_DB->done_nickname = '';
				$this->Encounter_Results_DB->save($result->id);
			}

			// just for evidence
			$this->Patient_Warnings_DB->patient_id  = $file->patient_id;
			$this->Patient_Warnings_DB->create_at   = date('Y-m-d H:i:s');
			$this->Patient_Warnings_DB->description = 'File removed ['.$file->title.'] ' . $this->input->post('reason_delete');
			$this->Patient_Warnings_DB->status      = 1;
			$this->Patient_Warnings_DB->user_create = $this->current_user->nick_name;
			$this->Patient_Warnings_DB->save();

			//if result not exist
			$pathCurrentFile = $this->patient_path($file->patient_id).'/'.$file->file_name;
			
			//Keep Data
			copy($pathCurrentFile, $this->deleted_path($file->patient_id, $file->file_name) );
			
			if(!$result)
			{	
				//Delete File
				unlink($pathCurrentFile);
			}

			// remove data
			$this->Patient_Related_Files_DB->delete( $file->id );
			

			return $this->template->json( [
				'status' => 1,
				'message' => 'Archivo removido <b>'.$file->title.'</b>'
			]);
		}
	}

	/**
	 * @route:{get}open/(:num)/preview
	 */
	function open_preview( $ID )
	{
		if($ID === '$1' )
		{	
			$this->template->render_file( 'no-photo-available-md.png' );
			exit;
		}

		if ( !$file = $this->Patient_Related_Files_DB->get( $ID ) ) 
		{
			$this->template->render_file( 'no-photo-available-md.png' );
		}
		
		$filePath = $this->patient_path($file->patient_id).'/'.$file->file_name;
		
		if( !file_exists($filePath) || $file->file_name==='')
		{	
			$this->template->render_file( 'no-photo-available-md.png' );
		}
		else
		{
			$this->template->render_preview( $filePath );
		}
	}

	/**
	 * @route:__avoid__
	 */
	function index()
	{
		$this->template
			->modal('patient/relatedfiles/modal-delete-files',[
				'title' => 'Eliminar archivo',
				'size' => 'modal-xl'
			] )
			->modal('patient/relatedfiles/modal-preview-files',[
				'title' => 'Vista previa',
				'size' => 'modal-xl'
			] )
			->body([
				'ng-app' => 'app_relatedfiles',
				'ng-controller' => 'ctrl_relatedfiles',
			])
			->js('patient/relatedfiles')
			->render( 'patient/relatedfiles/view-panel-list' );
	}
	
	/**
	 * @route:{get}search/(:num)/(:num)
	 */
	function search($maxRecords = 0, $page = 0)
	{
		$result = $this->Patient_Related_Files_DB->getPagination( 
			$maxRecords, 
			$page, 
			$this->input->get('sort'), 
			$this->input->get('filters')
		);
		
		return $this->template->json( $result );
	}

	/**
	 * @route:createpatient
	 */
	public function createPatient()
	{
		$query    = $this->db->select('id')->from('patient')->order_by('id');
		$patients = $query->get()->result_array();
		$files    = [];

		foreach ($patients as $patient) {
			$path = $this->patient_path($patient['id']);
			if(!file_exists($path))
			{
				mkdir( $path );
				$files[] = $path;
			}
		}
		
		pr($files);
	}

	/**
	 * @route:{post}(:num)/checkDone
	 */
	function done( $related_file_id )
	{
		$this->validate_access(['manager','medic']);
		
		if ( !$file = $this->Patient_Related_Files_DB->get( $related_file_id ) ) 
		{
			return $this->template->json([
				'message' => 'Documento no encontrado'
			]);
		}
		else if($file->document_for_done != 1 ) 
		{
			return $this->template->json([
				'message' => 'Estado no disponible para completado'
			]);
		}

		$this->form_validation->set_rules('pin', 'PIN', 'required|xss_clean|pin_verify');
		if($this->input->post('contact_patient') == 1)
		{
			$this->form_validation->set_rules('reason_contact', 'Razón de contacto', 'trim|required|xss_clean|max_length[1500]');
		}

		if( $this->form_validation->run() === FALSE )
		{
			return $this->template->json([
				'message' => $this->form_validation->error_string()
			]);
		}
		else
		{
			if($this->input->post('contact_patient') == 1 )
			{
				$this->load->model(['Patient_Contact_Model' => 'Patient_Contact_DB']);
				$this->Patient_Contact_DB->create_user_by   = $this->current_user->id;
				$this->Patient_Contact_DB->reason           = $this->input->post('reason_contact');
				$this->Patient_Contact_DB->patient_id       = $file->patient_id;
				$this->Patient_Contact_DB->create_at        = date('Y-m-d H:i:s');
				$this->Patient_Contact_DB->related_file_id  = $file->id;
				$this->Patient_Contact_DB->save();
			}
			
			$this->Patient_Related_Files_DB->document_for_done = 0;
			$this->Patient_Related_Files_DB->done_date         = date('Y-m-d H:i:s');
			$this->Patient_Related_Files_DB->done_nick_name    = $this->current_user->nick_name;
			$this->Patient_Related_Files_DB->save( $file->id );

			return $this->template->json([
				'status' => 1,
				'message' => 'Documento asignado COMPLETO',
				'pending_check_docs' => $this->Menu_DB->get_pending_results_check()
			]);
		}

	}

	/**
	 * @route:{get}docsDeleted
	 */
	function docsDeleted()
	{
		$deletedPath = FCPATH ."../private/uploads/deleted/";

		$files     = scandir($deletedPath);
		$documents = Array();

		$dTime = new \DateTime();
		$dTime->modify( "-2 months" );
		
		foreach ($files as $fileName ) {
			
			if(!is_file( $deletedPath . $fileName))
				continue;

			$fileData = explode("_", $fileName );

			$patientId = str_replace("P", "" , $fileData[0] );
			$date      = str_replace("D", "" , $fileData[1] );
			$userId    = str_replace("U", "" , $fileData[2] );
			$group     = $fileData[3];

			$dateTime 	   = new \DateTime($date);

			if( $dTime->format('YmdHi') >= $dateTime->format('YmdHi') )
			{
				unlink($deletedPath . $fileName);
				continue;
			}
			
			$patient = $this->db->select('name, last_name')->from('patient')->where(['id' => $patientId])->get()->row_array();
			
			$user = $this->db->select('nick_name')->from('user')->where(['id' => $userId])->get()->row_array();
			
			$documents[] = Array(
				'patient_id' => $patientId,
				'patient' => $patient['name'].' '.$patient['last_name'],
				'date' => $date,
				'user' => $user['nick_name'],
				'group' => $group,
				'file' => $fileName
			);
		}
		
		//sort by date
		$docDate = array_column($documents, 'date');
		array_multisort($docDate, SORT_DESC, $documents);

		$this->template
			->render( 'patient/relatedfiles/view-panel-deleted' , [
				'documents' => $documents
			]);
	}

	/**
	 * @route:{get}docDownload
	 */
	function docDownload()
	{
		$fileName = FCPATH ."../private/uploads/deleted/".$this->input->get('fileName');
		
		if (file_exists($fileName)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.basename($fileName));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($fileName));
		    ob_clean();
		    flush();
		    readfile($fileName);
		    exit;
		}
	}

	/**
	 * @route:download
	 */
	function download()
	{
		$files = [];
	 
		foreach ($this->input->get('ids') as $key => $value) 
		{
			if ( !$file = $this->Patient_Related_Files_DB->get( $value ) ) 
			{
				show_error('File not found',404);
			}

			$fstring = $this->patient_path( $file->patient_id ) .'/'. $file->file_name;
			
			if(!is_file($fstring))
			{
				show_error("Patient file not found",404);
			}
			
			$files[] = $fstring;

		} 
		include FCPATH."../system/libraries/PDFMerger/PDFMerger.php";

		$merge   = new PDFMerger\PDFMerger;;
 		$fileimg = FCPATH . "../private/uploads/temp/temp_";
 		$index   = 0;
 		$delFile = [];

		foreach ($files as $file) 
		{	  
			$info = explode(".",$file);

			if($info[count($info)-1] == "pdf")
			{
				$merge->addPDF($file);
			}
			else
			{
				error_reporting(E_ALL & ~E_NOTICE);
				ini_set('display_errors', 0);
				ini_set('log_errors', 1);
				
				$dimensions = getimagesize($file); 
 				$orient     = "P";

 				if($dimensions[0] >= $dimensions[1])
 				{
 					$orient = "L";
 				}

				$pdf = new FPDF($orient,'mm',array($dimensions[0]/3,$dimensions[1]/3));
				$pdf->SetPrintHeader(false);  
				$pdf->AddPage();  
				$pdf->Image($file, 5, 5, ($dimensions[0]),($dimensions[1])); 
				 
				while(file_exists($fileimg.$i.".pdf"))
				{
					$i++;
				}

				$delFile[] = $fileimg.$i.".pdf"; 
				
				$pdf->Output($fileimg.$i.".pdf", 'F');
				$merge->addPDF($fileimg.$i.".pdf"); 
			}
		} 
		
		$merge->merge("download","allFiles.pdf");   

		foreach ($delFile as $key => $file) 
		{ 
			unlink($file);
		} 
	}
}