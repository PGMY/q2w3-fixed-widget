jQuery(document).ready(function () {  
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
	var top = jQuery('#' + widget_id).offset().top - parseFloat(jQuery('#' + widget_id).css('marginTop').replace(/auto/, 0));
	top -= vertical_offset;
	jQuery(window).scroll(function (event) {
		// what the y position of the scroll is
		var y = jQuery(this).scrollTop();
		
		// whether that's below the form
		if (y >= top) {
			// if so, ad the fixed class
			jQuery('#' + widget_id).addClass('q2w3-fixed-widget');
			jQuery('#' + widget_id).css('width', widget_width);
			jQuery('#' + widget_id).css('margin', widget_margin);
			jQuery('#' + widget_id).css('padding', widget_padding);
			jQuery('#' + widget_id).css('top', vertical_offset);
		} else {
			// otherwise remove it
			jQuery('#' + widget_id).removeClass('q2w3-fixed-widget');
		}
	});
}