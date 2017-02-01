<?php
/**
 * WordPress Markup Language parser (WPL Parser)
 * 
 * @author Sven Wagener <svenw_at_themekraft_dot_com>
 * @copyright themekraft.com
 * 
 */
class TK_WML_Parser{

	var $display;
	var $functions;
	var $bound_content;
	var $errstr;
	var $text_strings;
	var $create_textfiles;
	
	/**
	 * PHP 4 constructor
	 *
	 * @package Themekraft Framework
	 * @since 0.1.0
	 * 
	 */
	function tk_wpml_parser( $return_object = TRUE ){
		$this->__construct( $return_object );
	}
	
	/**
	 * PHP 5 constructor
	 *
	 * @package Themekraft Framework
	 * @since 0.1.0
	 * 
	 */
	function __construct( $return_object = TRUE ){
		$this->display= array();
		
		// Menu & Pages
		$functions['menu'] = array( 'id' => '', 'title' => '', 'page' => array(), 'slug' => '', 'capability' => 'edit_posts', 'parent_slug' => '',  'icon' => '', 'position' => '', 'return_object' => $return_object );
		$functions['page'] = array(  'id' => '', 'title' => '', 'content' => '', 'headline' => '', 'slug' => '', 'icon' => '' );
		$bound_content['menu'] = 'page';

		// Posts
		$functions['metabox'] = array(  'id' => '', 'title' => '', 'content' => '' , 'post_type' => '', 'return_object' => $return_object );
		
		// Tabs
		$functions['tabs'] = array( 'id' =>'', 'tab' => array(), 'return_object' => $return_object );
		$functions['tab'] = array( 'id' =>'', 'title' => '', 'content' => '' );
		$bound_content['tabs'] = 'tab';
		
		// Accordion
		$functions['accordion'] = array( 'id' => '', 'section' => array(), 'return_object' => $return_object );
		$functions['section'] = array( 'id' => '', 'title' => '', 'content' => '', 'class' => '' );
		$bound_content['accordion'] = 'section';
		
		// Autocomplete
		$functions['autocomplete'] = array( 'name' => '', 'class' => '' , 'value' => array(), 'label' => '', 'return_object' => $return_object );
		$functions['value'] = array( 'content' => '' );
		$bound_content['autocomplete'] = 'value';
		
		// Form
		$functions['form'] = array( 'id' => '', 'name' => '', 'content' => '', 'return_object' => $return_object );
		
		// Form elements
		$functions['textfield'] = array( 'name' => '', 'class' => '', 'label' => '', 'tooltip' => '', 'description' => '', 'link' => '', 'return_object' => $return_object );
		$functions['textarea'] = array( 'name' => '', 'class' => '', 'label' => '', 'tooltip' => '', 'description' => '', 'link' => '', 'return_object' => $return_object );
		$functions['colorpicker'] = array( 'name' => '', 'class' => '', 'label' => '', 'tooltip' => '', 'description' => '', 'link' => '', 'return_object' => $return_object );
		$functions['file'] = array( 'name' => '', 'class' => '', 'label' => '', 'tooltip' => '', 'description' => '', 'link' => '', 'uploader' => 'wp', 'delete' => FALSE, 'return_object' => $return_object );
				
		$functions['checkbox'] = array( 'name' => '', 'class' => '', 'description' => '', 'label' => '', 'tooltip' => '', 'description' => '', 'link' => '', 'return_object' => $return_object );
		$functions['radio'] = array( 'name' => '', 'class' => '', 'value' => '', 'description' => '', 'label' => '', 'tooltip' => '', 'description' => '', 'link' => '', 'return_object' => $return_object );
		
		$functions['select'] = array( 'name' => '', 'option' => array(), 'multiselect' => FALSE, 'size' => '', 'label' => '', 'tooltip' => '', 'description' => '', 'link' => '', 'class' => '', 'onchange' => '', 'return_object' => $return_object );
		$functions['option'] = array( 'id' => '', 'value' => '', 'name' => '', 'hide_class' => '' );
		$bound_content['select'] = 'option';		
		
		$functions['button'] = array( 'name' => '', 'return_object' => $return_object );

		$functions['import'] = array( 'name' => '', 'class' => '', 'label' => '', 'tooltip' => '', 'description' => '', 'link' => '' );
		$functions['export'] = array( 'name' => '', 'class' => '', 'forms' => '', 'label' => '', 'file_name' => '', 'tooltip' => '', 'description' => '', 'link' => '', 'return_object' => $return_object );
		
		// tk_db_export( $name, $forms, $label, $file_name,  $tooltip, $return_object = TRUE )
		
		$this->bound_content = $bound_content;
		
		// Putting all functions in an array
		$this->function_names = array_keys( $functions );
		$this->functions = $functions;
	}
	
	function load_wml( $xml_string, $return_object = FALSE ){
		
		// Checking if DOMDocument is installed
		if ( ! class_exists('DOMDocument') )
			return FALSE;
		
		// Loading XML
		$doc = new DOMDocument();
				
		set_error_handler( array( $this, 'wml_error' ) );
		if( !$doc->loadXML( $xml_string ) ){
			return FALSE;
		}
		restore_error_handler();
		
		return $this->load_dom( $doc );
	}
	
	function load_wml_file( $source ){
		
		$doc = new DOMDocument();
		if ( !file_exists( $source ) ){
			$this->errstr = '<strong>' . __( 'WML Document error: ', 'tkf' ) . '</strong>' .  __( 'File not found! Be sure, the full document path is given.', 'tkf' );
			add_action( 'all_admin_notices', array( $this, 'error_box' ), 1 );
			return FALSE;
		}
		set_error_handler( array( $this, 'wml_error' ) );
		if( !$doc->load( $source ) ){
			return FALSE;
		}
		restore_error_handler();
				
		return $this->load_dom( $doc );
	}
	
	function load_dom( $dom ){
		// Getting main node
		$node = $dom->getElementsByTagName( 'wml' );
		$mainnode = $node->item(0);
		
		// Getting object
		$this->display= $this->tk_obj_from_node( $mainnode );
				
		return TRUE;		
	}
	
	function wml_error($errno, $errstr, $errfile, $errline){		
	    if ( $errno == E_WARNING && ( substr_count( $errstr,"DOMDocument::loadXML()" ) > 0 ) ){
	       	$this->errstr = '<strong>' . __( 'WML Document error: ', 'tkf' ) . '</strong>' . substr( $errstr, 79, 1000 );
			add_action( 'all_admin_notices', array( $this, 'error_box' ), 1 );
	    }elseif ( $errno == E_WARNING && ( substr_count( $errstr,"DOMDocument::load()" ) > 0 ) ){
	    	$this->errstr = '<strong>' . __( 'WML Document error: ', 'tkf' ) . '</strong>' . substr( $errstr, 70, 1000 );
			add_action( 'all_admin_notices', array( $this, 'error_box' ), 1 );	    	
	    }
	    else
	        return false;
	}
	
	function error_box(){
		echo '<div id="message" class="error"><p>' . $this->errstr . '</p></div>';
	}


	function tk_obj_from_node( $node, $function_name = FALSE, $is_html = FALSE, $parent_name = ''){
		global $tkf_create_textfiles, $tkf_text_domain;
		
		// Getting node values
		$node_name = $node->nodeName;
   		$node_value = $node->nodeValue;
		$node_attr = $node->attributes;
		$node_list = $node->childNodes;
		
		/*
		 * Running node attributes 
		 */
		foreach( $node_attr as $attribute ){
			$params[$attribute->nodeName] = $attribute->nodeValue;
		}
		
		// Functions have to be executed before executing inner functions			
		if( FALSE != $function_name ){
			// Setting global form instance name
			if( $function_name == 'form' ){
				global $tk_form_instance_option_group;					
				$tk_form_instance_option_group = $params['name'];									
			}
			if( $function_name == 'metabox' ){
				global $tkf_metabox_id;					
				$tkf_metabox_id = $params['id'];									
			}
			if( $function_name == 'option' ){
				global $tk_form_instance_option_group, $tkf_hide_class_options, $tkf_show_class, $tkf_hide_class;
				
				$values = tk_get_values( $tk_form_instance_option_group );
				
				// If value from select is option value
				if( $values->$parent_name == $params['value'] ){
					if($params['show_class'] != '')			
						$tkf_show_class[] = $params['show_class'];
					if($params['hide_class'] != '')	
						$tkf_hide_class[] = $params['hide_class'];
				}
				
				$tkf_hide_class_options[$parent_name]['value'] = $values->$parent_name;
				$tkf_hide_class_options[$parent_name]['option'][] = array(
					'name' => $params['name'],
					'value' => $params['value'],
					'hide_class' => $params['hide_class'],
					'show_class' => $params['show_class'],
					
				);
			}
			
			
			$name = $params['name'];	
		}		
		
		/*
		 * Running sub nodes 
		 */
		for( $i=0;  $i < $node_list->length; $i++ ){
			$subnode = $node_list->item( $i );
			$subnode_name = $subnode->nodeName;
			$subnode_value = $subnode->nodeValue;
			$subnode_attributes = $subnode->attributes;
			
			// WML Tag
			if( in_array( $subnode_name, $this->function_names ) ){												
				$params['content'][$i] = $this->tk_obj_from_node( $subnode, $subnode_name, FALSE, $name );
			
			// HTML Tag
			}elseif( $subnode->nodeType != XML_TEXT_NODE ){
				
				// Getting Tag attributes
				$attributes = '';
				foreach ( $subnode_attributes as $attr_name => $attrNode )
            		$attributes.= ' ' . $attr_name . '="' . $attrNode->value . '"'; 
				
				// Set up Tag
				$params['content'][$i] = array ( '<' . $subnode->nodeName . $attributes . '>', $this->tk_obj_from_node( $subnode, FALSE, TRUE ), '</' . $subnode->nodeName . '>' );
				
			// Text 
			}else{
				if( $subnode->nodeType == XML_TEXT_NODE && trim( $subnode_value ) != '' ){
					$params['content'][$i] = __( trim( $subnode_value )  , $tkf_text_domain );
					if( $tkf_create_textfiles ) tk_add_text_string( trim( $subnode_value ) );
				}
			}
		}
		
		/*
		 * Calling function / Returning value
		 */
		if( FALSE != $function_name ){
			$params = $this->cleanup_function_params( $function_name, $params );
			$function_result = call_user_func_array( 'tk_db_' . $function_name , $params );
			return $function_result;
		}elseif( $is_html ){
			if( isset( $params['content'] ) )
				return $params['content'];
		}else{
			return $params;
		}
	}

	function cleanup_function_params( $function_name, $params ){
		
		// Checking each param for function
		foreach( $this->functions[ $function_name ] AS $key => $function_params ){
			
			// If function was bound to content or has no content
			if( !isset( $params[ $key ] ) ){
				// If function was bound to content
				
				if( isset( $this->bound_content[ $function_name ] ) ){
					
					if( $this->bound_content[ $function_name ] != '' && $key == $this->bound_content[ $function_name ] ){
						$params_new[ $this->bound_content[ $function_name ] ] = $params[ 'content' ];
					}else{
						// Rewrite key to function name
						$params_new[ $key ] = $this->functions[ $function_name ][ $key ];
					}
				}else{
					$params_new[ $key ] = '';
				}

			// Getting content from set param
			}else{
				$params_new[ $key ] = $params[ $key ];
			}
		}
		return $params_new;
	}
	
	function get_html(){		
		$db = new TK_Display();
		return $db->get_html( $this->display );
	}
	
	function download_text_files( $file_src = FALSE ){
		global $tkf_text_domain, $tkf_text_domain_strings;
		
		$file_content = '<?php ' . chr(10) . chr(10);
		
		foreach ( $tkf_text_domain_strings AS $string ){
			if( $tkf_text_domain != '' ){
				$file_content.= '_e( \'' . $string . '\', \'' . $tkf_text_domain . '\' );' . chr(10);
			}else{
				$file_content.= '_e( \'' . $string . '\' );' . chr(10);
			}	
		}
		if( $tkf_text_domain != '' && !$file_src ){
			$file_src = dirname( __FILE__ ) . '/langfile_' . $tkf_text_domain . '.php';
		}elseif( !$file_src ) {
			$file_src = dirname( __FILE__ ) . '/langfile_' . md5( time() ) . '.php';
		}
		
		header("Content-Type: text/plain");
		header('Content-Disposition: attachment; filename="language_strings.php"');
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		
		echo $file_content;
		
		exit;				
	}
}

/*
 * Shortener functions ( For instancing without classes )
 */
function tk_wml_parse( $wml ){
	if( !empty( $wml ) ){
		$wml_parser = new TK_WML_Parser();	
		$wml_parser->load_wml( $wml );
		return $wml_parser->get_html();
	}else{
		return false;
	}
}
function tk_wml_parse_file( $source ){
	$wml_parser = new TK_WML_Parser();	
	$wml_parser->load_wml_file( $source );
	return $wml_parser->get_html();
}
function tk_wml_create_textfiles( $wml, $destination_src = FALSE ){
	global $tkf_create_textfiles;
	
	$tkf_create_textfiles = TRUE;
	
	if( !empty( $wml ) ){
		$wml_parser = new TK_WML_Parser( TRUE, TRUE );	
		$wml_parser->load_wml( $wml );
		return $wml_parser->download_text_files( $destination_src );
	}else{
		return false;
	}
}
function tk_wml_create_textfiles_from_wml_file( $source, $destination_src = FALSE ){
	global $tkf_create_textfiles;
	
	$tkf_create_textfiles = TRUE;
	
	$wml_parser = new TK_WML_Parser();	
	$wml_parser->load_wml_file( $source );
	return $wml_parser->download_text_files( $destination_src );
}
function tk_add_text_string( $string ){
	global $tkf_text_domain_strings, $tkf_text_domain, $tkf_create_textfiles;
	
	if( $tkf_create_textfiles && trim( $string ) != '' && !in_array( $string, $tkf_text_domain_strings) ){
		array_push( $tkf_text_domain_strings, $string );
	}
}