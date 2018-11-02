(function($) {
  // Site title
  wp.customize('blogname', function(value) {
    value.bind(function(to) {
      $('.brand').text(to);
    });
	});

	wp.customize('home_page_header_hide', function(value) {
		value.bind(function(to) {
			$('.section-home-header').toggle(!to);
		});
	});

	wp.customize('home_page_post_hide', function(value) {
		value.bind(function(to) {
			$('#section-home-page-post').toggle(!to);
		});
	});

	wp.customize('home_page_our_stories_hide', function(value) {
		value.bind(function(to) {
			$('#section-home-page-our_stories').toggle(!to);
		});
	});

	wp.customize('home_page_post_link_label', function(value) {
		value.bind(function(to) {
			$('#section-home-page-post .more-link').text(to);
		});
	});

	wp.customize('home_page_our_stories_link_label', function(value) {
		value.bind(function(to) {
			$('#section-home-page-our_stories .more-link').text(to);
		});
	});

	wp.customize('home_page_post_background', function(value) {
		value.bind(function(to) {
			$("#section-home-page-post").removeClass (function (index, className) {
				return (className.match (/(^|\s)bg-\S+/g) || []).join(' ');
			});
			$("#section-home-page-post").addClass('bg-' + to);
		});
	});

	wp.customize('home_page_our_stories_background', function(value) {
		value.bind(function(to) {
			$("#section-home-page-our_stories").removeClass (function (index, className) {
				return (className.match (/(^|\s)bg-\S+/g) || []).join(' ');
			});
			$("#section-home-page-our_stories").addClass('bg-' + to);
		});
	});

	wp.customize('home-page-widgets-1_background', function(value) {
		value.bind(function(to) {
			$("#section-home-page-widgets-1").removeClass (function (index, className) {
				return (className.match (/(^|\s)bg-\S+/g) || []).join(' ');
			});
			$("#section-home-page-widgets-1").addClass('bg-' + to);
		});
	});

	wp.customize('home-page-widgets-2_background', function(value) {
		value.bind(function(to) {
			$("#section-home-page-widgets-2").removeClass (function (index, className) {
				return (className.match (/(^|\s)bg-\S+/g) || []).join(' ');
			});
			$("#section-home-page-widgets-2").addClass('bg-' + to);
		});
	});

	wp.customize('home-page-widgets-3_background', function(value) {
		value.bind(function(to) {
			$("#section-home-page-widgets-3").removeClass (function (index, className) {
				return (className.match (/(^|\s)bg-\S+/g) || []).join(' ');
			});
			$("#section-home-page-widgets-3").addClass('bg-' + to);
		});
	});

})(jQuery);
