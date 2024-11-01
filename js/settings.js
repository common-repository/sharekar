
jQuery(document).ready(function(){
	jQuery('#sharekar-social-position').sortable();
	jQuery('.sharekar-colorpicker').wpColorPicker();

	jQuery("[name=sharekar_tab]").on('click', function(){
		jQuery('.sharekar-tab-wrapper').hide();
		history.pushState(null,null,jQuery(this).val());
		jQuery(jQuery(this).val()).show();
	});
	
	if(window.location.hash){
		jQuery('[value='+window.location.hash+']').click();
	}
});
