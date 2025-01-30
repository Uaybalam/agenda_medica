<?php
/**
 * VIEWPATH is home layouts
 */
$config['layout_template_path']	= 'layouts';

/**
 * VIEWPATH + name modal layouts
 */
$config['layout_modal_file']	= 'layouts/layout.modal.php';

/**
 * Name menu files
 */
$config['menu_files']			= FCPATH . '../private/menu/';


/**
 * Assets path js default
 */
$config['asset_path_js']     = 'assets/js';

/**
 * Assets path css default
 */
$config['asset_path_css']    = 'assets/css';

/**
 * Assets prevent cache
 */
$config['asset_generate_id'] = TRUE;

/**
 * Save `table` time(seconds) and memory(megabytes) information about render
 * 
 * +------------+---------------+------+-----+---------+----------------+
 * | Field      | Type          | Null | Key | Default | Extra          |
 * +------------+---------------+------+-----+---------+----------------+
 * | id         | bigint(20)    | NO   | PRI | NULL    | auto_increment |
 * | date       | datetime      | NO   |     | NULL    |                |
 * | controller | varchar(100)  | NO   |     |         |                |
 * | action     | varchar(100)  | NO   |     |         |                |
 * | time       | decimal(10,4) | NO   |     | 0.0000  |                |
 * | params     | varchar(250)  | NO   |     |         |                |
 * | memory_mb  | decimal(10,2) | NO   |     | 0.00    |                |
 * +------------+---------------+------+-----+---------+----------------+
 *
 */
$config['render_template'] = '';

/**
 * minify HTML
 */
$config['minify_output']  = FALSE;

/**
 * $config[layouts][@name_template][js][file][path]
 * $config[layouts][@name_template][css][file][path]
 */
$config['layouts']['public'] 	= [
	'css' => [
		['file' =>'opensans', 'path' => 'assets/vendor/fonts/'],
		['file' =>'bootstrap.flatly', 'path' =>  '/assets/vendor/bootstrap/css/' ],
		['file' =>'admin', 'path' =>  '' ],
		['file' =>'switch', 'path' =>  '' ],
		['file' =>'input-radio', 'path' => ''],
		['file' =>'bootstrap-tagsinput', 'path' =>  '/assets/vendor/bootstrap/css/' ],
		['file' =>'bootstrap-datepicker', 'path' =>  '/assets/vendor/bootstrap/css/' ],
		['file' =>'dropdowns-enhancement', 'path' =>  '/assets/vendor/bootstrap/css/' ],
		//['file' =>'animate', 'path' =>  '/assets/vendor/bootstrap/css/' ],
		['file' =>'font-awesome', 'path' =>  '/assets/vendor/bootstrap/font-awesome/css/' ],
		['file' =>'style', 'path' =>  '/assets/vendor/fonts/iconmoon/' ],
		['file' =>'select2.min', 'path' => '/assets/vendor/select2-4.0.2/dist/css/' ],
		['file' =>'toastr', 'path' => '/assets/vendor/toastr/' ],
		
		['file' =>'squares', 'path' => ''],
		['file' =>'app', 'path' => ''],
	],	
	'js' => [
		['file' =>'jquery.min', 'path' =>  '/assets/vendor/jquery/' ],
		['file' =>'jquery-ui.min', 'path' => '/assets/vendor/jquery/'],
		['file' =>'jquery.maskedinput.min', 'path' =>  '/assets/vendor/jquery/' ],
		['file' =>'angular.min', 'path' =>  '/assets/vendor/angular-1.4.8/' ],
		
		['file' =>'angular-filter', 'path' =>  '/assets/vendor/angular/' ],
		['file' =>'angular-bootstrap3-typeahead', 'path' =>  '/assets/vendor/angular/' ],
		['file' =>'dirpagination.min', 'path' =>  '/assets/vendor/angular/' ],
		//
		['file' => 'select2.full.min' , 'path' => '/assets/vendor/select2-4.0.2/dist/js/'],
		//
		['file' =>'moment.min', 'path' =>  '/assets/vendor/time/' ],
		['file' =>'es', 'path' =>  '/assets/vendor/node_modules/moment/locale/' ],
		['file' =>'sweetalert', 'path' => '/assets/vendor/' ],
		['file' =>'bootstrap.min', 'path' =>  '/assets/vendor/bootstrap/js/' ],
		
		['file' =>'toastr', 'path' => '/assets/vendor/toastr/' ],
		['file' =>'bootstrap-tagsinput', 'path' =>  '/assets/vendor/bootstrap/js/' ],
		['file' =>'bootstrap3-typeahead.min', 'path' => '/assets/vendor/bootstrap/js/'],
		['file' =>'bootstrap-datepicker.min', 'path' =>  '/assets/vendor/bootstrap/js/' ],
		['file' =>'dropdowns-enhancement', 'path' =>  '/assets/vendor/bootstrap/js/' ],
		//
		['file' => '_notify' , 'path' => ''],
		['file' => '_config' , 'path' => ''],
	]
];

$config['layouts']['user'] = $config['layouts']['public'];

$config['layouts']['public']['js'][] = ['file' => 'moment-timezone-with-data.min', 'path' => '/assets/vendor/time/'];
$config['layouts']['public']['css'][] = ['file' => 'input-password', 'path' => '/assets/vendor/fonts/dotsfont-master/'];