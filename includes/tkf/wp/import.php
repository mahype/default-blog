<?php

class TK_Import_Button extends TK_WP_Fileuploader{
	/**
	 * PHP 4 constructor
	 *
	 * @package Themekraft Framework
	 * @since 0.1.0
	 * 
	 * @param string $name Name of colorfield
	 * @param array $args 
	 */
	function tk_import_button( $name, $args = array() ){
		$this->__construct( $name, $args );
	}
	
	/**
	 * PHP 5 constructor
	 *
	 * @package Themekraft Framework
	 * @since 0.1.0
	 * 
	 * @param string $name Name of colorfield
	 * @param array $args Array of [ $id , $extra Extra colorfield code, option_groupOption group to save data, $before_textfield Code before colorfield, $after_textfield Code after colorfield   ]
	 */
	function __construct( $name, $args = array() ){
		global $post, $tk_form_instance_option_group;
		
		$defaults = array(
			'id' => substr( md5 ( time() * rand() ), 0, 10 ),
			'extra' => '',
			'before_element' => '',
			'uploader' => 'file',
			'after_element' => '',
			'option_group' => $tk_form_instance_option_group
		);
		
		$parsed_args = wp_parse_args( $args, $defaults );
		extract( $parsed_args , EXTR_SKIP );
		
		$this->id = $id;
		$this->delete = TRUE;
		$this->insert_attachement = FALSE;
		$this->wp_name = $name;
		
		$this->done_import = FALSE;
		
		parent::__construct( $name, $parsed_args );
	}
	
	function validate_actions( $input, $value ){
		global $tk_form_instance_option_group;
		
		// If error occured
		if( $_FILES[ $tk_form_instance_option_group . '_values' ]['name'] != ''  ){
			$file[ 'tmp_name' ] = $_FILES[ $tk_form_instance_option_group . '_values' ][ 'tmp_name' ][ $this->wp_name ];
			
			if( '' !=  $file[ 'tmp_name' ] )
				$input = tk_import_values( $tk_form_instance_option_group, $file[ 'tmp_name' ] );			
		}
		
		return $input;
	}

	function get_html(){

		$import_button = tk_form_button( __( 'Import settings', 'tkf' ), array( 'name' => tk_get_field_name( $this->id . '_button' ) ), 'html' ); 
		$this->after_element = $import_button . $this->after_element;
		
		$html = parent::get_html();
		
		return $html;
	}
}
function tk_import_values( $option_group, $file_name ){
	if( !file_exists( $file_name ) )
		return FALSE;
	
	$file_data = implode ( '', file ( $file_name ) );
	
	$values = unserialize( $file_data );
	
	return $values;
}
function tk_import_button( $name, $args, $return = 'echo' ){
	$import_button = new TK_Import_Button( $name, $args );
	return tk_element_return( $import_button, $return );
}
