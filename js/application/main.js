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

  // Initialize pjax on all anchor tags
  $(document).pjax('a', '#pjax-container');

  // Increase the pjax timeout
  $.pjax.defaults.timeout = 5000;

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
    $('#wp-search-input').val(selection);

    // Listen for first event and do our pjax. Without .one() this fires exponentially.
    $(document).one('submit', '#wp-search-form', function(e){
      $.pjax.submit(e, '#pjax-container');
    });

    $('#wp-search-form').submit();
  });

  $(document)
  .on('keyup', '#s2id_autogen1_search', function(){
    // Show additional search tags when text has been entered
    if($(this).val().length){
      searchTagEnabler('enable');
    } else {
      searchTagEnabler('disable');
    }
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
  });

  // Add blinking caret to the end of the selected term
  $('#select2-chosen-1').append('<span class="search-caret"></span>');

  function searchTagEnabler(state){
    if(state == 'enable'){
      $('#select2-drop').addClass('active');
      $('#search-select .search-tag').removeAttr('disabled');
    } else {
      $('#select2-drop').removeClass('active');
      $('#search-select .search-tag').prop('disabled', true);
    }
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
    destroyImageViewer();
  });

  ///////////////////////////////////////////////////////
  //
  // Resize events
  //
  ///////////////////////////////////////////////////////

  $(window).resize(function(){
    calculateColumnsInRow('.image-set');
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

    var imageSetOffset = thisImageSet.offset().left;
    var imageWidth = thisImageSet.find('img').width();
    var indicatorPos = imageSetOffset + (imageWidth / 2);

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

    $('.image-viewer-open-indicator').css({left: indicatorPos});
  });

  function createImageViewer(el, aboveOrBelow, images){
    var imageViewer = '<div class="image-viewer"><div class="image-viewer-slide-count"></div><div class="image-viewer-close"></div><div class="image-viewer-open-indicator"></div><div class="image-viewer-slide-container"></div></div>';
    var offset = 100;
    var ratio = 0.9;
    var imageViewerHeight = ($(window).height() - offset) * ratio;

    if(aboveOrBelow == 'above'){
      el.before(imageViewer);
    } else {
      el.after(imageViewer);
    }

    $(images).each(function(){
      $('.image-viewer-slide-container').append('<div><img src="' + $(this).attr("data-image-url") + '"></div>');
    });

    $('.image-viewer').addClass('open').css({height: imageViewerHeight});
    var positionTop = $('.image-viewer').position().top - offset;
    $('html, body').animate({scrollTop: positionTop}, 800, 'swing');

    // Initiate slick
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
  $('.image-set').removeClass('image-set-open');
  $('.image-viewer').remove();
}
