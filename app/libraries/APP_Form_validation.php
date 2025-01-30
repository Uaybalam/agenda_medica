<?php 
class APP_Form_validation extends CI_Form_validation{
    
    function __construct( $rules = array() )
	{
	    parent::__construct( $rules );

        $this->set_message('is_unique','El {field} ingresado ya existe');
	}

    public function same_value( $str, $field )
    {
        sscanf($field, '%[^.].%[^.]', $oldValue, $field);
        
        if($oldValue && $str === $oldValue )
        {
            $this->set_message('same_value', 'El campo {field} tiene el mismo valor');
            return FALSE;
        }

        return TRUE;
       
    }
	
	public function exist_data( $str, $field )
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
            
        if( ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 0) )
        {   
            $this->set_message('exist_data',  "{field} no encontrado");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    public function exist_date( $str ) 
    {
    	$date = @date_parse( $str ); 
        
        if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"]))
            return TRUE;
        else
        {   
            $this->set_message('exist_date', 'El campo {field} no es una fecha válida');
            return FALSE;
        }
    }

    public function date_max_today( $str)
    {
        $date = @date_parse( $str ); 
        
        if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"]))
        {
            $today         = (int)date('Ymd');
            $date_selected = (int)date('Ymd',strtotime($str));
            
            if($date_selected > $today )
            {   
                $this->set_message('date_max_today', 'El campo {field} no puede ser mayor que la fecha actual');
                return FALSE;
            }
        }
        
        return TRUE;
    }

    public function date_min_today( $str)
    {
        $date = @date_parse( $str ); 
        
        if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"]))
        {
            $today         = (int)date('Ymd');
            $date_selected = (int)date('Ymd',strtotime($str));
            
            if($date_selected < $today )
            {   
                $this->set_message('date_min_today', 'El campo {field} no puede ser menor que la fecha actual');
                return FALSE;
            }
        }
        
        return TRUE;
    }

    protected function in_data( $str, $field  )
    {
    	if (!in_array($str, explode(',', $field) ))
        {   
            $this->set_message('in_data', 'El campo {field} no puede ser válido');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function exist_data_permited( $str , $field )
    {
        if( $str == 0)
        {
            return TRUE;
        }

        sscanf($field, '%[^.].%[^.]', $table, $field);
            
        if( ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 0) )
        {   
            $this->set_message('exist_data_permit',  "Datos no válidos {field}");
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    function pin_verify( $str )
    {
        
        if( $str && $str === $this->CI->current_user->pin ) 
        {   
            return TRUE;
        }
        else
        {
            $this->set_message('pin_verify','El PIN no coincide con el usuario');
            return FALSE;
            
        }
    }

    function exist_setting_name( $str , $type)
    {
        $where = [];
        $id    = null;

        if (strpos($type, "|") !== false) 
        {
            $id   = explode("|",$type)[1];
            $type = explode("|",$type)[0]; 
        }

        if($type)
        {
            $where['type'] = $type;
        }

        if($id)
        {
            $where["id !="] = $id;
        }

        $where['name'] = $str;
         $where["instance_id"] = $_SESSION['User_DB']->instance_id;

        if( ($this->CI->db->limit(1)->get_where('custom_setting', $where )->num_rows() > 0 ) )
        {    
            $this->set_message('exist_setting_name',  "El campo nombre esta repetido");
            return FALSE;
        }
        else
        {    
            return TRUE;
        }
    }
}