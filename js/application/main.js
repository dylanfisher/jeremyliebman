// Primary Javascript file

$(function(){


  ///////////////////////////////////////////////////////
  //
  // pjax configuration
  //
  ///////////////////////////////////////////////////////


  // Initialize pjax on all anchor tags
  $(document).pjax('a', '#pjax-container', {
    // Increase the pjax timeout
    timeout: 10000
  });

  $(document).on('pjax:start', function(){
    console.log('pjax start');
    $('#pjax-container').addClass('pjax-transition');
  });

  $(document).on('pjax:end', function(){
    console.log('pjax end');
    $('#pjax-container').removeClass('pjax-transition');
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

  function searchTagEnabler(state){
    if(state == 'enable'){
      $('#select2-drop').addClass('active');
      $('#search-select .search-tag').removeAttr('disabled');
    } else {
      $('#select2-drop').removeClass('active');
      $('#search-select .search-tag').prop('disabled', true);
    }
  }


});
