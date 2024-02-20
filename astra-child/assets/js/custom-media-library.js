jQuery(document).ready(function($) {
  // Function to add CSS class based on custom field value
  function addCustomClassToMediaItems() {
    $('.attachment').each(function() {
      var $this = $(this);
      var postID = $this.data('id');

      // AJAX request to get the custom field value for the attachment
      var xhr = new XMLHttpRequest();
      xhr.open('POST', ajaxurl, true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
              var customFieldValue = response.data;
              if (customFieldValue === 'member') {
                $this.addClass('access-level__member');
              }
            }
          }
        }
      };

      xhr.send('action=get_custom_field_value&post_id=' + postID + '&field_name=access_level');
    });
  }

  // Call the function on page load and when new items are added to the list (through AJAX)
  addCustomClassToMediaItems();

  // Also, hook into AJAX event to add the class to new items
  $(document).ajaxSuccess(function(event, xhr, settings) {
    if (settings.data.includes('action=query-attachments')) {
      addCustomClassToMediaItems();
    }
  });
});