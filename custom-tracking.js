function toggleCTADropdown() {
    var ctaDropdown = document.getElementById('cta-dropdown');
    ctaDropdown.style.display = document.getElementById('goal-cta').checked ? 'block' : 'none';
}

function toggleInquiryDropdown() {
    var inquiryDropdown = document.getElementById('inquiry-dropdown');
    inquiryDropdown.style.display = document.getElementById('goal-inquiry').checked ? 'block' : 'none';
}

    function toggleTitleInput() {
        var titleCheckbox = document.getElementById('title_variation_checkbox');
        var titleInput = document.getElementById('title_variation_input');
        if (titleCheckbox.checked) {
            titleInput.style.display = 'block';
        } else {
            titleInput.style.display = 'none';
        }
    }

    function toggleDescriptionInput() {
        var descriptionCheckbox = document.getElementById('description_variation_checkbox');
        var descriptionInput = document.getElementById('description_variation_input');
        if (descriptionCheckbox.checked) {
            descriptionInput.style.display = 'block';
        } else {
            descriptionInput.style.display = 'none';
        }
    }



    function toggleImageInput() {
        console.log("toggleImageInput function called"); // Debug statement to check if the function is called
        var imageCheckbox = document.getElementById('image_variation_checkbox');
        var imageUploadSection = document.getElementById('image_upload_section'); // Target the parent div
        console.log("Checkbox state:", imageCheckbox.checked); // Debug statement to check the checkbox state
        if (imageCheckbox.checked) {
            console.log("Checkbox is checked"); // Debug statement for when the checkbox is checked
            imageUploadSection.style.display = 'block'; // Show the parent div
        } else {
            console.log("Checkbox is unchecked"); // Debug statement for when the checkbox is unchecked
            imageUploadSection.style.display = 'none'; // Hide the parent div
        }
    }
    
    function previewImage(event) {
        console.log("previewImage function called"); // Debug statement to check if the function is called
        var preview = document.getElementById("image_variation_preview");
        var file = event.target.files[0];
        console.log("Selected file:", file); // Debug statement to check the selected file
        var reader = new FileReader();
    
        reader.onload = function() {
            preview.src = reader.result;
            preview.style.display = "block";
        }
    
        if (file) {
            console.log("File selected"); // Debug statement for when a file is selected
            reader.readAsDataURL(file);
        }
    }

    jQuery(document).ready(function($) {
        $('#ab-testify-form').submit(function(e) {
            e.preventDefault();
    
            
            var fileInput = document.getElementById('image_variation_input');
            var imageFile = fileInput.files[0];
    
            if (imageFile) {
                // Read the image file as a data URL
                var reader = new FileReader();
                reader.onload = function(event) {
                    // Convert the image data to base64
                    var base64Image = event.target.result;
    
                    
                    var formData = new FormData();
                    formData.append('image_variation', base64Image);
    
                    // Serialize other form data
                    var otherFormData = $(this).serialize();
                    formData.append('other_data', otherFormData);
    
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        processData: false, 
                        contentType: false, 
                        data: formData,
                        success: function(response) {
                            // Handle success response
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                            console.error(error);
                        }
                    });
                };
                reader.readAsDataURL(imageFile); 
            } else {
                console.log('No image file selected.');
            }
        });
    });
    


    

    jQuery(document).ready(function($) {
        // Your custom code here
      
        $('select').not('.not-select2').select2({
            minimumResultsForSearch: -1,
            dropdownAutoWidth: true
        });
      
        // More custom code here
      });
    
      // Wait for the DOM to be fully loaded
    jQuery(document).ready(function($) {
        // Initialize Select2 on elements with the class 'js-example-basic-single'
        $('.js-example-basic-single').select2({
            minimumResultsForSearch: -1,
            dropdownAutoWidth: true
        });
    });



  // Wait for the DOM to be fully loaded
jQuery(document).ready(function($) {
    // Initialize Select2 on elements with the class 'js-example-basic-single'
    $('.js-example-basic-single').select2({
        minimumResultsForSearch: -1,
        dropdownAutoWidth: true,
        templateResult: formatResult, // Function to format results in the dropdown
        templateSelection: formatSelection // Function to format the selected option
    });

    // Function to format results in the dropdown
    function formatResult(result) {
        if (!result.id) {
            return result.text;
        }

        var $result = $(
            '<span><img src="' + result.element.dataset.thumbnailUrl + '" class="thumbnail small" />' + result.text + '</span>'
        );

        return $result;
    }

    // Function to format the selected option
    function formatSelection(selection) {
        if (!selection.id) {
            return selection.text;
        }

        var $selection = $(
            '<span>' + selection.text + '</span>'
        );

        return $selection;
    }

   
    var customCss = `
        /* Custom CSS to adjust the size of the thumbnail */
        .thumbnail.small {
            width: 50px; /* Adjust the width as needed */
            height: auto; /* Maintain aspect ratio */
            margin-right: 5px; /* Optional: Add some space between the thumbnail and the text */
        }
    `;
    
    
    $('head').append('<style>' + customCss + '</style>');
});



    
    
    
    
  

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.action-button').forEach(function(button) {
        button.addEventListener('click', function() {
            var action = this.getAttribute('data-action');
            var elementId = this.getAttribute('data-element-id');
            if (action && elementId) {
                window[action](elementId);
            }
        });
    });
});
