jQuery(window).load(function () {  
	var margin_top = q2w3_fixed_widgets_margin_top;
	var margin_top_accumulate = margin_top;
	var margin_bottom = q2w3_fixed_widgets_margin_bottom;
	var widget_height = new Array();
	for (var i = 0; i < q2w3_fixed_widgets.length; i++) {
		widget = jQuery('#' + q2w3_fixed_widgets[i]);
		if ( widget.attr('id') ) { // element existsts
			widget_height[i] = widget.outerHeight(true);
		} else {
			widget_height[i] = 0;			
		}
	}
	for (var i = 0; i < q2w3_fixed_widgets.length; i++) {
		widget = jQuery('#' + q2w3_fixed_widgets[i]);
		if ( widget.attr('id') ) { // element existsts
			q2w3_fixed_widget(widget, margin_top, margin_top_accumulate, margin_bottom, widget_height, i);
			margin_top_accumulate += widget_height[i];
		}
	}
	if (jQuery.browser.mozilla || jQuery.browser.opera) { // fixes mozilla, opera page refresh problem
		var scroll = jQuery(document).scrollTop();
		jQuery(document).scrollTop(0);
		jQuery(document).scrollTop(scroll);
	}
});		
function q2w3_fixed_widget(widget, margin_top, margin_top_accumulate, margin_bottom, widget_height, i) {
	var widget_width = widget.css('width');
	var widget_margin = widget.css('margin');
	var widget_padding = widget.css('padding');
	var parent_height = widget.parent().height();
	var update_parent_height = false;
	var scroll_border_top = widget.offset().top - margin_top_accumulate;
	for (var j = i + 1; j < widget_height.length; j++) {
		margin_bottom += widget_height[j];
	}
	var scroll_border_bottom = jQuery(document).height() - margin_bottom;
	var widgets_height_accumulate = 0;
	for (var k = i; k >= 0; k--) {
		widgets_height_accumulate += widget_height[k];		
	}
	jQuery(window).scroll(function (event) {
		var scroll_target = jQuery(this).scrollTop() + widgets_height_accumulate + margin_top;
		var scroll_delta = scroll_target - scroll_border_bottom + ( jQuery(window).height() - widgets_height_accumulate - margin_top );
		if ( scroll_target >= scroll_border_bottom ) { // fixed bottom
			if ( !update_parent_height ) { // needed when sidebar is longer then main content
				widget.parent().height(parent_height);
				update_parent_height = true;
			}
			widget.css('position', 'fixed');
			widget.css('top', '');
			widget.css('bottom', scroll_delta);
			widget.css('width', widget_width);
			widget.css('margin', widget_margin);
			widget.css('padding', widget_padding);
		} else if ( jQuery(this).scrollTop() >= scroll_border_top ) { // fixed top
			if ( !update_parent_height ) { // needed when sidebar is longer then main content
				widget.parent().height(parent_height);
				update_parent_height = true;
			}
			widget.css('position', 'fixed');
			widget.css('top', margin_top_accumulate);
			widget.css('bottom', '');
			widget.css('width', widget_width);
			widget.css('margin', widget_margin);
			widget.css('padding', widget_padding);
		} else { // normal
			if ( update_parent_height ) {
				widget.parent().removeAttr('style');
				update_parent_height = false;
			}
			widget.css('position', '');
		}
	});
}