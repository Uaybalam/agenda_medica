<?php

/*
|--------------------------------------------------------------------------
| Template Schema use CSS
|--------------------------------------------------------------------------
|
| CSS templates
| can use any of these from = https://bootswatch.com
| https://bootswatch.com/slate/bootstrap.min.css
| https://bootswatch.com/simplex/bootstrap.min.css
| https://bootswatch.com/spacelab/bootstrap.min.css
| https://bootswatch.com/yeti/bootstrap.min.css
*/	
$config['schema_template'] = site_url('assets/css/cerulean.bootstrap.min.css');
	

/*
|--------------------------------------------------------------------------
| Schema table
|--------------------------------------------------------------------------
|
| this table save version, name file, date and user. Use for run pending 
| schema yml
|
*/
$config['schema_table'] = 'ci_schema';	

/*
|--------------------------------------------------------------------------
| Schema session var
|--------------------------------------------------------------------------
|
| name session to login
|	
*/
$config['schema_session_var'] = 'session_schema';

/*
|--------------------------------------------------------------------------
| Schema users controller
|--------------------------------------------------------------------------
|
| you have acces to use enviroment visual to execute migration
|	
*/	
$config['schema_session_users'] = Array(
	'admin' => 'secret'
);

/*
|--------------------------------------------------------------------------
| schemas Path
|--------------------------------------------------------------------------
|
| Path to your migrations folder.
| Typically, it will be within your application path.
| Also, writing permission is required within the migrations path.
|
*/
$config['schema_path'] = BASEPATH.'../private/schema/';



/*
|--------------------------------------------------------------------------
| schemas Path Spyc library
|--------------------------------------------------------------------------
|
| Path library yml
| Necessary to create an array from yml
| Can save on /libraries/Spyc.php for standar CI
|
*/
$config['schema_path_spyc'] = APPPATH.'/libraries/core/';
