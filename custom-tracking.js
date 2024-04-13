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

function previewImage(event) {
    var input = event.target;
    var imagePreview = document.getElementById('image_preview');

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        imagePreview.style.display = 'none';
    }
}

jQuery(document).ready(function($) {
    $('#ab-testify-form').submit(function(e) {
        e.preventDefault();

        var fileInput = document.getElementById('image_variation_upload');
        var imageFile = fileInput.files[0];

        if (imageFile) {
            var reader = new FileReader();
            reader.onload = function(event) {
                var base64Image = event.target.result;

                var formData = new FormData();
                formData.append('image_variation', base64Image);
                var otherFormData = $(this).serialize();
                formData.append('other_data', otherFormData);

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
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

// Initialize Select2 on elements with the class 'js-example-basic-single'
jQuery(document).ready(function($) {
    $('.js-example-basic-single').select2({
        minimumResultsForSearch: -1,
        dropdownAutoWidth: true,
        templateResult: formatResult,
        templateSelection: formatSelection
    });

    function formatResult(result) {
        if (!result.id) {
            return result.text;
        }

        var $result = $(
            '<span><img src="' + result.element.dataset.thumbnailUrl + '" class="thumbnail small" />' + result.text + '</span>'
        );

        return $result;
    }

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


// layout 

$(document).ready(function() {
    // Initialize Select2
    $('.js-example-basic-single').select2();

    // Update layout preview
    $('#layout-select').on('change', function() {
        var selectedLayout = $(this).val();
        $('#layout-preview').html(''); // Clear previous layout shape

        // Apply the appropriate CSS class based on the selected layout variation
        switch (selectedLayout) {
            case 'default':
                $('#layout-preview').append('<div class="layout-shape"></div>');
                break;
            case 'alternative':
                $('#layout-preview').append('<div class="layout-rectangle"></div>');
                break;
            case 'custom':
                $('#layout-preview').append('<div class="layout-circle"></div>');
                break;
            // Add cases for more layout variations as needed
        }
    });
});

// Layout

function changeLayout(layoutVariation) {
    var mainContainer = document.getElementById('main-container');

    // Remove existing layout classes
    mainContainer.classList.remove('single-column-layout', 'two-column-layout', 'three-column-layout');

    // Add classes based on the selected variation
    if (layoutVariation === 'single-column') {
        mainContainer.classList.add('single-column-layout');
    } else if (layoutVariation === 'two-column') {
        mainContainer.classList.add('two-column-layout');
    } else if (layoutVariation === 'three-column') {
        mainContainer.classList.add('three-column-layout');
    }
}





// Event listener to call corresponding functions based on button click
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

// Additional event listener for image preview
document.getElementById('image_variation_upload').addEventListener('change', previewImage);
