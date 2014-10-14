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

TrackDuck.getSettings = function (my_href){
   var url = 'https://app.trackduck.com/api/bar/settings/?url=' + encodeURIComponent(my_href);
   var xhr = TrackDuck.createCORSRequest('GET', url);
   TrackDuck.xhr = xhr;
   if (xhr === null) {
    jQuery('#trackduck_options_trackduck_id').parent().find('.description').first().html(
    	trackduck_admin_data.update_browser
    );
		jQuery('#get_ajax_trackduck_options_trackduck_id').hide();
		return false;
   }
   // Response handlers.
   xhr.onload = function() {
     return xhr.status;
   };
   xhr.onerror = function(err) {
       console.log('network error', {xhrUrl: url, xhrTimeout: err.target.timeout});
   };
   xhr.withCredentials = true;
   xhr.send();
};

jQuery(document).ready(function(){
  var my_href = trackduck_admin_data.url;
  if (jQuery('#trackduck_options_trackduck_id').val() && jQuery('#trackduck_options_trackduck_active').val()) {
    jQuery('.textapi_text').after(
      '<button id="trackduck_disable" type="submit" class="button button-primary">'+trackduck_admin_data.disable+'</button> '+
      '<a href="https://app.trackduck.com/project/'+jQuery('#trackduck_options_trackduck_id').val()+'/settings/domains?utm_source=plugin&utm_medium=wp&utm_content=en&utm_campaign=wp-hosted-plugin" target="_blank" class="button button-primary">'+trackduck_admin_data.project+'</a>'
    );
    jQuery('#trackduck_disable').click(function(){
      jQuery('#trackduck_options_trackduck_active').val('');
    });
  } else {
    TrackDuck.getSettings(trackduck_admin_data.url);
    var response = JSON.parse(TrackDuck.xhr.response);
    switch (response.status) {
      // not logged in
      case 401 : 
        jQuery('.textapi_text').after(
          trackduck_admin_data.login
        );
      break;
      // no project
      case 403 : 
        jQuery('.textapi_text').after(
          '<a href="https://app.trackduck.com/project/new?utm_source=plugin&utm_medium=wp&utm_content=en&utm_campaign=wp-hosted-plugin&url='+encodeURIComponent(my_href)+'" target="_blank" id="trackduck_enable" class="button button-primary">'+
          trackduck_admin_data.enable
          +'</a>'
        );
      break;
      // everything ok
      case 200 : 
        jQuery('#trackduck_options_trackduck_id').val(response.projectId);
        jQuery('#trackduck_options_trackduck_active').val("true");
        jQuery('.textapi_text').after(
          '<a href="#" id="trackduck_enable" class="button button-primary">'+
          trackduck_admin_data.enable
          +'</a>'
        );
        jQuery(document).on('click', "#trackduck_enable",function(e){
          jQuery("#submit").trigger("click");
          e.preventDefault();
        });
      break;
    }
  }
});

jQuery('.form-table').first().next().hide().next().hide();
jQuery(document).ready(function(){
  if (jQuery('#trackduck_options_trackduck_id').val() && jQuery('#trackduck_options_trackduck_active').val()) {
    navigator.sayswho= (function(){
        var ua= navigator.userAgent, 
        N= navigator.appName, tem, 
        M= ua.match(/(opera|chrome|safari|firefox|msie|trident)\/?\s*([\d\.]+)/i) || [];
        M= M[2]? [M[1], M[2]]:[N, navigator.appVersion, '-?'];
        return M[0];
    })();
    if(navigator.sayswho=="Chrome"){jQuery('#trackduck_firefox').hide();}
    if(navigator.sayswho=="Firefox"){jQuery('#trackduck_chrome').hide();}
    jQuery('.form-table').first().next().show().next().show();
  }
});