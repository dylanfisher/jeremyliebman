///////////////////////////////////////////////////////
//
// Primary Javascript file
//
///////////////////////////////////////////////////////

///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
//
// Initial document ready events that don't need to
// be re-initialized after pjax or popstate event.
//
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////

$(function(){

  ///////////////////////////////////////////////////////
  //
  // pjax configuration
  //
  ///////////////////////////////////////////////////////

  // Set pjax defaults
  if ($.support.pjax){
    // Increase the pjax timeout
    $.pjax.defaults.timeout = 5000;
    // Don't scroll to top
    // $.pjax.defaults.scrollTo = false;
  }

  // Initialize pjax on all anchor tags
  $(document).pjax('a', '#pjax-container');

  $(document).on('pjax:start', function(){
    // console.log('pjax start');
    $('#pjax-container').addClass('pjax-transition');
    if(infoWrapperOpen === true){
      toggleInfoBox();
      $('html, body').animate({scrollTop: 0}, 400, 'swing');
    }
  });

  $(document).on('pjax:end', function(){
    // console.log('pjax end');
    $('#pjax-container').removeClass('pjax-transition');

    // Replace the select2 chosen item in search input with proper pjax page title
    var pjaxTitle = $('#pjax-page-title').html();
    $('#select2-chosen-1').html(pjaxTitle + '<span class="search-caret"></span>');

    destroyImageViewer(); 
  });

  $(document).on('pjax:timeout, pjax:error', function(event){
    // console.log('pjax error');
  });

  // pjax loader
  var loaderTimeoutID;
  var showLoader = function(){
    $('#pjax-container').prepend('<div class="loader">Loading...</div>');
    $('.loader').fadeIn();
  };

  $(document).on('pjax:send', function(){
    // Show loader after interval, if the page is really slow.
    var loadingDelay = 1000;
    loaderTimeoutID = window.setTimeout(showLoader, loadingDelay);
  });

  $(document).on('pjax:complete', function(){
    picturefill();
    // Remove loader and reset timeout.
    window.clearTimeout(loaderTimeoutID);
    $('.loader').fadeOut(function(){
      $('.loader').remove();
    });
  });

  ///////////////////////////////////////////////////////
  //
  // Search and select2
  //
  ///////////////////////////////////////////////////////

  $('.search-nav').fadeIn(100);

  $('#search-select').select2({
    // Matcher is used to determine how search results are displayed.
    // Right now we match on first letters of each string.
    matcher: function(term, text){
      return text.toUpperCase().indexOf(term.toUpperCase()) ===  0;
    }
  })
  .on('select2-opening', function(e){
    searchTagEnabler('disable');
  })
  .on('select2-selecting', function(e){
    // Event triggers when clicking or pressing enter on an item.
    // This is the final event that should trigger search.
    var selection = e.val;

    $('#wp-search-input, #wp-search-input-single-images').val(selection);

    // Listen for first event and do our pjax. Without .one() this fires exponentially.
    $(document).one('submit', '#wp-search-form, #wp-search-form-single-images', function(e){
      $.pjax.submit(e, '#pjax-container');
    });

    // Decide which form to submit. One for image sets, the other for single images.
    if(e.object.css.indexOf('recent-work-option') !== -1){
      // Recent work links to home page
      var url = $('html').attr('data-home-url');
      $.pjax({url: url, container: '#pjax-container'});
    } else if(e.object.css.indexOf('image-set-search-tag') !== -1){
      // Image sets
      $('#wp-search-form').submit();
    } else {
      // Single images
      // $('#wp-search-form-single-images').submit();
      $('#wp-search-form').submit(); // Using the same form for both single and sets
    }

  });

  $(document)
  .on('keyup', '#s2id_autogen1_search', function(){
    // Show additional search tags when text has been entered
    if($(this).val().length){
      searchTagEnabler('enable');
    } else {
      searchTagEnabler('disable');
    }
    searchTagDoubleChecker();
  })
  .on('keydown', '#s2id_autogen1_search', function(e){
    // If the delete key is pressed on keydown, also remove class so that
    // all tags aren't shown until letting go of keyup.
    if (e.which == 8){
      if($(this).val().length == 1){
        searchTagEnabler('disable');
      }
    } else if(e.which <= 90 && e.which >= 48) {
      // If any alphanumeric key is pressed, enable the search tags.
      // Don't enable if a modifer key is pressed.
      searchTagEnabler('enable');
    }
    searchTagDoubleChecker();
  });

  // Add blinking caret to the end of the selected term
  $('#select2-chosen-1').append('<span class="search-caret"></span>');

  function searchTagEnabler(state){
    if(state == 'enable'){
      $('#select2-drop').addClass('active');
      $('#select2-drop .search-tag').removeClass('visuallyhidden');
      $('#search-select .search-tag').removeAttr('disabled');
    } else {
      $('#select2-drop').removeClass('active');
      $('#select2-drop .search-tag').addClass('visuallyhidden');
      $('#search-select .search-tag').prop('disabled', true);
    }
  }

  function searchTagDoubleChecker(){
    // Double check the value and see if search tags should be visible
    setTimeout(function(){
      if($('#s2id_autogen1_search').val().length === 0){
        searchTagEnabler('disable');
      }
    }, 10);
  }

  ///////////////////////////////////////////////////////
  //
  // Info wrapper toggle
  //
  ///////////////////////////////////////////////////////

  var infoWrapper = $('#info-wrapper');
  var infoWrapperHeight = infoWrapper.outerHeight(true);
  var infoWrapperOpen = false;

  $('#info-button').click(function(){
    toggleInfoBox();
  });

  $('#info-wrapper-close').click(function(){
    toggleInfoBox();
  });

  function toggleInfoBox(){
    if(infoWrapperOpen === false){
      infoWrapper.css({marginTop: -infoWrapperHeight});
      infoWrapper.show();
      infoWrapper.transition({marginTop: 0});
      infoWrapperOpen = true;
    } else {
      infoWrapper.transition({marginTop: -infoWrapperHeight}, function(){
        infoWrapper.hide();
        infoWrapperOpen = false;
      });
    }
  }

  // Close image viewer when pressing the X close button
  $(document).on('click', '.image-viewer-close', function(){
    destroyImageViewer(true);
  });

  $(document).on('click', '.slick-custom-prev', function(){
    var viewer = $('.image-viewer .slick-slider');
    var count = viewer.find('.slick-slide').length;
    if(viewer.length && count > 1){
      $(this).closest('.slick-slider').slickPrev();
    }
  });

  $(document).on('click', '.slick-custom-next', function(){
    var viewer = $('.image-viewer .slick-slider');
    var count = viewer.find('.slick-slide').length;
    if(viewer.length && count > 1){
      $(this).closest('.slick-slider').slickNext();
    }
  });

  $(document).on('mouseenter', '.slick-custom-prev', function(){
    $(this).closest('.slick-slider').find('.slick-prev').addClass('active');
  });

  $(document).on('mouseenter', '.slick-custom-next', function(){
    $(this).closest('.slick-slider').find('.slick-next').addClass('active');
  });

  $(document).on('mouseleave', '.slick-custom-button', function(){
    $(this).closest('.slick-slider').find('.slick-next, .slick-prev').removeClass('active');
  });

  ///////////////////////////////////////////////////////
  //
  // Resize events
  //
  ///////////////////////////////////////////////////////

  $(window).resize(function(){
    calculateColumnsInRow('.image-set');
    calculateColumnsInRow('.single-image');
  });

});


///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
//
// Events that need re-initialization after pjax and
// popstate events.
//
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////

$(document).on('page:load ready pjax:end', function(){

  ///////////////////////////////////////////////////////
  //
  // Image sets
  //
  ///////////////////////////////////////////////////////

  // Calculate how many columns are in a row, and add a new row class to the first column in each new row.
  calculateColumnsInRow('.image-result');

  // Append an image viewer when clicking on an image set and set this set to active
  $('.image-set-placeholder, .image-set-info-wrapper').click(function(){
    // Place the image viewer before the next image set on the following row.
    var thisImageSet = $(this).closest('.image-result');
    var nextImageSet = thisImageSet.nextAll('.new-image-set-row').first();
    var imagesInSet = thisImageSet.children();

    var images = imagesInSet.clone().toArray();

    thisImageSet.find('.image-set-placeholder, .image-set-info-wrapper').removeClass('activate-slide');

    if(thisImageSet.hasClass('image-set-open')){
      // Go to active slide if a placeholder is clicked
      $(this).addClass('activate-slide');
      $('.image-viewer-slide-container').slickGoTo(slideActivator());
    } else {
      destroyImageViewer();
      thisImageSet.addClass('image-set-open');

      // If a placehold image is clicked, give the image a class to be used to determine which slide to go to
      $(this).addClass('activate-slide');

      // If there is no next image set on the next row, place the image viewer after the last image set in the current row.
      if(nextImageSet.length === 0){
        nextImageSet = thisImageSet.nextAll('.image-result').last();
        // If there are no other image set after this one, place the image viewer after this image set.
        if(nextImageSet.length === 0){
          nextImageSet = thisImageSet;
        }
        createImageViewer(nextImageSet, 'below', images);
      } else {
        createImageViewer(nextImageSet, 'above', images);
      }
    }

    var imageSetOffset = thisImageSet.offset().left;
    var imageViewerOffset = $('.image-viewer').offset().left;
    var imageWidth = thisImageSet.find('img').width();
    var indicatorPos = imageSetOffset - imageViewerOffset + (imageWidth / 2);

    $('.image-viewer-open-indicator').css({left: indicatorPos});
  });

});

///////////////////////////////////////////////////////
//
// Keyboard bindings
//
///////////////////////////////////////////////////////

// Image viewer slide left
Mousetrap.bind('left', function(){
  var viewer = $('.image-viewer .slick-slider');
  var count = viewer.find('.slick-slide').length;
  if(viewer.length && count > 1){
    viewer.slickPrev();
  }
});

// Image viewer slide right
Mousetrap.bind('right', function(){
  var viewer = $('.image-viewer .slick-slider');
  var count = viewer.find('.slick-slide').length;
  if(viewer.length && count > 1){
    viewer.slickNext();
  }
});

// Destroy image viewer when escape key pressed
Mousetrap.bind('escape', function(){
  var viewer = $('.image-viewer');
  if(viewer.length){
    destroyImageViewer(true);
  }
});

///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
//
// Global functions
//
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////

function calculateColumnsInRow(elString) {
  // Remove column class first, in case this is on resize and columns have shifted rows.
  $(elString).removeClass('new-image-set-row');

  var columnsInRow = 0;

  $(elString).each(function() {
    if($(this).prev().attr('class') === undefined){
      return;
    }

    // If the columns's previous class name matches the current column:
    if($(this).prev().attr('class').indexOf(elString.replace(/\./g,'')) != -1) {
      // If the position of the current column is not equal to the previous column:
      if($(this).position().top != $(this).prev().position().top){
        // Then this is a new row.
        $(this).addClass('new-image-set-row');
      }
      columnsInRow++;
    } else {
      // Always add class for first column in row.
      $(this).addClass('new-image-set-row');
      columnsInRow++;
    }
  });
}

function createImageViewer(el, aboveOrBelow, images){
  var imageViewer = '<div class="image-viewer"><div class="image-viewer-slide-count"></div><div class="image-viewer-close"></div><div class="image-viewer-open-indicator"></div><div class="image-viewer-slide-container"></div></div>';
  var ratio = 0.9;
  var ratioDiff = 1 - ratio;
  var offset = $(window).height() * ratioDiff;
  var captionHeight = 36;
  var imageViewerHeight = ( ($(window).height() - offset) * ratio ) + captionHeight;
  if($(window).width() < 760){
    imageViewerHeight = imageViewerHeight + 40;
  }

  if(aboveOrBelow == 'above'){
    el.before(imageViewer);
  } else {
    el.after(imageViewer);
  }

  $(images).each(function(){
    var caption = $(this).attr("data-image-caption").length ? '<div class="caption">' + $(this).attr("data-image-caption") + '</div>' : '';
    var captionParentClass = $(this).attr("data-image-caption").length ? ' class="has-caption"' : '';
    var image = $(this).attr("data-image-url");
    var image2x = $(this).attr("data-image-url-2x");
    var imageMobile = $(this).attr("data-image-url-mobile");
    var imageMobile2x = $(this).attr("data-image-url-mobile-2x");
    // $('.image-viewer-slide-container').append('<div' + captionParentClass + '>' + image + caption + '</div>');
    $('.image-viewer-slide-container').append(
      '<div' + captionParentClass + '>' +
        '<picture style="display: none;">' +
          '<!--[if IE 9]><video style="display: none;"><![endif]-->' +
          '<source srcset="' + imageMobile + ', ' + imageMobile2x + ' 2x" media="(max-width: 800px)">' +
          '<source srcset="' + image + ', ' + image2x + ' 2x">' +
          '<!--[if IE 9]></video><![endif]-->' +
          '<img srcset="' + image + ', ' + image2x + ' 2x">' +
        '</picture>' +
        caption +
      '</div>'
      );
  });

  // Re-initialize picturefill after appending picture element.
  $('.image-viewer-slide-container picture').show();
  picturefill();

  $('.image-viewer').addClass('open').css({height: imageViewerHeight});
  var positionTop = $('.image-viewer').offset().top - offset;
  $('html, body').animate({scrollTop: positionTop}, 400, 'swing');

  // Initiate slick carousel
  var current = 1;
  var count = 0;
  $('.image-viewer-slide-container').slick({
    onInit: function(slick){
      count = slick.slideCount;
      current = slick.currentSlide + 1;
      updateSlideCount(current, count);
      var customButtons = '<div class="slick-custom-prev slick-custom-button"></div><div class="slick-custom-next slick-custom-button"></div>';
      $('.image-viewer-slide-container').append(customButtons);
      $('.image-viewer-slide-container').imagesLoaded( function() {
        $('.slick-slider').addClass('slick-loaded');
      });
    },
    onAfterChange: function(slick){
      current = slick.currentSlide + 1;
      updateSlideCount(current, count);
    }
  });

  $('.image-viewer-slide-container').slickGoTo(slideActivator());

  function updateSlideCount(current, count){
    if(count == 1){
      $('.image-viewer-slide-count').hide();
    } else {
      $('.image-viewer-slide-count').html(current + '/' + count);
    }
  }
}

function slideActivator(){
  // Determine if a placeholder image was clicked, and go to that slide if it was.
  var el = $('.image-set-open');
  if(el.find('.activate-slide').length){
    activator = el.find('.activate-slide').index();
    totalSlides = el.find('.image-set-placeholder').length;
    activeSlide = totalSlides - activator;

    return activeSlide;
  } else {
    return 0;
  }
}

function destroyImageViewer(scroll){
  if(scroll === true){
    $('html, body').animate({scrollTop: $('.image-set-open').offset().top - $(window).height() * 0.1});
  }
  $('.image-set, .single-image').removeClass('image-set-open');
  $('.image-set-placeholder').removeClass('activate-slide');
  $('.image-viewer').remove();
  calculateColumnsInRow('.image-result');
}
