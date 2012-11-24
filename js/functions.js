jQuery(window).load(function () {  
	var vertical_offset = 10;
	for (var i = 0; i < q2w3_fixed_widgets.length; i++) {
		widget = jQuery('#' + q2w3_fixed_widgets[i]);
		if ( widget.attr('id') ) { // element existsts
			q2w3_fixed_widget(widget, vertical_offset);
			vertical_offset += widget.outerHeight(true);
		}
	}
});		
function q2w3_fixed_widget(widget, vertical_offset) {
	var widget_width = widget.css('width');
	var widget_margin = widget.css('margin');
	var widget_padding = widget.css('padding');
	var top = widget.offset().top - vertical_offset;
	jQuery(window).scroll(function (event) {
		if ( jQuery(this).scrollTop() >= top ) { // fixed
			widget.css('position', 'fixed');
			widget.css('width', widget_width);
			widget.css('margin', widget_margin);
			widget.css('padding', widget_padding);
			widget.css('top', vertical_offset);
		} else { // normal
			widget.css('position', 'static');
		}
	});
}