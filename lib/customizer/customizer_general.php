<?php

namespace Roots\Sage\Customizer;

use Roots\Sage\Assets;
use Roots\Sage\Extras;

function make_customizer_section($wpc, $section, $args, $hideable = true) {
	$wpc->add_section( $section , $args );

	//Show disable checkbox
	if ($hideable) {
		$wpc->add_setting($section . '_hide', array(
			'default' => false,
			'transport' => 'postMessage'
		));

		$wpc->add_control( $section . '_hide', array(
			'type' => 'checkbox',
			'priority' => 1, // Within the section.
			'section' => $section,
			'label' => __( 'Disable Section' ),
			'description' => __( '' )
		) );
	}
}

