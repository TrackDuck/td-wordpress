<?php

class TrackDuck_Options_Data {

	public static function get() {
		return array(
			'page_title'        => __( 'TrackDuck Options', 'trackduck' ),
			'page_description'  => false,
			'menu_title'        => __( 'TrackDuck', 'trackduck' ),
			'permission'        => 'manage_options',
			'menu_slug'         => 'trackduck_options',
			'option_name'       => 'trackduck_options',
			'plugin_path'       => 'ttrackduck_options',
			'defaults'          => array(
				'trackduck_active'  => false,
				'trackduck_id'      => '',
			),
			'section'           => array(
				array(
					'slug'  => 'trackduck',
					'title' => __( 'Main Settings', 'trackduck' ),
					'field' => array(
						array(
							'slug'        => 'id',
							'title'       => __( 'TrackDuck Project ID', 'trackduck' ),
							'type'        => 'textapi',
							'description' => ' ',
							'options'			=> array(
                'text'        => sprintf(
                  __('TrackDuck - visual feedback and bug tracking solution for web design and development. This plugin enables you and your clients to add comments with screenshots directly from your WordPress website. Receive detailed visual feedback from your customers and error reports from users in seconds! Learn more on <a href="%1$s" target="_blank" >TrackDuck website</a>.','trackduck'),
                  'http://trackduck.com'
                ),
								'button'			=> __('Get Project ID','trackduck'),
							)
						),
						array(
							'slug'  => 'active',
							'title' => __( 'Enable TrackDuck', 'trackduck' ),
							'label' => __( 'Add TrackDuck code to your website', 'trackduck' ),
							'type'  => 'hidden'
						),
					),
				),
        array(
          'slug'  => 'td_extension',
          'title' => __( 'Improve TrackDuck performance', 'trackduck' ),
          'field' => array(
            array(
              'slug'        => 'id',
              'title'       => __( 'TrackDuck Project ID', 'trackduck' ),
              'type'        => 'extension',
              'description' => ' ',
              'options'     => array(
                'text'        => __('Install extension for your browser and enhance screenshot capturing.','trackduck'),
              )
            )
          )
        )
			)
		);
	}
}
