jQuery(document).ready(function ($) {


	// minimize button template
	var mae_popup_placeholder =
		'<div class="mae_popup_placeholder"></div>';


	// popup toolbar template
	var popup_toolbar =
		'<div class="popup_toolbar">\
			<div class="minimize_button"><i class="eicon-close"></i></div>\
			<div class="elementor-panel-heading-title popup_heading"></div>\
      </div>';

	// hook elementor code control to inject maximize button 
	var maximize_button_template =
	'<div class="maximize_button_wrapper">\
		<div class="maximize_button code_control_maximize" data-popup-class="mae_code_popup">\
			<i class="eicon-lightbox"></i>\
		</div>\
	</div>';
	var template = $('#tmpl-elementor-control-code-content').html();
	$('#tmpl-elementor-control-code-content').html(maximize_button_template + template);


	// hook elementor texarea control to inject maximize button 
	var maximize_button_template =
	'<div class="maximize_button_wrapper">\
		<div class="maximize_button textarea_control_maximize" data-popup-class="mae_textarea_popup">\
			<i class="eicon-lightbox"></i>\
		</div>\
	</div>';
	var template = $('#tmpl-elementor-control-textarea-content').html();
	$('#tmpl-elementor-control-textarea-content').html(maximize_button_template + template);


	// hook elementor wysiwyg control to inject maximize button 
	var maximize_button_template =
	'<div class="maximize_button_wrapper">\
		<div class="maximize_button wysiwyg_control_maximize" data-popup-class="mae_wysiwyg_popup">\
			<i class="eicon-lightbox"></i>\
		</div>\
	</div>';
	var template = $('#tmpl-elementor-control-wysiwyg-content').html();
	$('#tmpl-elementor-control-wysiwyg-content').html(maximize_button_template + template);





	$(window).load(function () {
		// DETECT ELEMENTOR UI THEME CHANGE
		$(document).on('change', 'select[data-setting="ui_theme"]', function () {
			// console.log('boom');
			val = $(this).val();
			if (val == 'dark') {
				$('head').append('<link id="-dark-mode" rel="stylesheet" href="' + MagnificAddons.mae_plugin_url + 'assets/-dark-mode.css" type="text/css" />');

			}
			if (val == 'light') {
				$('#-dark-mode-css').remove();
				$('#-dark-mode').remove();
			}
			if (val == 'auto') {
				if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
					$('head').append('<link id="-dark-mode" rel="stylesheet" href="' + MagnificAddons.mae_plugin_url + 'assets/-dark-mode.css" type="text/css" />');
				}
			}
		});
	});





	// SET POPUP SETINGS
	if (typeof(Storage) !== "undefined") {
		 var popup_settings = JSON.parse(localStorage.getItem("popup_settings"));
	    if (popup_settings) {
	        $(':root')[0].style.setProperty('--popup_top', popup_settings.top);
	        $(':root')[0].style.setProperty('--popup_left', popup_settings.left);
	        $(':root')[0].style.setProperty('--popup_width', popup_settings.width);
	        $(':root')[0].style.setProperty('--popup_height', popup_settings.height);
	    }
	}




	// TEXT AND CODE WIDGETS POPUP
	$(document).on('click', '.maximize_button', function () {

		$('.minimize_button').trigger('click');

		var el = $(this).closest('.elementor-control').find('.elementor-control-content');

		var popup_class = $(this).attr('data-popup-class');

		// make popup
		$(el).addClass('mae_control_popup ' + popup_class).trigger('resize').prepend(popup_toolbar);

		$(mae_popup_placeholder).insertBefore(el);

		var section_title = $('#elementor-controls .elementor-control-type-section.elementor-open .elementor-panel-heading-title').text();
		$(el).find('.popup_heading').text(section_title);

		settings = {};

		// make druggable and resizable
		init_drag_and_resize('.mae_control_popup', settings);

		$(this).parent().hide();
	});




	$(document).on('click', '.minimize_button', function () {

		var parent = $(this).closest('.elementor-control');
		var el = $(parent).find('.elementor-control-content');

		$(parent).find('.mae_popup_placeholder').remove();

		$(el)[0].className = $(el)[0].className.replace(/\bmae.*?\b/g, '');

		$(el).trigger('resize');
		$(el).find('.popup_toolbar').remove();

		$(parent).find('.maximize_button_wrapper').show();

		$(el).draggable('destroy');
		$(el).resizable('destroy');
		$(el).removeAttr('style').show();

	});

	

	$(document).on('dblclick', '.popup_toolbar', function () {
		var el = $(this).parent();
		if($(el).hasClass('mae_popup_maximized')) {
			$(el).css({
				'top': 'var(--popup_top)',
				'left': 'var(--popup_left)',
				'width': 'var(--popup_width)',
				'height': 'var(--popup_height)',			
			});
			$(el).removeClass('mae_popup_maximized');
		} else {
			$(el).css({
				'top': '0px',
				'left': '0px',
				'width': '100vw',
				'height': '100vh',			
			});
			$(el).addClass('mae_popup_maximized');
		}
	});



	// add resizable and draggable to popup function
	function init_drag_and_resize(el, settings) {
		$(el).draggable({
			stop: function () {
				savePopupSettings(this);
			},
			cancel: ".ace_editor.ace-tm",
			containment: "window",
			iframeFix: true,
			cancel: ".elementor-wp-editor,.ace_editor,.mae_popup_maximized, textarea",
			zIndex: 1000,
		});
		$(el).resizable({
			start: function () {
				$('iframe').css('pointer-events', 'none');
			},
			stop: function () {
				$('iframe').css('pointer-events', '');
				savePopupSettings(this);
				$(this).removeClass('mae_popup_maximized');

			},
			minHeight: 320,
			minWidth: 500,
			containment: "document",
			handles: "all",
		});
	}




	// save popup settings to local storage
	function savePopupSettings(el) {

		var popup_settings = {
			'top': $(el).css('top'),
			'left': $(el).css('left'),
			'height': $(el).css('height'),
			'width': $(el).css('width'),
		}
		 
	    $(':root')[0].style.setProperty('--popup_top', popup_settings.top);
	    $(':root')[0].style.setProperty('--popup_left', popup_settings.left);
	    $(':root')[0].style.setProperty('--popup_width', popup_settings.width);
		 $(':root')[0].style.setProperty('--popup_height', popup_settings.height);
		 
	    if (typeof(Storage) !== "undefined") {
	        localStorage.setItem("popup_settings", JSON.stringify(popup_settings));
	    }

	}

});



