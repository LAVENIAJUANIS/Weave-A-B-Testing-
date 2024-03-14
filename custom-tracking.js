function toggleCTADropdown() {
    var ctaDropdown = document.getElementById('cta-dropdown');
    ctaDropdown.style.display = document.getElementById('goal-cta').checked ? 'block' : 'none';
}

function toggleInquiryDropdown() {
    var inquiryDropdown = document.getElementById('inquiry-dropdown');
    inquiryDropdown.style.display = document.getElementById('goal-inquiry').checked ? 'block' : 'none';
}

function toggleTargetElements() {
    var titleCheckbox = document.getElementById('title_variation_checkbox');
    var descriptionCheckbox = document.getElementById('description_variation_checkbox');
    
    var titleVariationInput = document.getElementById('title_variation_input');
    var titleButtons = document.getElementById('title-buttons');
    var titleVariations = document.getElementById('title-variations');
    
    var descriptionVariationInput = document.getElementById('description_variation_input');
    var descriptionSaveButton = document.getElementById('description_save_button');
    var savedDescriptionVariation = document.getElementById('saved_description_variation');

    // Toggle display for title variations
    if (titleCheckbox.checked) {
        titleVariationInput.style.display = 'block';
        titleButtons.style.display = 'block';
        titleVariations.style.display = 'block';
    } else {
        titleVariationInput.style.display = 'block'; // Title input remains visible even if unchecked
        titleButtons.style.display = 'none';
        titleVariations.style.display = 'block'; // Saved title variations remain visible
    }

    // Toggle display for description variations
    if (descriptionCheckbox.checked) {
        descriptionVariationInput.style.display = 'block';
        descriptionSaveButton.style.display = 'block';
        savedDescriptionVariation.style.display = 'block';
    } else {
        descriptionVariationInput.style.display = 'block'; // Description input remains visible even if unchecked
        descriptionSaveButton.style.display = 'none';
        savedDescriptionVariation.style.display = 'block'; // Saved description variations remain visible
    }
}


function updateTargetElement() {
    var titleCheckbox = document.getElementById('title_variation_checkbox');
    var titleVariationInput = document.getElementById('title_variation_input');

    titleCheckbox.disabled = false;

    titleVariationInput.style.display = titleCheckbox.checked ? 'block' : 'none';
}



function toggleTitleInput() {
    var checkbox = document.getElementById("title_variation_checkbox");
    var inputField = document.getElementById("title_variation_input");
    var savedVariation = document.getElementById("saved_title_variation");
    
    inputField.style.display = checkbox.checked ? "block" : "none";
    savedVariation.style.display = checkbox.checked ? "block" : "none";
}


// for displaying saved title
function saveTitleVariation() {
    var inputField = document.getElementById("title_variation_text");
    var titleVariation = inputField.value.trim(); // Trim to remove leading/trailing spaces
    if (titleVariation !== '') {
        var savedVariation = document.getElementById("saved_title_variation");

        var variationDiv = document.createElement("div");
        variationDiv.textContent = titleVariation;

        var editButton = document.createElement("span");
        editButton.textContent = "Edit";
        editButton.style.cursor = "pointer";
        editButton.style.color = "blue";
        editButton.style.textDecoration = "underline";
        editButton.style.marginRight = "5px";
        editButton.onclick = function() {
            editTitleVariation(this);
        };

        var deleteButton = document.createElement("span");
        deleteButton.textContent = "Delete";
        deleteButton.style.cursor = "pointer";
        deleteButton.style.color = "red";
        deleteButton.style.textDecoration = "underline";
        deleteButton.onclick = function() {
            deleteTitleVariation(this);
        };

        var buttonContainer = document.createElement("div");
        buttonContainer.appendChild(editButton);
        buttonContainer.appendChild(deleteButton);

        var entryContainer = document.createElement("div");
        entryContainer.classList.add("title-variation"); // Add a class to mark it as a title variation
        entryContainer.appendChild(variationDiv);
        entryContainer.appendChild(buttonContainer);

        savedVariation.appendChild(entryContainer);
        savedVariation.style.display = "block"; // Ensure the saved variation container is visible
    }
    inputField.value = ''; // Clear the input field after saving
}


function editTitleVariation(button) {
    var savedVariation = button.closest('.title-variation'); // Get the parent div containing the saved variation
    var titleSpan = savedVariation.querySelector('div'); // Get the div containing the title
    var title = titleSpan.textContent.trim(); // Get the text content of the title and trim leading/trailing spaces

    var inputField = document.getElementById("title_variation_text");
    inputField.value = title; // Set the input field value to the extracted title
    inputField.style.display = "inline-block"; // Ensure the input field is displayed inline-block
    document.getElementById("save_button").style.display = "inline-block"; // Ensure the save button is displayed inline-block
    savedVariation.style.display = "none"; // Hide the saved variation container
    
    // Set focus to the input field
    inputField.focus();
}


function deleteTitleVariation(button) {
    var savedVariation = button.parentNode.parentNode; // Get the parent div containing the saved variation
    savedVariation.remove(); // Remove the entire saved variation container
}


function toggleDescriptionInput() {
    var checkbox = document.getElementById("description_variation_checkbox");
    var inputField = document.getElementById("description_variation_input");
    var savedVariation = document.getElementById("saved_description_variation");
    var saveButton = document.getElementById("description_save_button");
    
    var displayStyle = checkbox.checked ? "block" : "none";
    
    inputField.style.display = displayStyle;
    savedVariation.style.display = displayStyle;
    saveButton.style.display = displayStyle;
}


// Save description variation

function saveDescriptionVariation() {
    var inputField = document.getElementById("description_variation_text");
    var descriptionVariation = inputField.value.trim(); // Trim to remove leading/trailing spaces
    if (descriptionVariation !== '') {
        var savedVariation = document.getElementById("saved_description_variation");

        var variationDiv = document.createElement("div");
        variationDiv.textContent = descriptionVariation;

        var editButton = document.createElement("span");
        editButton.textContent = "Edit";
        editButton.style.cursor = "pointer";
        editButton.style.color = "blue";
        editButton.style.textDecoration = "underline";
        editButton.style.marginRight = "5px";
        editButton.onclick = function() {
            editDescriptionVariation(this);
        };

        var deleteButton = document.createElement("span");
        deleteButton.textContent = "Delete";
        deleteButton.style.cursor = "pointer";
        deleteButton.style.color = "red";
        deleteButton.style.textDecoration = "underline";
        deleteButton.onclick = function() {
            deleteDescriptionVariation(this);
        };

        var buttonContainer = document.createElement("div");
        buttonContainer.appendChild(editButton);
        buttonContainer.appendChild(deleteButton);

        var entryContainer = document.createElement("div");
        entryContainer.classList.add("description-variation"); 
        entryContainer.appendChild(variationDiv);
        entryContainer.appendChild(buttonContainer);

        savedVariation.appendChild(entryContainer);
        savedVariation.style.display = "block"; 
    }
    inputField.value = ''; 
}

function deleteDescriptionVariation(button) {
    var savedVariation = button.parentNode.parentNode; 
    savedVariation.remove();
}



function editDescriptionVariation(button) {
    var savedVariation = button.closest('.description-variation'); 
    var descriptionDiv = savedVariation.querySelector('div'); 
    var description = descriptionDiv.textContent.trim(); 

    var inputField = document.getElementById("description_variation_text");
    inputField.value = description; 
    inputField.style.display = "inline-block"; 
    document.getElementById("description_save_button").style.display = "inline-block"; 
    savedVariation.style.display = "none"; 
    
    
    inputField.focus();
}



function createButton(text, clickHandler) {
    var button = document.createElement("button");
    button.textContent = text;
    button.addEventListener("click", clickHandler);
    return button;
}

function appendToContainer(container, elements) {
    elements.forEach(function(element) {
        container.appendChild(element);
    });
}

// Layout
function toggleLayoutInput() {
    var layoutCheckbox = document.getElementById('layout_variation_checkbox');
    var layoutSelect = document.getElementById('layout_variation_select');
    layoutSelect.style.display = layoutCheckbox.checked ? 'block' : 'none';
}

// Image
function toggleImageInput() {
    var imageCheckbox = document.getElementById('image_variation_checkbox');
    var imageInput = document.getElementById('image_variation_input');
    imageInput.style.display = imageCheckbox.checked ? 'block' : 'none';
}

function uploadImage() {
    var uploadButton = document.getElementById("image_upload_button");
    uploadButton.click();
    uploadButton.addEventListener('change', handleImageUpload);
}

function handleImageUpload(event) {
    var imageFiles = event.target.files;
    if (imageFiles && imageFiles.length > 0) {
        for (var i = 0; i < imageFiles.length; i++) {
            displayImagePreview(imageFiles[i]);
        }
    }
}

function displayImagePreview(imageFile) {
    var imagePreview = document.getElementById("image_preview");
   
    var imageContainer = document.createElement("div");
    imageContainer.classList.add("image-container");

    // Create image element
    var img = document.createElement("img");
    img.src = URL.createObjectURL(imageFile);
    img.classList.add("uploaded-image");
   
    img.style.width = "200px";
    img.style.height = "auto"; 
    
    imageContainer.appendChild(img);

    
    var br1 = document.createElement("br");
    var br2 = document.createElement("br");
    imageContainer.appendChild(br1);

    
    var editButton = document.createElement("button");
    editButton.textContent = "Edit";
    editButton.addEventListener("click", function() {
        editImage(imageFile);
    });
    imageContainer.appendChild(editButton);

    var deleteButton = document.createElement("button");
    deleteButton.textContent = "Delete";
    deleteButton.addEventListener("click", function() {
        deleteImage(imageContainer);
    });
    imageContainer.appendChild(deleteButton);

   
    imageContainer.appendChild(br2);

   
    imagePreview.appendChild(imageContainer);

    
    imagePreview.style.display = "block";
}

function deleteImage(imageContainer) {
   
    imageContainer.remove();
}

function editImage(imageFile) {
  
}

function saveTestDataToJSON() {
    // Gather data from form fields
    var testData = {
        test_name: document.getElementById('test_name').value,
        conversion_goals: Array.from(document.querySelectorAll('input[name="conversion_goals[]"]:checked')).map(function(goal) {
            return goal.value;
        }),
        content_id: document.getElementById('content-select').value,
        target_elements: {
            title_variation: document.getElementById('title_variation_checkbox').checked,
            description_variation: document.getElementById('description_variation_checkbox').checked,
            image_variation: document.getElementById('image_variation_checkbox').checked,
            layout_variation: document.getElementById('layout_variation_checkbox').checked
            // Add more target elements as needed
        },
        // Add more fields as needed
    };

    // Convert data to JSON format
    var jsonData = JSON.stringify(testData, null, 2); // Use null and 2 for pretty formatting

    // Send JSON data to server for storage
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_test_data.php'); // Replace 'save_test_data.php' with the actual endpoint
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Data saved successfully');
        } else {
            console.error('Failed to save data');
        }
    };
    xhr.send(jsonData);
}



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