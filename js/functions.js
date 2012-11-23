jQuery(window).load(function () {  
	var i = 0;
	var vertical_offset = 10;
	while ( q2w3_fixed_widgets[i] ) {
		q2w3_fixed_widget(q2w3_fixed_widgets[i], vertical_offset);
		vertical_offset += jQuery('#' + q2w3_fixed_widgets[i]).outerHeight(true);
		i++;
	}
});		

function q2w3_fixed_widget(widget_id, vertical_offset) {
	var widget_width = jQuery('#' + widget_id).css('width');
	var widget_margin = jQuery('#' + widget_id).css('margin');
	var widget_padding = jQuery('#' + widget_id).css('padding');
	var top = jQuery('#' + widget_id).offset().top;
	top -= vertical_offset;
	
	jQuery(window).scroll(function (event) {
		if ( jQuery(this).scrollTop() >= top ) {
			// fixed
			jQuery('#' + widget_id).css('position', 'fixed');
			jQuery('#' + widget_id).css('width', widget_width);
			jQuery('#' + widget_id).css('margin', widget_margin);
			jQuery('#' + widget_id).css('padding', widget_padding);
			jQuery('#' + widget_id).css('top', vertical_offset);
		} else {
			// normal
			jQuery('#' + widget_id).css('position', 'static');
		}
	});
}