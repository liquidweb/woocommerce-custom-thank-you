<?php

function wcty_view( $file, $attr_arr = array() ) {

	$view = '';

	if ( empty( $file ) ) {
		return false;
	}

	$extension    = '.php';
	$twem_dirpath = plugin_dir_path( __FILE__ ) . 'view/';

	if ( file_exists( $twem_dirpath . $file . $extension ) ) {
		ob_start();
		include $twem_dirpath . $file . $extension;
		$view = ob_get_clean();
	}

	return $view;
}
