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
                  __('TrackDuck - visual feedback and bug tracking solution for web. After enabling TrackDuck plugin, you will be able to add comments with screenshots directly from your WordPress website, and receive detailed feedback from your customers. You can also receive error reports from users. Take control of feedback and communication with clients, sign up for track duck for free. Learn more on <a href=\"%1$s\">TrackDuck website</a>.','trackduck'),
                  'http://trackduck.com'                  
                ),
								'button'			=> __('Get Project ID','trackduck'),
								'script'			=> '
var TrackDuck = {};

TrackDuck.createCORSRequest = function(method, url) {
   var xhr = new XMLHttpRequest();
   if ("withCredentials" in xhr) {
       // XHR for Chrome/Firefox/Opera/Safari/IE10.
//       xhr.open(method, url, true);
       xhr.open(method, url, false);
   } else if (typeof XDomainRequest != "undefined") {
       // XDomainRequest for IE8-9. It has losts of bugs and problems: headers, cookies (auth), http not allow to https.
       // IE8-9 is not working now!
       // http://blogs.msdn.com/b/ieinternals/archive/2010/05/13/xdomainrequest-restrictions-limitations-and-workarounds.aspx
       //xhr = new XDomainRequest();
       //xhr.open(method, url);
       xhr = null;
   } else {
       // CORS not supported.
       xhr = null;
   }
   return xhr;
};

TrackDuck.getSettings = function (my_href,redirect){
   var url = \'https://app.trackduck.com/api/bar/settings/?url=\' + encodeURIComponent(my_href);
   var xhr = TrackDuck.createCORSRequest(\'GET\', url);
   TrackDuck.xhr = xhr;
   if (xhr === null) {
    jQuery(\'#trackduck_options_trackduck_id\').parent().find(\'.description\').first().html(
    	\''.sprintf(
    		__('Please, update your browser. Currently we support all modern browsers and IE 10+','trackduck'),
    		'https://app.trackduck.com/'
    	).'\'
    );
		jQuery(\'#get_ajax_trackduck_options_trackduck_id\').hide();
		return false;
   }
   // Response handlers.
   xhr.onload = function() {
       if (xhr.status === 200) {
           var resp = JSON.parse(xhr.responseText);
           jQuery(\'#trackduck_options_trackduck_id\').val(resp.projectId);
           jQuery(\'#trackduck_options_trackduck_active\').val("true");
           jQuery("#submit").trigger("click");
       }else if(xhr.status === 403){
          if (jQuery("#trackduck_enable").size()==0) {
            jQuery(\'.textapi_text\').after(
              \''.sprintf(
                '<a href="%1$s" target="_blank" id="trackduck_enable" class="button button-primary">'.__('Enable integration','trackduck').'</a>',
                'https://app.trackduck.com/#/project/new/step1?url=\'+encodeURIComponent(my_href)+\''
              ).'\'
            );
            jQuery(document).on(\'click\', "#trackduck_enable",function(e){
              TrackDuck.getSettings(\''.get_bloginfo('url').'\',jQuery(this).attr("href"));
              console.log(TrackDuck.xhr);
              if (TrackDuck.xhr.status=="403") {
                // go to TrackDuck
              } else {
                e.preventDefault();
              }
            });            
          }
       }else if(xhr.status === 401){
          jQuery(\'.textapi_text\').after(
           	\''.sprintf(
           		'<p><a href="%2$s" class="button" id="trackduck_google">'.__('Google signup','trackduck').'</a> <a href="%3$s" class="button" id="trackduck_facebook">'.__('Login with Facebook','trackduck').'</a></p><p><a href="%1$s">'.__('Login or register with email','trackduck').'</a></p>',
           		'https://app.trackduck.com/auth/login?redirect='.admin_url( "admin.php?page=trackduck_options" ),
           		'https://app.trackduck.com/auth/google?redirect='.admin_url( "admin.php?page=trackduck_options" ),
           		'https://app.trackduck.com/auth/Facebook?redirect='.admin_url( "admin.php?page=trackduck_options" )
           	).'\'
          );
       } else {
           TrackDuck.error(\'TD API Exception status:\'+ xhr.status, {responseText: xhr.responseText});
       }
     return xhr.status;
   };
   xhr.onerror = function(err) {
       console.log(\'network error\', {xhrUrl: url, xhrTimeout: err.target.timeout});
   };

   xhr.withCredentials = true;
   xhr.send();
};

jQuery(document).ready(function(){
  if (!jQuery(\'#trackduck_options_trackduck_id\').val()){
    TrackDuck.getSettings(\''.get_bloginfo('url').'\');
  } else if (!jQuery(\'#trackduck_options_trackduck_active\').val()){
    var my_href = \''.get_bloginfo('url').'\';
          if (jQuery("#trackduck_enable").size()==0) {
            jQuery(\'.textapi_text\').after(
              \''.sprintf(
                '<a href="%1$s" target="_blank" id="trackduck_enable" class="button button-primary">'.__('Enable integration','trackduck').'</a>',
                'https://app.trackduck.com/#/project/new/step1?url=\'+encodeURIComponent(my_href)+\''
              ).'\'
            );
            jQuery(document).on(\'click\', "#trackduck_enable",function(e){
              TrackDuck.getSettings(\''.get_bloginfo('url').'\',jQuery(this).attr("href"));
              console.log(TrackDuck.xhr);
              if (TrackDuck.xhr.status=="403") {
                // go to TrackDuck
              } else {
                e.preventDefault();
              }
            });            
          }
  } else {
    console.log(jQuery(\'#trackduck_options_trackduck_id\').val());
           jQuery(\'.textapi_text\').after(
            \'<button id="trackduck_disable" type="submit" class="button button-primary">'.__('Disable integration','trackduck').'</button> \'+
            \'<a href="https://app.trackduck.com/#/project/\'+jQuery(\'#trackduck_options_trackduck_id\').val()+\'/settings" target="_blank" class="button button-primary">'.__('Open project settings','trackduck').'</a>\'
           );
           jQuery(\'#trackduck_disable\').click(function(){
//             jQuery(\'#trackduck_options_trackduck_id\').val(\'\');
             jQuery(\'#trackduck_options_trackduck_active\').val(\'\');
           });
  }
});
'
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
                'text'        => __('Install extension for your favourite browser and to enhance screenshot capturing','trackduck'),
                'script'      => '
jQuery(\'.form-table\').first().next().hide().next().hide();
jQuery(document).ready(function(){
  if (jQuery(\'#trackduck_options_trackduck_id\').val() && jQuery(\'#trackduck_options_trackduck_active\').val()) {
    navigator.sayswho= (function(){
        var ua= navigator.userAgent, 
        N= navigator.appName, tem, 
        M= ua.match(/(opera|chrome|safari|firefox|msie|trident)\/?\s*([\d\.]+)/i) || [];
        M= M[2]? [M[1], M[2]]:[N, navigator.appVersion, \'-?\'];
        return M[0];
    })();
    if(navigator.sayswho=="Chrome"){jQuery(\'#trackduck_firefox\').hide();}
    if(navigator.sayswho=="Firefox"){jQuery(\'#trackduck_chrome\').hide();}
    jQuery(\'.form-table\').first().next().show().next().show();
  }
});
                '
              )
            )
          )
        )
			)
		);
	}
}
