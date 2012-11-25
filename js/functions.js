jQuery(window).load(function () {  
	var offset_top = 10;
	for (var i = 0; i < q2w3_fixed_widgets.length; i++) {
		widget = jQuery('#' + q2w3_fixed_widgets[i]);
		if ( widget.attr('id') ) { // element existsts
			q2w3_fixed_widget(widget, offset_top);
			offset_top += widget.outerHeight(true);
		}
	}
});		
function q2w3_fixed_widget(widget, offset_top) {
	var widget_width = widget.css('width');
	var widget_margin = widget.css('margin');
	var widget_padding = widget.css('padding');
	var top = widget.offset().top - offset_top;
	var parent_height = widget.parent().height();
	var update_parent_height = false;
	jQuery(window).scroll(function (event) {
		if ( jQuery(this).scrollTop() >= top ) { // fixed
			if ( !update_parent_height ) { // needed when sidebar is longer then main content
				widget.parent().height(parent_height);
				update_parent_height = true;
			}
			widget.css('position', 'fixed');
			widget.css('width', widget_width);
			widget.css('margin', widget_margin);
			widget.css('padding', widget_padding);
			widget.css('top', offset_top);
		} else { // normal
			if ( update_parent_height ) {
				widget.parent().removeAttr('style');
				update_parent_height = false;
			}
			widget.css('position', 'static');
		}
	});
}