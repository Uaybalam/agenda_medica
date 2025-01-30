<?php

function exportDataBase()
{
	$CI =& get_instance();
	$pass        = $CI->db->password;
	$name        = $CI->db->database;
	$user        = $CI->db->username;
	$tables      = false;
	$backup_name = false;
	
	set_time_limit(10000); $mysqli = new mysqli("localhost",$user,$pass,$name); 

	$mysqli->select_db($name); 
	$mysqli->query("SET NAMES 'utf8'");
	$queryTables = $mysqli->query('SHOW TABLES'); 

	while($row = $queryTables->fetch_row()) { 
		$target_tables[] = $row[0]; 
	}	
	if($tables !== false) { 
		$target_tables = array_intersect( $target_tables, $tables); 
	} 
	
	$content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `".$name."`\r\n--\r\n\r\n\r\n";
	foreach($target_tables as $table){
		if (empty($table)){ continue; } 
		$result	= $mysqli->query('SELECT * FROM `'.$table.'`');  	$fields_amount=$result->field_count;  $rows_num=$mysqli->affected_rows; 	$res = $mysqli->query('SHOW CREATE TABLE '.$table);	$TableMLine=$res->fetch_row(); 
		$content .= "\n\n".$TableMLine[1].";\n\n";   $TableMLine[1]=str_ireplace('CREATE TABLE `','CREATE TABLE IF NOT EXISTS `',$TableMLine[1]);
		for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
			while($row = $result->fetch_row())	{ //when started (and every after 100 command cycle):
				if ($st_counter%100 == 0 || $st_counter == 0 )	{$content .= "\nINSERT INTO ".$table." VALUES";}
					$content .= "\n(";    for($j=0; $j<$fields_amount; $j++){ $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); if (isset($row[$j])){$content .= '"'.$row[$j].'"' ;}  else{$content .= '""';}	   if ($j<($fields_amount-1)){$content.= ',';}   }        $content .=")";
				//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
				if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {$content .= ";";} else {$content .= ",";}	$st_counter=$st_counter+1;
			}
		} $content .="\n\n\n";
	}
	$content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
	$backup_name = $backup_name ? $backup_name : $name.'___('.date('Y-m-d').').sql';
	ob_get_clean(); header('Content-Type: application/octet-stream');  header("Content-Transfer-Encoding: Binary");  header('Content-Length: '. (function_exists('mb_strlen') ? mb_strlen($content, '8bit'): strlen($content)) );    header("Content-disposition: attachment; filename=\"".$backup_name."\""); 
	echo $content; exit;
	/*
	$command= "mysqldump -h localhost -u ".$username." -p".$password." ".$database." --single-transaction --quick --lock-tables=false 2>&1";
	
	$exec = exec($command, $output, $res );
	if(!empty($res))
	{	
		pr($res);
		pr($exec);
		pr($output);
		exit;
	}

	header('Content-Description: File Transfer');
	header("Content-type: text/plain");
	header("Content-Disposition: attachment; filename=database.sql");
	header('Cache-Control: must-revalidate');
	header('Expires: 0');
	echo implode("\n",$output);
	flush(); 
 	*/
}

function redirect_ssl() {

	$port = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80;
	
	if($port == 443)
		return true;

    $CI =& get_instance();
   	$site = explode(":",$CI->config->config['base_url']);
   	if($site[0]==='https' && $_SERVER['SERVER_PORT'] != 443)
   	{
   		redirect($CI->uri->uri_string());
   	}
}

function validate_access_type( $access_type )
{
	$CI = &get_instance();
	
	$arr_access_type = (is_array($access_type)  ) ? $access_type : [$access_type];
	
	if  ( 	$CI->current_user->access_type === 'admin' || 
			in_array($CI->current_user->access_type, $arr_access_type ) 
		)
	{
		return true;
	}

	return false;
}

function clear_var( $var )
{	
	return filter_var(trim($var), FILTER_UNSAFE_RAW);
}

function global_settings( $config_title )
{
	$CI = &get_instance();

	$CI->db->from('settings_global')
		->where(['title' => $config_title ]);
		
	$settings = $CI->db->get()->row();
	
	if( $settings )
	{
		return $settings->value;
	}
	else
	{
		return '';
	}
}

function active_plan( $id , $name , $return = 1 )
{

	$plan = [
		0 => 'PlanMedicare',
		1 => 'PlanMedicaid',
		2 => 'PlanChampus',
		3 => 'PlanChampVA',
		4 => 'PlanGroupHealthPlan',
		5 => 'PlanFECA',
		6 => 'PlanOther',
	];

	if( $plan[$id] === $name )
	{
		return $return;
	}
	else
	{	
		return '';
	}	
}

function active_patientrelation( $id , $name, $return = 1)
{
	$relationship = [
		0 => 'PatientRelationSELF',
		1 => 'PatientRelationSPOUSE',
		2 => 'PatientRelationCHILD',
		3 => 'PatientRelationOTHER'
	];
	
	if( $relationship[$id] === $name )
	{
		return $return;
	}
	else
	{	
		return '';
	}	
}

function human_age( $date )
{

	$from     = new DateTime($date);
	$to       = new DateTime('today');
	$age_year = $from->diff($to)->y;
	if( $age_year > 1 )
	{
		return "{$age_year} Años";
	}
	else if($age_year === 1 )
	{
		return "{$age_year} Año";
	}
	else
	{
		$age_months = $from->diff($to)->m;
		if($age_months>1)
		{
			return "{$age_months} Meses";
		}
		else if($age_months === 1)
		{
			return "{$age_months} Mes";
		}
		else
		{
			$age_days =  $from->diff($to)->d;
			if($age_days>1)
			{
				return "{$age_days} Días";
			}
			else if($age_days === 1)
			{
				return "{$age_days} Día";
			}
			else
			{	
				return "Menos de 12 horas después del nacimiento";
			}
		}
	}
}
