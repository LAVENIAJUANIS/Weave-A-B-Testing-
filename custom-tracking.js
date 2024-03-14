function toggleCTADropdown() {
    var ctaDropdown = document.getElementById('cta-dropdown');
    ctaDropdown.style.display = document.getElementById('goal-cta').checked ? 'block' : 'none';
}

function toggleInquiryDropdown() {
    var inquiryDropdown = document.getElementById('inquiry-dropdown');
    inquiryDropdown.style.display = document.getElementById('goal-inquiry').checked ? 'block' : 'none';
}

function toggleTargetElements() {
    var titleCheckbox = document.getElementById('element-title');
    var titleVariationInput = document.getElementById('title-variation-input');
    var titleButtons = document.getElementById('title-buttons');
    var titleVariations = document.getElementById('title-variations');

    if (titleCheckbox.checked) {
        titleVariationInput.style.display = 'block';
        titleButtons.style.display = 'block';
        titleVariations.style.display = 'block';
    } else {
        titleVariationInput.style.display = 'none';
        titleButtons.style.display = 'none';
        titleVariations.style.display = 'none';
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
    inputField.style.display = checkbox.checked ? "block" : "none";
    var savedVariation = document.getElementById("saved_title_variation");
    savedVariation.style.display = "none";
    
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


function toggleDescriptionElements() {
    var descriptionCheckbox = document.getElementById('description_variation_checkbox');
    var descriptionInput = document.getElementById('description_variation_input');
    var descriptionSaveButton = document.getElementById('description_save_button');
    var savedDescriptionVariation = document.getElementById('saved_description_variation');

    var displayStyle = descriptionCheckbox.checked ? 'block' : 'none';

    descriptionInput.style.display = displayStyle;
    descriptionSaveButton.style.display = displayStyle;
    savedDescriptionVariation.style.display = displayStyle;
}


function toggleDescriptionInput() {
    var checkbox = document.getElementById("description_variation_checkbox");
    var inputField = document.getElementById("description_variation_input");
    var saveButton = document.getElementById("description_save_button");

    var displayStyle = checkbox.checked ? "block" : "none";

    inputField.style.display = displayStyle;
    saveButton.style.display = displayStyle;

    
    saveButton[displayStyle === 'block' ? 'addEventListener' : 'removeEventListener']("click", saveDescriptionVariation);

   
    if (!checkbox.checked) {
        inputField.value = ''; // Clear input field
        var savedVariations = document.getElementsByClassName("description-variation");
        Array.from(savedVariations).forEach(function(savedVariation) {
            savedVariation.style.display = 'none';
        });
    }
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

// IMAGE SECTION

// function toggleImageInput() {
//     var checkbox = document.getElementById("image_variation_checkbox");
//     var inputField = document.getElementById("image_variation_input");
//     inputField.style.display = checkbox.checked ? "block" : "none";
// }

// function uploadImage() {
//     var uploadButton = document.getElementById("image_upload_button");
//     uploadButton.click();
//     uploadButton.addEventListener('change', handleImageUpload);
// }

// function handleImageUpload(event) {
//     var imageFile = event.target.files[0];
//     if (imageFile) {
//         saveImageVariation(imageFile);
//     }
// }

// function saveImageVariation(imageFile) {
//     if (!imageFile) {
//         return;
//     }
    
//     var savedVariations = document.getElementById("saved_image_variations");
//     savedVariations.style.display = "block";

//     var img = document.createElement("img");
//     img.src = URL.createObjectURL(imageFile);
//     img.classList.add("image-variation");

//     var editButton = createButton("Edit", function() {
//         editImageVariation(img);
//     });
//     editButton.classList.add("edit-button");

//     var deleteButton = createButton("Delete", function() {
//         deleteImageVariation(img);
//     });
//     deleteButton.classList.add("delete-button");

//     appendToContainer(savedVariations, [img, editButton, deleteButton]);
// }

// function editImageVariation(imageElement) {

// }

// function deleteImageVariation(imageElement) {
//     var savedVariations = document.getElementById("saved_image_variations");
//     var editButton = imageElement.nextElementSibling;
//     var deleteButton = editButton.nextElementSibling;
//     imageElement.remove();
//     editButton.remove();
//     deleteButton.remove();
//     if (savedVariations.childElementCount === 0) {
//         savedVariations.style.display = "none";
//     }
// }


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

function toggleLayoutInput() {
    var layoutCheckbox = document.getElementById('layout_variation_checkbox');
    var layoutSelect = document.getElementById('layout_variation_select');
    layoutSelect.style.display = layoutCheckbox.checked ? 'block' : 'none';
}

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