// Primary Javascript file

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
    console.log('pjax start');
    $('#pjax-container').addClass('pjax-transition');
  });

  $(document).on('pjax:end', function(){
    console.log('pjax end');
    $('#pjax-container').removeClass('pjax-transition');

    // Replace the select2 chosen item in search input with proper pjax page title
    var pjaxTitle = $('#pjax-page-title').html();
    $('#select2-chosen-1').html(pjaxTitle + '<span class="search-caret"></span>');
  });

  $(document).on('pjax:timeout, pjax:error', function(event){
    console.log('pjax error');
    console.log(event);
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

  ///////////////////////////////////////////////////////
  //
  // Image sets
  //
  ///////////////////////////////////////////////////////

  calculateColumnsInRow('.image-set');

  $('.image-set-info-wrapper').click(function(){

  });


  // Calculate how many columns are in a row, and add a new row class to the first column in each new row. 
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

  ///////////////////////////////////////////////////////
  //
  // Resize events
  //
  ///////////////////////////////////////////////////////

  $(window).resize(function(){
    calculateColumnsInRow('.image-set');
  });

});
