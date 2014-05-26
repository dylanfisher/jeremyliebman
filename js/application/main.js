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
  });

  $(document).on('pjax:end', function(){
    // console.log('pjax end');
    $('#pjax-container').removeClass('pjax-transition');

    // Replace the select2 chosen item in search input with proper pjax page title
    var pjaxTitle = $('#pjax-page-title').html();
    $('#select2-chosen-1').html(pjaxTitle + '<span class="search-caret"></span>');
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
    var loadingDelay = 2000;
    loaderTimeoutID = window.setTimeout(showLoader, loadingDelay);
  });

  $(document).on('pjax:complete', function(){
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
    if(e.object.css == 'image-set-search-tag'){
      // Image sets
      $('#wp-search-form').submit();
    } else {
      // Single images
      $('#wp-search-form-single-images').submit();
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
    if($('.image-set-open').length){
      $('html, body').animate({scrollTop: $('.image-set-open').offset().top - $(window).height() * 0.1});
    }
    destroyImageViewer();
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
  calculateColumnsInRow('.image-set');

  // Append an image viewer when clicking on an image set and set this set to active
  $('.image-set-info-wrapper').click(function(){
    // Place the image viewer before the next image set on the following row.
    var thisImageSet = $(this).closest('.image-set');
    var nextImageSet = thisImageSet.nextAll('.new-image-set-row').first();
    var imagesInSet = $(this).closest('.image-set').children();

    var images = imagesInSet.clone().toArray();

    if(thisImageSet.hasClass('image-set-open')){
      // Do nothing if this image set is already open
    } else {
      destroyImageViewer();
      thisImageSet.addClass('image-set-open');

      // If there is no next image set on the next row, place the image viewer after the last image set in the current row.
      if(nextImageSet.length === 0){
        nextImageSet = thisImageSet.nextAll('.image-set').last();
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

  // Calculate how many columns are in a row, and add a new row class to the first column in each new row. 
  calculateColumnsInRow('.single-image');

  // Single image viewers
  $('.single-image').click(function(){
    var thisImageSet = $(this);
    var nextImageSet = $(this).nextAll('.new-image-set-row').first();
    var image = [$(this).find('img')];

    if($(this).hasClass('image-set-open')){
      // Do nothing if this image set is already open
    } else {
      destroyImageViewer();
      $(this).addClass('image-set-open');

      // If there is no next image set on the next row, place the image viewer after the last image set in the current row.
      if(nextImageSet.length === 0){
        nextImageSet = thisImageSet.nextAll('.single-image').last();
        // If there are no other image set after this one, place the image viewer after this image set.
        if(nextImageSet.length === 0){
          nextImageSet = thisImageSet;
        }
        createImageViewer(nextImageSet, 'below', image);
      } else {
        createImageViewer(nextImageSet, 'above', image);
      }
    }

    var imageSetOffset = thisImageSet.offset().left;
    var imageViewerOffset = $('.image-viewer').offset().left;
    var imageWidth = thisImageSet.find('img').width();
    var indicatorPos = imageSetOffset - imageViewerOffset + (imageWidth / 2);

    $('.image-viewer-open-indicator').css({left: indicatorPos});
  });

  function createImageViewer(el, aboveOrBelow, images){
    var imageViewer = '<div class="image-viewer"><div class="image-viewer-slide-count"></div><div class="image-viewer-close"></div><div class="image-viewer-open-indicator"></div><div class="image-viewer-slide-container"></div></div>';
    var ratio = 0.9;
    var ratioDiff = 1 - ratio;
    var offset = $(window).height() * ratioDiff;
    var captionHeight = 36;
    var imageViewerHeight = ( ($(window).height() - offset) * ratio ) + captionHeight;

    if(aboveOrBelow == 'above'){
      el.before(imageViewer);
    } else {
      el.after(imageViewer);
    }

    $(images).each(function(){
      var caption = $(this).attr("data-image-caption").length ? '<div class="caption">' + $(this).attr("data-image-caption") + '</div>' : '';
      var captionParentClass = $(this).attr("data-image-caption").length ? ' class="has-caption"' : '';
      var image = '<img src="' + $(this).attr("data-image-url") + '">';
      $('.image-viewer-slide-container').append('<div' + captionParentClass + '>' + image + caption + '</div>');
    });

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
      },
      onAfterChange: function(slick){
        current = slick.currentSlide + 1;
        updateSlideCount(current, count);
      }
    });

    function updateSlideCount(current, count){
      $('.image-viewer-slide-count').html(current + '/' + count);
    }
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

function destroyImageViewer(){
  $('.image-set, .single-image').removeClass('image-set-open');
  $('.image-viewer').remove();
  calculateColumnsInRow('.image-set');
  calculateColumnsInRow('.single-image');
}
