<?php

class Encounter_Referrals_Model extends APP_Model
{		
	private $_status = [
        0 => 'Inicial', 
        1 => 'Nueva',
        2 => 'Pendiente',
        3 => 'Aprovada',
        4 => 'Denegada',    
        5 => 'Recogida',
        6 => 'Completada'
	];
    
	public function getStatus()
	{	
		return $this->_status;
	}
	
    public function getPagination( $itemsPerPage, $page, $sort, $filters )
    {
        $config = [
            'table' => $this->tableName(),
            'orderAvailable' => [
                'patient'    => 'patient.name',
                'refer_date' => 'str_to_date(encounter_referrals.refer_date,"%m/%d/%Y")',
                //'insurance'  => 'refer_insurance'
            ],
            'itemsPerPage' => $itemsPerPage,
            'page' => $page,
            'sort' => $sort,
            'filters' => $filters
        ];
        
        $columns = [
            'encounter_referrals.id',
            'encounter_referrals.status',
            'encounter_referrals.encounter_id',
            'encounter_referrals.patient_id',
            'encounter_referrals.comments',
            'encounter_referrals.reason',
            'encounter_referrals.speciality',
            'encounter_referrals.service',
            'encounter_referrals.acuity',
            'encounter_referrals.webticket',
            'encounter_referrals.diagnosis',
            'encounter_referrals.date_ipa_sent',
            'encounter_referrals.date_ipa_recived',
            'encounter_referrals.date_requested',
            'encounter_referrals.date_follow_up_appt',
            'encounter_referrals.date_specialist_appt',
            'encounter_referrals.date_patient_notify',
            'encounter_referrals.date_consultation_report',
            'encounter_referrals.requested_provider',
            'encounter_referrals.encounter_id',
            'encounter_referrals.refer_date',
            'encounter_referrals.user_created_nickname',
            'concat(patient.name," ",patient.last_name) as patient',
            'IF(encounter_referrals.encounter_id=0,encounter_referrals.insurance,encounter.insurance_title) as insurance',
        ];
        
        $pagination = new \libraries\Pagination( $config, $columns );

        return $pagination->retrieve( function( $qb, $pag, $type ) {
            
            $qb->join('patient', 'patient.id=encounter_referrals.patient_id','inner');
            $qb->join('encounter', 'encounter.id=encounter_referrals.encounter_id','left');
            
            $qb->where(['encounter_referrals.status !=' => 0 ]);
           
            if( $patient = $pag->getFilter('patient') )
                $qb->like('concat(patient.name," ",patient.last_name)', $patient );

            if( $patientID = $pag->getFilter('patient_id') )
                $qb->where('encounter_referrals.patient_id', $patientID );

            if( $referDate = $pag->getFilter('refer_date') )
                $qb->like( [ 'encounter_referrals.refer_date' => $referDate ] );
            
            if( $insurance = $pag->getFilter('insurance') )
                $qb->like( [ 'IF(encounter_referrals.encounter_id=0,encounter_referrals.insurance,encounter.insurance_title)' => $insurance ] );
            
            if( $reason = $pag->getFilter('reason'))
                $qb->like( [ 'encounter_referrals.reason' => $reason ] );
            
            if( $acuity = $pag->getFilter('acuity'))
                $qb->like( [ 'encounter_referrals.acuity' => $acuity ] );
            
            if( $webticket = $pag->getFilter('webticket'))
                $qb->like( [ 'encounter_referrals.webticket' => $webticket ] );
            
            if( $speciality = $pag->getFilter('speciality'))
                $qb->like( [ 'encounter_referrals.speciality' => $speciality ] );

            if( $comments = $pag->getFilter('comments'))
                $qb->like( [ 'encounter_referrals.comments' => $comments ] );  
            
            $status = $pag->getFilter('status');
            if( intval($status) > 0)
                $qb->where( [ 'encounter_referrals.status' => $status ] );
            
            return $qb;
        });
    }
    
}