<?php

class APP_Input extends CI_Input
{
	function __construct()
	{
	    parent::__construct();
	}

	/**
	 * Fetch an item from the POST array
	 *
	 * @param	mixed	$index		Index for item to be fetched from $_POST
	 * @param	bool	$xss_clean	Whether to apply XSS filtering
	 * @return	mixed
	 */
	public function post($index = NULL, $xss_clean = NULL)
	{
		$value = $this->_fetch_from_array($_POST, $index, $xss_clean);
		if(is_null($value))
		{
			return '';
		}
		
		return $value;
	}
}