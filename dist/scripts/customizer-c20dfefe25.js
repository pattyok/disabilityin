!function(o){wp.customize("blogname",function(e){e.bind(function(e){o(".brand").text(e)})}),wp.customize("home_page_header_hide",function(e){e.bind(function(e){o(".section-home-header").toggle(!e)})}),wp.customize("home_page_post_hide",function(e){e.bind(function(e){o(".section-home-post").toggle(!e)})}),wp.customize("home_page_post_background",function(e){e.bind(function(e){console.log(e),o("#section-home-page-post").removeClass(function(o,e){return(e.match(/(^|\s)bg-\S+/g)||[]).join(" ")}),o("#section-home-page-post").addClass("bg-"+e)})}),wp.customize("home_page_post_background",function(e){e.bind(function(e){console.log(e),o("#section-home-page-our_stories").removeClass(function(o,e){return(e.match(/(^|\s)bg-\S+/g)||[]).join(" ")}),o("#section-home-our_stories").addClass("bg-"+e)})}),wp.customize("home-page-widgets-1_background",function(e){e.bind(function(e){o("#section-home-page-widgets-1").removeClass(function(o,e){return(e.match(/(^|\s)bg-\S+/g)||[]).join(" ")}),o("#section-home-page-widgets-1").addClass("bg-"+e)})}),wp.customize("home-page-widgets-2_background",function(e){e.bind(function(e){o("#section-home-page-widgets-2").removeClass(function(o,e){return(e.match(/(^|\s)bg-\S+/g)||[]).join(" ")}),o("#section-home-page-widgets-2").addClass("bg-"+e)})}),wp.customize("home-page-widgets-3_background",function(e){e.bind(function(e){o("#section-home-page-widgets-3").removeClass(function(o,e){return(e.match(/(^|\s)bg-\S+/g)||[]).join(" ")}),o("#section-home-page-widgets-3").addClass("bg-"+e)})})}(jQuery);