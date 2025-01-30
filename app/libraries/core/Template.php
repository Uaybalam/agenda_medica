<?php
/**
 * Template
 */
#[\AllowDynamicProperties]
class Template {
    
    /**
     *  @var $CI: instance at framework
     */
    protected $CI;
    
    /**
     *  @var $include_js: JS include on fotter
     */
    protected $include_js             = array();
    
    /**
     *  @var $include_js: CSS include on header
     */
    protected $include_css         = array();
    
    /**
     *  @var $include_modal: Modal add to response
     */
    protected $include_modal       = array();
    
    /**
     * @var array
     */
    protected $include_css_external = array();

    /**
     * @var array
     */
    protected $include_js_external = array();

    /**
     *  @var string
     */
    protected $layout_selected;
    
    /**
     *  @var string
     */
    protected $layout_template_path;

    /**
     * @var boolean
     */
    protected $load_assets_success;
    
    /**
     * @var string
     */
    protected $layout_title;

    /**
     * @var string
     */
    protected $menu_files;

    /**
     * @var array
     */
    protected $body_attr = array();

    /**
     *  @var $path_js: folder js
     */
    protected $path_js;
    
    /**
     *  @var $path_css: folder css
     */
    protected $path_css;
    
    /**
     *  @var $new_id: id generater no cache
     */
    protected $new_id              = '';

	function __construct( $config = array() )
    {
            
		$this->CI =& get_instance();
        
        $this->layouts = $config['layouts'];

        $this->layout_template_path = $config['layout_template_path'];
        
        $this->layout_modal_file    = $config['layout_modal_file'];
        
        $this->menu_files           = $config['menu_files'];
        
        $this->path_js              = $config['asset_path_js'];
        $this->path_css             = $config['asset_path_css'];
        $this->render_template      = $config['render_template'];
        $this->minify_output        = $config['minify_output'];

        if( $config['asset_generate_id'] === TRUE)
        {   
            $this->new_id   =  '?id='.uniqid();
        }

        $this->body( ['cz-shortcut-listen' => 'true' ] );
    }


    /**
     *->modal
     *  
     *  Define view type modal in view/themes/_modal-header.php
     *
     * @param view   string     name file view
     * @param config array      (size,id,title, open_on_load)
     * @param config array      config init modal 
     */
    function modal( $view_name, $config = null, $params = null ) 
    {
        $view = ( strpos( $view_name , "." ) === false ) ?  $view_name : $view_name.'.php'; 
        $config['view']         = $view;
        $config['size']         = (isset($config['size']))            ? $config['size'] : 'modal-lg';
        $config['id']           = (isset($config['id']))              ? $config['id'] : str_replace(["/","."], "-", $view_name);
        $config['title']        = (isset($config['title']) )      ? $config['title'] : '!title is required';
        $config['open_on_load'] = (isset($config['open_on_load']) )   ? (int)$config['open_on_load'] : 0;
        
        $view = ( strpos( $view , "." ) === false ) ?  $view : $view.'.php'; 
        
        $this->include_modal[]  = [
            'config' => $config,
            'params' => is_null($params) ? [] : $params
        ];
        return $this;
    }

    /**
     *->layout
     *  
     *  Define view header and footer theme in folder view/themes/
     *
     * @param name   string     name file view
     */
    function layout( $layout_name , $layout_title = '' )
    {
        $this->layout_selected = $layout_name;
        $this->layout_title    = ($layout_title != '') ? $layout_title : $layout_name;

    	return $this;
    }
    
    /**
     *->body
     *  
     *  Define content extra on body
     *
     * @param name   array     name file view
     */
    function body( $attr )  
    { 
        $this->body_attr = array_merge($this->body_attr, $attr);
        
        return $this;
    } 
    
    /**
     *->set_title 
     *
     * Set title page
     *
     * @param layout_title string title page
     *
     */
    function set_title( $layout_title )
    {
        $this->layout_title = $layout_title;

        return $this;
    }

    /**
     *->render [render website]
     *  
     *  Define view default
     *
     * @param view   string     name file view
     * @param parms  array      vars default view
     * @param return boolean    return html or print
     */
    function render($view, $parms = array(), $return = FALSE )
    {
        //get css and js defaults per layout
        $this->_load_assets_layout( $this->layout_selected );

        //get content css
        $css = '';
        foreach ($this->include_css as $value) {
            $css.= $this->_print_css( $value['file'],  $value['path'], FALSE );   
        }
        
        //get content js
        $js = '';
        //PR()
        foreach ($this->include_js as $value) { 
            $js.= $this->_print_js( $value['file'],  $value['path'], false );   
        }

        //get content modals
        $modals = '';

        foreach ($this->include_modal as $modal) { 
            
            $params_modal = [
                'config' => $modal['config'],
                'params' => $modal['params']
            ];

            $modals.= $this->CI->load->view(
                $this->layout_modal_file, 
                $params_modal, 
                TRUE
            );
        }


        $view = ( strpos( $view , "." ) === false ) ?  $view : $view.'.php'; 
        
        $body_attr = implode(' ', array_map(
            function ($v, $k) { return sprintf('%s="%s"', $k, $v); },
            $this->body_attr,
            array_keys($this->body_attr)
        )); 
            
        $params = [
            'config' => [
                'css'   => $css,
                'js'    => $js,
                'modals'=> $modals,
                'title' => $this->layout_title,
                'view'  => $view,
                'body'  => $body_attr
            ],
            '_'     => $parms
        ];

        $layout   = $this->layout_template_path.'/layout.'.$this->layout_selected.'.php';
            
        if($return) 
        {   
            $params['config']['return_view'] = TRUE;
            $content = $this->CI->load->view(
                $layout, 
                $params, 
                TRUE
            );
            
            return $content;
        }   
        else
        {   
            $params['config']['return_view'] = FALSE;
            
            //$this->CI->output->cache(minutes = 72);
            
            $this->CI->load->view(
                $layout, 
                $params, 
                FALSE
            );
            
                      
            if( $this->render_template )
            {       
                /**
                 * FORCE ENABLE HOOK
                 */
                $this->CI->hooks->enabled = TRUE;
                $this->CI->hooks->hooks['post_system'] = function()
                {
                    $segments = $this->CI->uri->rsegment_array();
                    array_splice($segments, 0 , 2);
                    $segments = array_filter($segments);
                    $this->CI->db->insert( $this->render_template,  [
                        'date' => date('Y-m-d H:i:s'),
                        'controller' => $this->CI->router->class,
                        'action' => $this->CI->router->method,
                        'time' => $this->CI->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end'),
                        'memory_mb' => round(memory_get_usage() / 1024 / 1024, 2),
                        'params' => implode(',', $segments )
                    ]);
                };  
            }
        }

        if( $this->minify_output )
        {
            /**
             * FORCE ENABLE HOOK
             */
            $this->CI->hooks->enabled = TRUE;
            $this->CI->hooks->hooks['display_override'] = function()
            {
                $buffer = $this->CI->output->get_output();

                $re = '%# Collapse whitespace everywhere but in blacklisted elements.
                    (?>             # Match all whitespans other than single space.
                      [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
                    | \s{2,}        # or two or more consecutive-any-whitespace.
                    ) # Note: The remaining regex consumes no text at all...
                    (?=             # Ensure we are not in a blacklist tag.
                      [^<]*+        # Either zero or more non-"<" {normal*}
                      (?:           # Begin {(special normal*)*} construct
                        <           # or a < starting a non-blacklist tag.
                        (?!/?(?:textarea|pre|script)\b)
                        [^<]*+      # more non-"<" {normal*}
                      )*+           # Finish "unrolling-the-loop"
                      (?:           # Begin alternation group.
                        <           # Either a blacklist start tag.
                        (?>textarea|pre|script)\b
                      | \z          # or end of file.
                      )             # End alternation group.
                    )  # If we made it here, we are not in a blacklist tag.
                    %Six';
                    
                $new_buffer = preg_replace($re, " ", $buffer);
                // We are going to check if processing has working
                if ($new_buffer === null)
                {
                    $new_buffer = $buffer;
                }

                $this->CI->output->set_output($new_buffer);
                $this->CI->output->_display();
            };
        }

    }

    /**
     *->render [render website]
     *  
     *  Define view default
     *
     * @param view   string     name file view
     * @param parms  array      vars default view
     * @param return boolean    return html or print
     */
    function render_view($view, $parms = array(), $return = FALSE )
    {
        $params = [
            '_'     => $parms
        ];
        
        $simple_view = ( strpos( $view , "." ) === false ) ?  $view : $view.'.php'; 

        if($return) 
        {   
            $content = $this->CI->load->view(
                $simple_view, 
                $params, 
                TRUE
            );
            
            return $content;
        }   
        else
        {   
            $this->CI->load->view(
                $simple_view, 
                $params, 
                FALSE
            );
        }
    }
    
    /**
     *->json
     *  
     *  Send json output config @ReturnJson in comments
     *  
     * @param data   array|object      send json output
     */ 
    function json( $data , $option_json =  'JSON_NUMERIC_CHECK' )
    {
        
        if(!defined('JSON_PRESERVE_ZERO_FRACTION'))
        {
            define('JSON_PRESERVE_ZERO_FRACTION', 1024);
        }
        
        $options_availible = [
            'JSON_HEX_QUOT',
            'JSON_HEX_TAG',
            'JSON_HEX_AMP',
            'JSON_HEX_APOS',
            'JSON_NUMERIC_CHECK',
            'JSON_PRETTY_PRINT',
            'JSON_UNESCAPED_SLASHES',
            'JSON_FORCE_OBJECT',
            'JSON_PRESERVE_ZERO_FRACTION', 
            'JSON_UNESCAPED_UNICODE', 
            'JSON_PARTIAL_OUTPUT_ON_ERROR'
        ];
        
        $this->CI->output
            ->set_status_header(200)
            ->set_content_type('json', 'UTF-8');
        

        if(!in_array($option_json, $options_availible))
        {   
            $option_json = 0;
        }
        else if(defined($option_json))
        {
            $option_json =  constant($option_json);
        }
        else if($option_json)
        {
            http_response_code(404);
            echo @json_encode( ['status' => 0, 'msg' => "Option JSON not works {$option_json}" ] ); 
            exit;
        }

        if( !isset($data['status']) )
        {   
            $data['status'] = 0;
        }

        echo @json_encode( $data , $option_json );
        
        exit;
    }

    /**
     *->json_entitie
     *  
     *  Send json_entitie output config @ReturnJson in comments
     *  
     * @param data   array|object      send json output type html
     */ 
    function json_entities( $data = null )
    {           
        //stripslashes
        return str_replace( '\n',"\\"."\\n",
            htmlentities(
                utf8_encode( json_encode( $data )  ) , 
                ENT_QUOTES | ENT_IGNORE, 'UTF-8' 
            )
        );
    }


    /**
     *->js
     *  
     *  Set JS end document
     *  
     * @param file   string      name file config in /resources/js/
     * @param path   string      path file changed for external if file=''
     */
    function js( $file, $path = '')
    {
        $this->include_js_external[] = [ 'file' => $file, 'path' => $path  ];
        return $this;
    }
    
    /**
     *->js
     *  
     *  Set CSS init document
     *  
     * @param file   string      name file config in /resources/css/
     * @param path   string      path file changed for external if file=''
     */
    function css( $file, $path = '')
    {
        $this->include_css_external[] = [ 'file' => $file, 'path' => $path  ];
        return $this;
    }   

    /**
     *->current_user
     *  
     *  get current user and redirect if have url
     *  status 0 = Inactivo
     *  status 1 = Activo
     *  status 2 = Pendiente activar
     *
     * @param redirect   string      full url redirect if not logged
     */
    function current_user( $type_user ='' , $redirect = '')
    {
        $exit = false;
        
        if(!isset($this->CI->session->userdata[$type_user]->id ) ){
            $exit = true; 
        }else if($user = $this->CI->{$type_user}->getBy('id', $this->CI->session->userdata[$type_user]->id )){
            if( (int) $user->status === 1){
                return $this->CI->session->userdata[$type_user] = $user;    
            }else{
                $exit = true;
            } 
        }else{
            $exit = true;
        }   
        
        if($exit && $redirect!='' ){
            redirect($redirect);
        }else{ 
            return false;
        }
    } 
    
    /**
     *->base64_img
     */
    function base64_img( $file )
    {
        $type     = pathinfo(  $file, PATHINFO_EXTENSION);
        $contents = file_get_contents($file);   
        return 'data:image/' . $type . ';base64,' . base64_encode($contents);
    } 
    
    /**
     *->renderImage
     */
    function render_file( $file , $previewBrowserTypes = ['pdf','jpg','png','jpeg'] )
    {

        $fileInfo = pathinfo( $file );
        
        $finfo    = finfo_open(FILEINFO_MIME_TYPE); // devuelve el tipo mime de su extensión
        $mime     = finfo_file($finfo, $file);
        finfo_close($finfo);

        header('Expires: 0');
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");

        if(in_array($fileInfo['extension'], $previewBrowserTypes))
        {
           
            header('Content-Disposition: inline; filename="'.basename($file).'"');
            header( "Content-type: ".$mime);
            readfile( $file ); 
        }
        else
        {   
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Content-Length: ' . filesize($file));
        }
        
        exit;
    }

    /**
     * ->renderPreview
     */
    function render_preview( $file )
    {   


        $fileInfo = pathinfo( $file );
        
        $finfo    = finfo_open(FILEINFO_MIME_TYPE); // devuelve el tipo mime de su extensión
        $mime     = finfo_file($finfo, $file);
        finfo_close($finfo);

        header('Expires: 0');
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");

        if( $fileInfo['extension'] === 'pdf' )
        {   
            /*$im = new imagick($file .'[0]');
            $im->setImageFormat('jpeg');
            
            if(method_exists($im, 'setImageAlphaChannel'))
            {
                //fixed background black on reports
                $im->setImageAlphaChannel(11);
            }

            header('Content-Type: image/jpeg');
            echo $im;*/
           

            $path = $fileInfo['dirname']."/".$fileInfo['basename'];

            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename='.$path);
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            echo readfile($path);
        }
        else{
            header( "Content-type: ".$mime);
            echo readfile( $file ); 
        }

        exit;
    }
    
    /**
     *->download CSV
     */
    function download_csv( $file_name, $data, $columns = array() , $separate = ',' )
    {   
        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename='.$file_name);

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        if( count($columns) > 0 )
        {   
            fputcsv( $output, $columns, $separate," " );
        }
        
        foreach ($data as $row) {
            fputcsv($output, $row , $separate," " );
        }

        fclose($output);
    }

    private function _load_assets_layout( $layout_name )
    {

        $include_js =  $include_css = [];
        if(isset($this->layouts[$layout_name]))
        {
            if(isset($this->layouts[$layout_name]['css']) )
            {   
                foreach ($this->layouts[$layout_name]['css'] as $value) 
                {
                    $include_css[] = $value;
                }

            }
            
            if(isset($this->layouts[$layout_name]['js']) )
            {   
                foreach ($this->layouts[$layout_name]['js'] as $value) 
                {
                    $include_js[] = $value;
                }
                
            }
        }
        //PR($include_css); exit;
        $this->include_css = array_merge($include_css, $this->include_css_external);
        $this->include_js  = array_merge($include_js, $this->include_js_external);
    }
    
    private function _print_js($file , $path = '', $print = TRUE )
    {
        $path     = ($path!='') ? $path : $this->path_js.'/';
        if($file==''){      
            $file_url = $path;
        }else{  
            $file_url = base_url($path.$file.'.js'). $this->new_id;
        }   
        if($print){
            echo '<script type="text/javascript"  src="'.$file_url.'"></script>';
        }else{
            return '<script type="text/javascript"  src="'.$file_url.'"></script>';
        }   
    }
    
    private function _print_css($file, $path = '', $print = TRUE)
    {
        $path = ($path!='') ? $path : $this->path_css.'/';
        if($file==''){
            $file_url = $path . $this->new_id;
        }else{
            $file_url = base_url($path.$file.'.css') . $this->new_id; 
        }

        if($print){
            echo '<link rel="stylesheet" href="'.$file_url.'" type="text/css" />';
        }else{
            return '<link rel="stylesheet" href="'.$file_url.'" type="text/css" />';
        }
    }
    
    /**
     *->load_menu
     *  
     *  File menu to load resources/_default/menu.yml
     *  
     */
    function load_menu( $menu_name = 'staff' )
    {
        $this->CI->load->library('core/Spyc');
        $file = "{$this->menu_files}menu-{$menu_name}.yml"; 
        //$file           = FCPATH . 'private/menu/menu-'.$menu_name.'.yml';
        $options_menu   = $this->CI->spyc->YAMLLoad( $file );
        return $options_menu; 
    }
    
    /**
     *->load_menu
     *  
     *  get menu from load_menu();
     *
     * @param user   data object user
     */   
    function get_menu( $menu = 'staff', $user = null )
    {   
        
        $user_access = isset($user->access_type) ? $user->access_type : '';
        
        $menu = $this->load_menu( $menu );
        if(!isset($menu['nodes']) || !is_array($menu['nodes']))
        {   
            show_error('Primary node not found', 500 );
        }   

        $current_route  = $this->CI->router->class.'/'.$this->CI->router->method;
        
        $html_nav   = '<ul class="nav navbar-nav side-nav themenav">
                        <li class="" style="border-bottom:1px solid #36619C;">
                            <a class=" href="/" style="border:padding:2px;margin: auto;text-align: center;">
                                <img src="/logo.png" width="51%" style="width: 100px;margin: auto;padding-bottom: 10px;">
                            </a>
                        </li>';
        $fn_nodes_html = function($nodes, $parent = null ) use( &$fn_nodes_html, $current_route, $user_access){
            $html_node = '';
            foreach ($nodes as $key => $info) {

                $info['name']           = isset($info['name']) ? $info['name'] : 'My Node';
                $info['url']            = isset($info['url']) ? $info['url'] : '#';
                $info['icon']           = isset($info['icon']) ? $info['icon'] : '';
                $info['route']          = isset($info['route']) ? $info['route'] : '';
                $info['access_types']   = isset($info['access_types']) ? $info['access_types'] : '';
                $info['preseparator']   = isset($info['preseparator']) ? $info['preseparator'] : '';
                $info['badge_function'] = isset($info['badge_function']) ? $info['badge_function'] : '';

                $selected  = ( $current_route === $info['route'] || explode("/",$current_route)[0] == explode("/",$info['route'])[0] ) ? 'active': ''; 
                
                if( $info['access_types'] != '' && $user_access != 'admin' && $user_access != 'root' )
                {   
                    $access_types = explode(",", $info['access_types']);

                    if(!in_array($user_access, $access_types))
                    {
                        continue;
                    }
                }

                $style_separator = ( $info['preseparator'] ) ? 'style="border-bottom:1px solid #36619C;"' : '';
                
                $html_node .= '<li class="'.$selected.'" '.$style_separator.' current_route="'.$current_route.'" route="'.$info['route'].'"">';

                if(isset( $info['nodes'] ) )
                {   
                    $uniqid_target = 'target_'.uniqid();
                    $html_node.= '<a href="javascript:;" data-toggle="collapse" data-target="#'.$uniqid_target.'"><i class="'.$info['icon'].'"></i> '.$info['name'].' <i class="fa fa-fw fa-caret-down"></i></a>';
                    $html_node.= '<ul id="'.$uniqid_target.'" class="collapse">';
                    $html_node.= $fn_nodes_html( $info['nodes'], $info['name'] ); 
                    $html_node.= '</ul>';
                }       
                else
                {   
                    /*$badge = '';
                    if($info['badge_function']!='')
                    {   
                        $badge = ' <span class="badge" id="pending-'.$info['badge_function'].'" >'.$this->CI->Menu_DB->{$info['badge_function']}().'</span>';
                    }*/
                    
                    $html_node.= '<a href="'.$info['url'].'">'.
                        '<i class="full-icon '.$info['icon'].'"></i> '.
                        '<span class="menu-title">'.$info['name'].'</span>'.
                        '</a>';
                }
                $html_node.= '</li>';
               
            }
                
            return $html_node;
        };

        $html_nav.= $fn_nodes_html( $menu['nodes'] );
        $html_nav.= '</ul>';
        //exit;
        return $html_nav;
    }
}
