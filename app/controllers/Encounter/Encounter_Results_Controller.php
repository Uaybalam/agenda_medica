<?php
/**
* @route:encounter/results
*/
class Encounter_Results_Controller extends APP_User_Controller
{
	
	function __construct()
	{	
		parent::__construct();

		$this->load->model([
			'Encounter_Model' => 'Encounter_DB',
			'Encounter_Results_Model' => 'Encounter_Results_DB',
			'Patient_Related_Files_Model' => 'Patient_Related_Files_DB',
		]);
		
		$this->Encounter_DB->set_user($this->current_user );
	}
	
	/**
	 * @route:{get}open/(:num)
	 * @route:{get}open
	 */
	function open( $ID )
	{
		
		if($ID === '$1' )
		{	
			$this->template->render_file( 'no-photo-available-md.png' );
			exit;
		}
		
		if( !($lab = $this->Encounter_Results_DB->get($ID)) )
		{
			$this->template->render_file( 'no-photo-available-md.png' );
			exit;
		}
		
		$encounter 		= $this->Encounter_DB->get( $lab->encounter_id );
		$FileLaboratory = $this->patient_path($encounter->patient_id).'/'.$lab->file_name;
		
		if( !file_exists($FileLaboratory) || $lab->file_name==='')
		{
			//$this->template->render_preview( $FileLaboratory );
			$this->template->render_file( 'no-photo-available-md.png' );
		}
		else
		{
			$this->template->render_file( $FileLaboratory );
		}
	}

	/**
	 * @route:{get}(:num)/open-preview
	 */
	function open_preview( $ID )
	{
		
		if($ID === '$1' )
		{	
			$this->template->render_file( 'no-photo-available-md.png' );
			exit;
		}
		
		if( !($lab = $this->Encounter_Results_DB->get($ID)) )
		{
			$this->template->render_file( 'no-photo-available-md.png' );
			exit;
		}
		
		$encounter 		= $this->Encounter_DB->get( $lab->encounter_id );
		$FileLaboratory = $this->patient_path($encounter->patient_id).'/'.$lab->file_name;
		
		if( !file_exists($FileLaboratory) || $lab->file_name==='')
		{	
			$this->template->render_file( 'no-photo-available-md.png' );
		}
		else
		{
			$this->template->render_preview( $FileLaboratory );
		}
	}

	
	/**
	 * @route:{post}save/(:num)
	 */
	function save( $ID )
	{
		$response = ['status' => 0];

		if( ! ( $encounter = $this->Encounter_DB->get_info( $ID ) ) )
		{	
			show_error('Encounter not found', 404);
		}

		$this->form_validation
			->set_rules('title','Titulo','trim|required|xss_clean|max_length[75]')
			->set_rules('comments','Indicaciones','trim|xss_clean')
			->set_rules('type_result','Tipo','trim|required|in_list['.implode(',',$this->Encounter_Results_DB->get_results_availible()).']')
		;

		if($this->form_validation->run() === false )
		{
			$response['message'] = $this->form_validation->error_string();
		}
		else if($encounter->status != 1 )
		{	
			$response['message'] = 'Estatus de consulta firmado';
		}
		else
		{		
			
			$this->Custom_Setting_DB->insertIfNew($this->input->post('title'), 'setting_request');

			if( $this->input->post('id') > 0 )
			{	
				$this->Encounter_Results_DB->title       = $this->input->post('title');
				$this->Encounter_Results_DB->type_result = $this->input->post('type_result');
				$this->Encounter_Results_DB->comments    = $this->input->post('comments');
				$this->Encounter_Results_DB->save( $this->input->post('id') );
				
				$response = [
					'status' => 1,
					'message' => 'Resultado fue actualziado',
					'result' => $this->Encounter_Results_DB->get( $this->input->post('id') )
				];

				$this->Encounter_DB->set_activity( $ID , 'encounter_result_edit');
			}	
			else
			{	
				$this->Encounter_Results_DB->encounter_id = $encounter->id;
				$this->Encounter_Results_DB->patient_id   = $encounter->patient_id;
				$this->Encounter_Results_DB->title        = $this->input->post('title');
				$this->Encounter_Results_DB->type_result  = $this->input->post('type_result');
				$this->Encounter_Results_DB->comments     = $this->input->post('comments');

				$result_id = $this->Encounter_Results_DB->save();
				
				$response = [
					'status' => 1,
					'message' => 'Resultado fue agregado',
					'result' => $this->Encounter_Results_DB->get( $result_id )
				];

				$this->Encounter_DB->set_activity( $ID , 'encounter_result_add');
			}

			
		}



		$this->template->json( $response );
	}

	/**
	 * @route:{get}delete/(:num)
	 */
	function delete( $ID )
	{
		$response['status'] = 0;
		
		if( !($lab = $this->Encounter_Results_DB->get($ID)) )
		{
			show_error('Result not found', 404);
		}
		else if( $lab->status != 0)
		{
			show_error('Result status active', 404);
		}
		
		$encounter = $this->Encounter_DB->get( $lab->encounter_id );
		if($encounter->status != 1 )
		{
			show_error('Encounter status signed', 404);
		}

		$this->Encounter_Results_DB->delete( $ID );
		$response = [
			'status' => 1, 
			'message' => 'Result was deleted',

		]; 
		
		$this->Encounter_DB->set_activity( $lab->encounter_id , 'encounter_result_remove');

		$this->template->json( $response );
	}

	/**
	 * @route:{post}set-refused/(:num)
	 */
	function set_refused( $ID )
	{
		if( !($result = $this->Encounter_Results_DB->get( $ID ) ) )
		{
			return $this->template->json([
				'message' => 'Result not found'
			]);
		}
		else if(!in_array($result->status, [ 2, 3 ]) )
		{
			return $this->template->json([
				'message' => "Result isn't status correct"
			]);
		}
		
		$this->form_validation->set_rules('pin','User PIN','required|trim|pin_verify');

		if($this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();	
		}
		else
		{	

			$this->Encounter_Results_DB->status           = 6;
			$this->Encounter_Results_DB->refused_date     = date('Y-m-d H:i:s');
			$this->Encounter_Results_DB->refused_nickname = $this->current_user->nick_name;
			$this->Encounter_Results_DB->save( $ID );

			$response = [
				'status' => 1,
				'message' => 'Result was asigned refused',
				'result' => $this->Encounter_Results_DB->get_info( $ID ),
				'pending' =>  [
					'waiting' => $this->Menu_DB->get_pending_results_waiting(),
					'check' => $this->Menu_DB->get_pending_results_check()
				]
			];
		}

		$this->template->json( $response );
	}

	/**
	 * @route:{post}upload/(:num)
	 */
	function upload( $ID )
	{
		
		$result = $this->Encounter_Results_DB->get( $ID );

		if( !$result )
		{	
			show_error('Result not found',500);
		}
		
		$encounter = $this->Encounter_DB->get( $result->encounter_id );
		
		$conf = [
			'allowed_types'    => 'gif|jpg|png|pdf|jpeg',
			'upload_path'      => $this->patient_path($encounter->patient_id),
			'file_name'        => "result_{$ID}",
			'file_ext_tolower' => TRUE,
			'overwrite'		   => TRUE
		];
			
		$this->load->library('upload', $conf);
			
		if(!in_array($result->status, [ 1 , 2 , 3, 4 ] ) )
		{	
			$status = $this->Encounter_Results_DB->get_status();
			$response['message'] = "Result cann't upload with status ".$status[$result->status];	
		}
		else if (!$this->upload->do_upload('file', $conf ))
        {
			$response['message']   = $this->upload->display_errors();
        }
		else
		{
			
			$data = $this->upload->data();
			
			$this->Encounter_Results_DB->file_name        = $data['file_name'];
			$this->Encounter_Results_DB->status           = 4;
			$this->Encounter_Results_DB->recive_date     = date('Y-m-d H:i:s');
			$this->Encounter_Results_DB->recive_nickname = $this->current_user->nick_name;
			$this->Encounter_Results_DB->save( $ID );
			
			$response = [
				'status' => 1,
				'message' => 'Result was lodaded',
				'result' => $this->Encounter_Results_DB->get_info( $ID ),
				'pending' =>  [
					'waiting' => $this->Menu_DB->get_pending_results_waiting(),
					'check' => $this->Menu_DB->get_pending_results_check()
				]
			];
		}
		
		$this->template->json( $response , 'string' );
	}

	/**
	 * @route:remove-results/(:num)
	 */
	function remove_results( $ID )
	{
		
		$result = $this->Encounter_Results_DB->get( $ID );
		
		if( !$result )
		{	
			show_error('Result not found',500);
		}
		else if($result->status != 4 || $result->file_name==='' )
		{
			$this->template->json( [
				'status' => 0,
				'message' => 'Result status not availible'
			] );
		}

		$encounter = $this->Encounter_DB->get( $result->encounter_id );
		
		@unlink($this->patient_path($encounter->patient_id).'/'.$result->file_name);
		
		$this->Encounter_Results_DB->file_name       = '';
		$this->Encounter_Results_DB->title_document  = '';
		$this->Encounter_Results_DB->status          = 3;
		$this->Encounter_Results_DB->recive_date     = '0000-00-00';
		$this->Encounter_Results_DB->recive_nickname = '';
		$this->Encounter_Results_DB->save( $ID );
		
		$this->template->json( [
			'status' => 1,
			'message' => 'Result removed',
			'result' => $this->Encounter_Results_DB->get_info( $ID ),
			'pending' =>  [
				'waiting' => $this->Menu_DB->get_pending_results_waiting(),
				'last_query' => $this->db->last_query(), 
				'check' => $this->Menu_DB->get_pending_results_check()
			]
		] , false );
	}
	
	/**
	 * @route:{post}(:num)/checkDone
	 */
	function checkDone( $result_id  )
	{
		$this->validate_access(['manager','medic']);
		
		if( !($result = $this->Encounter_Results_DB->get($result_id)) )
		{
			return $this->template->json( ['message' => 'Result not found'] );
		}
		else if( $result->status != 4 || $result->file_name ==='' )
		{
			return $this->template->json( ['message' => 'Result isn`t status Results Recive or not have file, refresh page'] );
		}

		$this->form_validation->set_rules('pin','User PIN','required|trim|pin_verify');
		if($this->input->post('contact_patient') == 1)
		{
			$this->form_validation->set_rules('reason_contact', 'Reason Contact', 'trim|required|xss_clean|max_length[1500]');
		}

		if($this->form_validation->run() === FALSE )
		{
			return $this->template->json([
				'message' => $this->form_validation->error_string() 
			]);	
		}
		else
		{


			$this->Encounter_Results_DB->status        = 5;
			$this->Encounter_Results_DB->done_date     = date('Y-m-d H:i');
			$this->Encounter_Results_DB->done_nickname = $this->current_user->nick_name;
			$this->Encounter_Results_DB->save( $result_id );
			
			$this->Patient_Related_Files_DB->title           = $result->title;
			$this->Patient_Related_Files_DB->user_id_created = $this->current_user->id;
			$this->Patient_Related_Files_DB->type            = $result->type_result;
			$this->Patient_Related_Files_DB->file_name       = $result->file_name;
			$this->Patient_Related_Files_DB->patient_id      = $result->patient_id;
			$this->Patient_Related_Files_DB->create_at       = date('Y-m-d H:i');
			$related_file_id = $this->Patient_Related_Files_DB->save();
			
			if($this->input->post('contact_patient') == 1 )
			{
				$this->load->model(['Patient_Contact_Model' => 'Patient_Contact_DB']);

				$this->Patient_Contact_DB->related_file_id = $related_file_id;
				$this->Patient_Contact_DB->create_user_by  = $this->current_user->id;
				$this->Patient_Contact_DB->reason          = $this->input->post('reason_contact');
				$this->Patient_Contact_DB->patient_id      = $result->patient_id;
				$this->Patient_Contact_DB->create_at       = date('Y-m-d H:i:s');
				$this->Patient_Contact_DB->save();
			}

			$response = [
				'status' => 1,
				'message' => 'Result was asigned done',
				'pending_check_docs' => $this->Menu_DB->get_pending_results_check()
			];
			
			return $this->template->json( $response );
			
		}
	}
	
	/**
	 * @route:set-done/(:num)
	 */
	function set_done( $ID )
	{

		$this->validate_access(['manager','medic']);

		if( !($result = $this->Encounter_Results_DB->get($ID)) )
		{
			$this->template->json( ['message' => 'Result not found'] );
		}
		else if( $result->status != 4 || $result->file_name ==='' )
		{
			$this->template->json( ['message' => 'Result isn`t status Results Recive or not have file, refresh page'] );
		}

		$this->form_validation->set_rules('pin','User PIN','required|trim|pin_verify');
		
		if($this->form_validation->run() === FALSE )
		{
			$response['message'] = $this->form_validation->error_string();	
		}
		else
		{

			$this->Encounter_Results_DB->status        = 5;
			$this->Encounter_Results_DB->done_date     = date('Y-m-d H:i');
			$this->Encounter_Results_DB->done_nickname = $this->current_user->nick_name;
			$this->Encounter_Results_DB->save( $ID );
			
			$this->Patient_Related_Files_DB->title           = $result->title;
			$this->Patient_Related_Files_DB->user_id_created = $this->current_user->id;
			$this->Patient_Related_Files_DB->type            = $result->type_result;
			$this->Patient_Related_Files_DB->file_name       = $result->file_name;
			$this->Patient_Related_Files_DB->patient_id      = $result->patient_id;
			$this->Patient_Related_Files_DB->create_at       = date('Y-m-d H:i');
			$this->Patient_Related_Files_DB->save();
			
			$response = [
				'status' => 1,
				'message' => 'Result was asigned done',
				'result' => $this->Encounter_Results_DB->get_info( $ID ),
				'pending_check_docs' => $this->Menu_DB->get_pending_results_check()
			];
			
		}

		$this->template->json( $response );
	}
}

