<?php

/*
 * Menu functions
 */
function tk_db_menu( $id = '', $title = '', $elements = array(), $menu_slug = '', $capability = '', $parent_slug = '', $icon_url = '', $position = '' ){
	
	$args = array(
			'id' => $id,
			'menu_title' => $title,
			'page_title' => $title,
			'capability' => $capability,
			'parent_slug' => $parent_slug,
			'menu_slug' => $menu_slug,			
			'icon_url' => $icon_url,
			'position' => $position,
			'object_menu' => TRUE
	);
	return tk_admin_pages( $elements, $args, 'object' );
}

function tk_db_page( $id, $title, $content, $headline = '', $menu_slug = '' , $icon_url = '' ){
	$page = array( 'id' => $id, 'menu_title' => $title, 'page_title' => $title, 'content' => $content, 'headline' => $headline, 'menu_slug' => $menu_slug, 'icon_url' => $icon_url );
	return $page;
}

/*
 * Post functions
 */
function tk_db_metabox( $id, $title, $content, $post_type ){
	return tk_wp_metabox( $id, $title, $content, $post_type, 'object' );
}

/*
 * Tab functions
 */
function tk_db_tabs( $id = '', $elements = array() ){
	return tk_tabs( $id, $elements, 'object' );
}
function tk_db_tab( $id, $title, $content = '' ){
	return array( 'id' => $id, 'title' => $title, 'content' => $content );
}

/*
 * Accordion functions
 */
function tk_db_accordion( $id, $elements = array() ){
	return tk_accordion( $id, $elements, 'object' );
}
function tk_db_section( $id, $title, $content = '', $css_class = '' ){
	global $tkf_hide_class, $tkf_show_class;
	
	if($css_class != ''){
		$css_class_array = explode(' ', $css_class);
	}
	$style = '';
	
	if(is_array($css_class_array)){
		foreach ($css_class_array as $class) {
			if(in_array($class, $tkf_hide_class))
				$style = 'display:none';
		}
	}
 	
	return array( 'id' => $id, 'title' => $title, 'content' => $content, 'css_class' => $css_class, 'style' => $style );
}
/*
 * Autocomplete functions
 */
function tk_db_autocomplete( $name = '', $css_class = '', $values = array(), $label ){
	if( trim( $label ) != '' ){
		
		tk_add_text_string( $label );
		tk_add_text_string( $tooltip );
		
		$before_element = '<div class="tk_field_row ' . $css_class . '"><div class="tk_field_label"><label for="' . $name . '" title="' . $tooltip . '">' . $label . '</label></div><div class="tk_field">';
		$after_element = '</div></div>';
	}	
	$args = array(
		'id' => $name,
		'before_element' => $before_element,
		'after_element' => $after_element
	);
	
	return tk_jqueryui_autocomplete( $name, $values, $args, 'object' );
}
function tk_db_value( $value ){
	return $value;
}

/*
 * Form function
 */
function tk_db_form( $id, $name, $content = '' ){
	$args = array(
		'id' => $id,
		'name' => $name,
		'css_classes' => '',
		'extra' => '',
		'before_element' => '',
		'after_element' => '',
	);
	$form = tk_form( $name, $name, $content, $args, 'object' );
	return $form;
}

/*
 * Form element functions
 */
function tk_db_textfield( $name, $css_class = '', $label, $tooltip, $description, $link ){
	global $tkf_hide_class, $tkf_show_class;
	
	if($link != '')
		$link = '<div class="field_link"> <a title="' . __( 'Go to this topic in our Knowledge Base', 'tkf') . '" href="' . $link . '" target="_blank">&rarr; ' . __( 'More help', 'tkf') . '.</a></div>';
		
	if( trim( $label ) != '' ){
			
		tk_add_text_string( $label );
		tk_add_text_string( $tooltip );
		
		if($css_class != ''){
			$css_class_array = explode(' ', $css_class);
		}
		$style_str = '';
		
		if(is_array($css_class_array)){
			foreach ($css_class_array as $class) {
				if(in_array($class, $tkf_hide_class))
					$style_str = ' style="display:none"';
			}
		}
	
		$before_element = '<div class="tk_field_row ' . $css_class . '"' . $style_str . '><div class="tk_field_main"><div class="tk_field_label"><label for="' . $name . '" title="' . $tooltip . '">' . $label . '</label></div><div class="tk_field"><div class="tk_field_option">';
		$after_element = '</div></div></div><div class="field_description">' . $description . $link . '</div></div>';
	}		 
	$args = array(
		'id' => $name,
		'before_element' => $before_element,
		'after_element' => $after_element
	);
	return tk_form_textfield( $name, $args, 'object' );
}

function tk_db_textarea( $name, $css_class = '', $label, $tooltip, $description, $link ){
	global $tkf_hide_class, $tkf_show_class;
	
	if($link != '')
		$link = '<div class="field_link"> <a title="Go to this topic in our Knowledge Base" href="' . $link . '" target="_blank">&rarr; More help.</a></div>';
	
	if( trim( $label ) != '' ){

		tk_add_text_string( $label );
		tk_add_text_string( $tooltip );
		
		if($css_class != ''){
			$css_class_array = explode(' ', $css_class);
		}
		$style_str = '';
		
		if(is_array($css_class_array)){
			foreach ($css_class_array as $class) {
				if(in_array($class, $tkf_hide_class))
					$style_str = ' style="display:none"';
			}
		}

		$before_element = '<div class="tk_field_row ' . $css_class . '"' . $style_str . '><div class="tk_field_main wide"><div class="tk_field_label"><label for="' . $name . '" title="' . $tooltip . '">' . $label . '</label><div class="field_description">' . $description . $link . '</div></div><div class="tk_field"><div class="tk_field_option">';
		$after_element = '</div></div></div></div>';
	}		 
	$args = array(
		'id' => $name,
		'before_element' => $before_element,
		'after_element' => $after_element
	);
	return tk_form_textarea( $name, $args, 'object' );
}
function tk_db_checkbox( $name, $css_class = '', $label, $tooltip, $description, $link ){
	global $tkf_hide_class, $tkf_show_class;

	if($link != '')
		$link = '<div class="field_link"> <a title="' . __( 'Go to this topic in our Knowledge Base', 'tkf') . '" href="' . $link . '" target="_blank">&rarr; ' . __( 'More help', 'tkf') . '.</a></div>';
			
	if( trim( $label ) != '' ){
		
		tk_add_text_string( $label );
		tk_add_text_string( $tooltip );
		
		if($css_class != ''){
			$css_class_array = explode(' ', $css_class);
		}
		$style_str = '';
		
		if(is_array($css_class_array)){
			foreach ($css_class_array as $class) {
				if(in_array($class, $tkf_hide_class))
					$style_str = ' style="display:none"';
			}
		}
	
		$before_element = '<div class="tk_field_row ' . $css_class . '"' . $style_str . '><div class="tk_field_main"><div class="tk_field_label"><label for="' . $name . '" title="' . $tooltip . '">' . $label . '</label></div><div class="tk_field"><div class="tk_field_option">';
		$after_element = '</div></div></div><div class="field_description">' . $description . $link . '</div></div>';
	}else{
		$after_element = '<div class="field_description">' . $description . '</div>' . $link;
	}
	$args = array(
		'id' => $name,
		'before_element' => $before_element,
		'after_element' => $after_element
	);
	return tk_form_checkbox( $name, $args, 'object' );
}
function tk_db_radio( $name, $css_class = '', $value, $label, $tooltip, $description, $link ){
	global $tkf_hide_class, $tkf_show_class;

	if($link != '')
		$link = '<div class="field_link"> <a title="Go to this topic in our Knowledge Base" href="' . $link . '" target="_blank">&rarr; More help.</a></div>';
			
	if( trim( $label ) != '' ){
		
		tk_add_text_string( $label );
		tk_add_text_string( $tooltip );
		tk_add_text_string( $description );
		
		if($css_class != ''){
			$css_class_array = explode(' ', $css_class);
		}
		$style_str = '';
		
		if(is_array($css_class_array)){
			foreach ($css_class_array as $class) {
				if(in_array($class, $tkf_hide_class))
					$style_str = ' style="display:none"';
			}
		}
		
		$before_element = '<div class="tk_field_row ' . $css_class . '"' . $style_str . '><div class="tk_field_main"><div class="tk_field_label"><label for="' . $name . '" title="' . $tooltip . '">' . $label . '</label></div><div class="tk_field"><div class="tk_field_option">';
		$after_element = '</div></div></div><div class="field_description">' . $description . $link . '</div></div>';

		}else{
			$after_element = '<div class="field_description">' . $description . '</div>' . $link;
		}
		
	$args = array(
		'id' => $name,
		'before_element' => $before_element,
		'after_element' => $after_element
	);
	return tk_form_radiobutton( $name, $value, $args, 'object' );
}

function tk_db_select( $name, $options, $multiselect = FALSE, $size = '', $label, $tooltip = '', $description, $link, $css_class = '', $onchange = '' ){
	global $tkf_hide_class_options, $tkf_hide_class, $tkf_show_class;	
	
	if($link != '')
		$link = '<div class="field_link"> <a title="Go to this topic in our Knowledge Base" href="' . $link . '" target="_blank">&rarr; More help.</a></div>';
	
	$found_onchange_hide_functions = FALSE;
	
	if( is_array($options) ):
		foreach ( $options AS $option ):
			if( !empty( $option['hide_class'] ) ):
				$found_onchange_hide_functions = TRUE;
				break;
			endif;
		endforeach;
	endif;
	
	if( $found_onchange_hide_functions ):
		$onchange.= 'hide_class(\'' . $name . '\');';
	endif;
	
	if( trim( $label ) != '' ){
			
		tk_add_text_string( $label );
		tk_add_text_string( $tooltip );
		
		$tmp_class_hide = '';
		
		if($css_class != ''){
			$css_class_array = explode(' ', $css_class);
		}
		$style_str = '';
		
		if(is_array($css_class_array)){
			foreach ($css_class_array as $class) {
				if(in_array($class, $tkf_hide_class))
					$style_str = ' style="display:none"';
			}
		}

		$before_element = '<div class="tk_field_row ' . $css_class . '"' . $style_str . '><div class="tk_field_main"><div class="tk_field_label"><label for="' . $name . '" title="' . $tooltip . '">' . $label . '</label></div><div class="tk_field"><div class="tk_field_option">';
		$after_element = '</div></div></div><div class="field_description">' . $description . $link . '</div></div>';

		if( is_array($tkf_hide_class_options[$name]) ){
			foreach($tkf_hide_class_options[$name] as $key => $tkf_hide_class_option){
				if($key != 'value') {
					foreach($tkf_hide_class_option as $option){
		
						if($option[hide_class] != '' || $option[show_class] != '' ) {
							if($option[hide_class] != '')
						   		$after_element .= '<input type="hidden" name="hide_' . $option[value] . '" class="' . $name . '" value="' . $option[hide_class] . '" >';
						    
						    if($option[show_class] != '')
						   		$after_element .= '<input type="hidden" name="show_' . $option[value] . '" class="' . $name . '" value="' . $option[show_class] . '" >';
						
						}
						
					}
				}
			}
		}
		
	}
	
	$args = array(
		'id' => $name,
		'multiselect' =>  (boolean) $multiselect,
		'size' =>  $size,
		'onchange' => $onchange,
		'before_element' => $before_element,
		'after_element' => $after_element
	);
	return tk_form_select( $name, $options, $args , 'object' );
}

function tk_db_option( $id, $value, $name, $hide_class ){
	
	if( $name == '' )
		$name = $value;
		
	tk_add_text_string( $name );
	
	return array( 'id' => $id, 'value' => $value, 'option_name' => $name, 'hide_class' => $hide_class );
}

function tk_db_button( $name ){
	
	tk_add_text_string( $name );
	
	$args = array(
		'id' => $name
	);
	return tk_form_button( $name, $args, 'object' );
}

function tk_db_import( $name, $css_class = '', $label, $tooltip, $description, $link ){

	if($link != '')
		$link = '<div class="field_link"> <a title="Go to this topic in our Knowledge Base" href="' . $link . '" target="_blank">&rarr; More help.</a></div>';

	$args = array(
		'id' => $name,
		'name' => $name,
		'before_element' => $before_element,
		'after_element' => $after_element
	);
	
	return tk_import_button( $name, $args, 'object' );
}

function tk_db_export( $name, $css_class = '', $forms, $label, $file_name, $tooltip, $description, $link ){

	if($link != '')
		$link = '<div class="field_link"> <a title="Go to this topic in our Knowledge Base" href="' . $link . '" target="_blank">&rarr; More help.</a></div>';
		
	$forms = explode( ',', $forms );
	
	if( $file_name == '' )
		$file_name = 'export_' . date( 'Ymdhis', time() ) . '.tkf';
	
	$args = array(
		'id' => $name,
		'name' => $name,
		'forms' => $forms,
		'file_name' => $file_name,
		'before_element' => $before_element,
		'after_element' => $after_element
	);
	
	return tk_export_button( $label, $args, 'object' );
}

function tk_db_colorpicker( $name, $css_class = '', $label, $tooltip, $description, $link ){
	global $tkf_hide_class, $tkf_show_class;

	if($link != '')
		$link = '<div class="field_link"> <a title="Go to this topic in our Knowledge Base" href="' . $link . '" target="_blank">&rarr; More help.</a></div>';
		
	if( trim( $label ) != '' ){
		
		tk_add_text_string( $label );
		tk_add_text_string( $tooltip );
		
		if($css_class != ''){
			$css_class_array = explode(' ', $css_class);
		}
		$style_str = '';
		
		if(is_array($css_class_array)){
			foreach ($css_class_array as $class) {
				if(in_array($class, $tkf_hide_class))
					$style_str = ' style="display:none"';
			}
		}
		
		$before_element = '<div class="tk_field_row ' . $css_class . '"' . $style_str . '><div class="tk_field_main"><div class="tk_field_label"><label for="' . $name . '" title="' . $tooltip . '">' . $label . '</label></div><div class="tk_field"><div class="tk_field_option">';
		$after_element = '</div></div></div><div class="field_description">' . $description . $link . '</div></div>';
	}		 
	$args = array(
		'id' => $name,
		'before_element' => $before_element,
		'after_element' => $after_element
	);
	return tk_form_colorpicker( $name, $args, 'object' );
}



function tk_db_file( $name, $css_class = '', $label, $tooltip, $description, $link, $uploader = 'wp', $delete = FALSE ){
	global $tkf_hide_class, $tkf_show_class;
	
	if($link != '')
		$link = '<div class="field_link"> <a title="Go to this topic in our Knowledge Base" href="' . $link . '" target="_blank">&rarr; More help.</a></div>';
	
	if( trim( $label ) != '' ){
		
		tk_add_text_string( $label );
		tk_add_text_string( $tooltip );
		
		if($css_class != ''){
			$css_class_array = explode(' ', $css_class);
		}
		$style_str = '';
		
		if(is_array($css_class_array)){
			foreach ($css_class_array as $class) {
				if(in_array($class, $tkf_hide_class))
					$style_str = ' style="display:none"';
			}
		}
		
		$before_element = '<div class="tk_field_row ' . $css_class . '"' . $style_str . '><div class="tk_field_main"><div class="tk_field_label"><label for="' . $name . '" title="' . $tooltip . '">' . $label . '</label></div><div class="tk_field"><div class="tk_field_option">';
		$after_element = '</div></div></div><div class="field_description">' . $description . $link . '</div></div>';
	}
	
	if( strtolower( $delete ) == 'true' ){
		$delete = TRUE;
	} else {
		$delete = FALSE;
	}
	 
	$args = array(
		'id' => $name,
		'delete' => $delete,
		'before_element' => $before_element,
		'after_element' => $after_element,
		'uploader' => $uploader
	);
	
	return tk_form_fileuploader( $name, $args, 'object' );
}