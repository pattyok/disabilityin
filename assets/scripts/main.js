/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {
  var positionBackToTop = function () {
    var right;
    var offset = $('.to-top-wrapper').offset();
    right = ($(window).width() - $('.to-top-wrapper').outerWidth())/2 ;
    if (right < 10){
      right = 10;
    }
    $('.to-top').css('right', right);
  };
    /** IMAGE MANIPULATION **/
        //Allows images to be used of any orientation and fit into the square etc

        //either get size from data attributes or from the actual image
        function getImageSize(img, callback) {
          //image may have data-attr with height / width or we have to get it
          if (typeof(img.attr('data-height')) !== 'undefined' && typeof(img.attr('data-width')) !== 'undefined'){
            if (callback !== undefined) {
              callback({width:img.attr('data-width'), height: img.attr('data-height')});
            }
          } else {
            var newImg = new Image();
            newImg.onload = function () {
              if (callback !== undefined) {
                callback({width: newImg.width, height: newImg.height});
              }
            };
            newImg.src = img.attr('src');
          }
        }

        //MAIN FUNCTION Pass in jQuery object of the image and the element to bound the size to
        var smartCropImages = function(imgEl, containerEl, callback) {
          var containerRatio = containerEl.outerHeight() / containerEl.outerWidth();
          var dbg = containerEl.outerHeight() + '/' + containerEl.outerWidth() + ' : ' + containerEl.outerHeight() / containerEl.outerWidth();
          getImageSize(imgEl, function(natSize){
            console.log(natSize);
            var imageRatio = natSize.height/natSize.width;
            dbg += natSize.height + '/' + natSize.width + ' : ' + natSize.height/natSize.width;
            imgEl.removeClass('crop-width').removeClass('crop-height');
            if (containerRatio > imageRatio) {
              imgEl.addClass('crop-width');
              dbg += 'crop-width';
            } else {
              imgEl.addClass('crop-height');
              dbg += 'crop-height';
            }
            //console.log(dbg);
            imgEl.removeClass('loading');
            if (callback !== undefined) {
              callback();
            }
          });
        };

        var runSmartCrop = function(context, callback) {
          var el = '.responsive-img-wrapper img';
          if (context) {
            el = context + ' ' + el;
          }
          $(el).each(function(){
            smartCropImages($(this), $(this).parents('.responsive-img-wrapper'));
          });
          if (callback) {
            callback();
          }
        };

        var setVideoHeights = function() {
          $('.responsive-video-match-height').each(function() {
            var row = $(this).parents('.row');
            $(this).css({
              'padding-top' : 0 + 'px'
            });
            var height = row.outerHeight();
            if ($(window).width() > 768 && height > 0) {
              if (height > 0) {
                $(this).css({
                  'padding-top' : height + 'px'
                });
              }
            } else {
              var width = $(this).outerWidth();
              height = '56.25%';///maintain 16x9 ratio
              $(this).css({
                'padding-top' : height
              });
            }
          });
        };
/*
*  add_marker
*
*  This function will add a marker to the selected Google Map
*
*  @type  function
*  @date  8/11/2013
*  @since 4.3.0
*
*  @param $marker (jQuery element)
*  @param map (Google Map object)
*  @return  n/a
*/

function add_marker( $marker, map ) {

  // var
  var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );

  // create marker
  var marker = new google.maps.Marker({
    position  : latlng,
    map     : map
  });

  // add to array
  map.markers.push( marker );

  // if marker contains HTML, add it to an infoWindow
  if( $marker.html() )
  {
    // create info window
    var infowindow = new google.maps.InfoWindow({
      content   : $marker.html()
    });

    // show info window when marker is clicked
    google.maps.event.addListener(marker, 'click', function() {

      infowindow.open( map, marker );

    });
  }

}

/*
*  center_map
*
*  This function will center the map, showing all markers attached to this map
*
*  @type  function
*  @date  8/11/2013
*  @since 4.3.0
*
*  @param map (Google Map object)
*  @return  n/a
*/

function center_map( map ) {

  // vars
  var bounds = new google.maps.LatLngBounds();

  // loop through all markers and create bounds
  $.each( map.markers, function( i, marker ){

    var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

    bounds.extend( latlng );

  });

  // only 1 marker?
  if( map.markers.length === 1 )
  {
    // set center of map
      map.setCenter( bounds.getCenter() );
      map.setZoom( 16 );
  }
  else
  {
    // fit to bounds
    map.fitBounds( bounds );
  }

}

      /*
*  new_map
*
*  This function will render a Google Map onto the selected jQuery element
*
*  @type  function
*  @date  8/11/2013
*  @since 4.3.0
*
*  @param $el (jQuery element)
*  @return  n/a
*/

function new_map( $el ) {
  // var
  var $markers = $el.find('.marker');

  // vars
  var args = {
    zoom    : 16,
    center    : new google.maps.LatLng(0, 0),
    mapTypeId : google.maps.MapTypeId.ROADMAP
  };


  // create map
  var map = new google.maps.Map( $el[0], args);


  // add a markers reference
  map.markers = [];


  // add markers
  $markers.each(function(){

      add_marker( $(this), map );

  });


  // center map
  center_map( map );


  // return
  return map;

}



  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        // JavaScript to be fired on all pages
        runSmartCrop();
        positionBackToTop();
        setVideoHeights();
        $( window ).resize(function() {
          runSmartCrop();
          setVideoHeights();
          positionBackToTop();
        });
        $(window).scroll(function(){
            var position = $(window).scrollTop();
            var topOffset = 100; //when do we show the link
            var bottomOffset = $(document).height() - $(window).height() - $('footer').height(); //when to stick it to the footer
            if (position > topOffset) {
              $('.to-top').addClass('is-visible');
            } else {
              $('.to-top').removeClass('is-visible');
            }
            if(position > bottomOffset) {
              $('.to-top').addClass('is-static');
            } else {
              $('.to-top').removeClass('is-static');
            }
        });
        $(".to-top").click(function() {
            $('html, body').animate({
                scrollTop: $("body").offset().top
            }, 1000);
        });
        $('.acf-map').each(function(){
          // create map
          map = new_map( $(this) );
        });
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired

      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page


      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // About us page, note the change from about-us to about_us.
    'about_us': {
      init: function() {
        // JavaScript to be fired on the about us page
      }
    }
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
