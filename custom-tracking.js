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
        console.log("Function called"); // Check if the function is called
        var imageCheckbox = document.getElementById('image_variation_checkbox');
        var imageInput = document.getElementById('image_variation_input');
        console.log("Checkbox state:", imageCheckbox.checked); // Check the checkbox state
        if (imageCheckbox.checked) {
            console.log("Checkbox is checked"); // Check if the checkbox is checked
            imageInput.style.display = 'block';
        } else {
            console.log("Checkbox is unchecked"); // Check if the checkbox is unchecked
            imageInput.style.display = 'none';
        }
    }
    
   

// function updateTargetElement() {
//     var titleCheckbox = document.getElementById('title_variation_checkbox');
//     var titleVariationInput = document.getElementById('title_variation_input');
//     titleVariationInput.style.display = titleCheckbox.checked ? 'block' : 'none';
// }

// function toggleTitleInput() {
//     var checkbox = document.getElementById("title_variation_checkbox");
//     var inputField = document.getElementById("title_variation_input");
//     var savedVariation = document.getElementById("saved_title_variation");
    
//     inputField.style.display = checkbox.checked ? "block" : "none";
//     savedVariation.style.display = checkbox.checked ? "block" : "none";
// }

// function toggleDescriptionInput() {
//     var checkbox = document.getElementById("description_variation_checkbox");
//     var inputField = document.getElementById("description_variation_input");
//     var savedVariation = document.getElementById("saved_description_variation");
//     var saveButton = document.getElementById("description_save_button");
    
//     var displayStyle = checkbox.checked ? "block" : "none";
    
//     inputField.style.display = displayStyle;
//     savedVariation.style.display = displayStyle;
//     saveButton.style.display = displayStyle;
// }

// function toggleLayoutInput() {
//     var layoutCheckbox = document.getElementById('layout_variation_checkbox');
//     var layoutSelect = document.getElementById('layout_variation_select');
//     layoutSelect.style.display = layoutCheckbox.checked ? 'block' : 'none';
// }

// function toggleImageInput() {
//     var imageCheckbox = document.getElementById('image_variation_checkbox');
//     var imageInput = document.getElementById('image_variation_input');
//     imageInput.style.display = imageCheckbox.checked ? 'block' : 'none';
// }

// function uploadImage() {
//     var uploadButton = document.getElementById("image_upload_button");
//     uploadButton.click();
//     uploadButton.addEventListener('change', handleImageUpload);
// }

// function handleImageUpload(event) {
//     var imageFiles = event.target.files;
//     if (imageFiles && imageFiles.length > 0) {
//         for (var i = 0; i < imageFiles.length; i++) {
//             displayImagePreview(imageFiles[i]);
//         }
//     }
// }

// function displayImagePreview(imageFile) {
//     var imagePreview = document.getElementById("image_preview");
   
//     var imageContainer = document.createElement("div");
//     imageContainer.classList.add("image-container");

//     var img = document.createElement("img");
//     img.src = URL.createObjectURL(imageFile);
//     img.classList.add("uploaded-image");
   
//     img.style.width = "200px";
//     img.style.height = "auto"; 
    
//     imageContainer.appendChild(img);

//     var br1 = document.createElement("br");
//     var br2 = document.createElement("br");
//     imageContainer.appendChild(br1);

//     var editButton = createButton("Edit", function() {
//         editImage(imageFile);
//     });
//     imageContainer.appendChild(editButton);

//     var deleteButton = createButton("Delete", function() {
//         deleteImage(imageContainer);
//     });
//     imageContainer.appendChild(deleteButton);

//     imageContainer.appendChild(br2);

//     imagePreview.appendChild(imageContainer);

//     imagePreview.style.display = "block";
// }

// function deleteImage(imageContainer) {
//     imageContainer.remove();
// }

// function editImage(imageFile) {
//     // Functionality to edit image
// }

// function saveTitleVariation() {
//     var inputField = document.getElementById("title_variation_text");
//     var titleVariation = inputField.value.trim();
//     if (titleVariation !== '') {
//         var savedVariation = document.getElementById("saved_title_variation");

//         var variationDiv = document.createElement("div");
//         variationDiv.textContent = titleVariation;

//         var editButton = createButton("Edit", function() {
//             editTitleVariation(this);
//         });

//         var deleteButton = createButton("Delete", function() {
//             deleteTitleVariation(this);
//         });

//         var buttonContainer = document.createElement("div");
//         buttonContainer.appendChild(editButton);
//         buttonContainer.appendChild(deleteButton);

//         var entryContainer = document.createElement("div");
//         entryContainer.classList.add("title-variation");
//         entryContainer.appendChild(variationDiv);
//         entryContainer.appendChild(buttonContainer);

//         savedVariation.appendChild(entryContainer);
//         savedVariation.style.display = "block";
//     }
//     inputField.value = '';
// }

// function editTitleVariation(button) {
//     var savedVariation = button.closest('.title-variation');
//     var titleSpan = savedVariation.querySelector('div');
//     var title = titleSpan.textContent.trim();

//     var inputField = document.getElementById("title_variation_text");
//     inputField.value = title;
//     inputField.style.display = "inline-block";
//     document.getElementById("save_button").style.display = "inline-block";
//     savedVariation.style.display = "none";
//     inputField.focus();
// }

// function deleteTitleVariation(button) {
//     var savedVariation = button.parentNode.parentNode;
//     savedVariation.remove();
// }

// function saveDescriptionVariation() {
//     var inputField = document.getElementById("description_variation_text");
//     var descriptionVariation = inputField.value.trim();
//     if (descriptionVariation !== '') {
//         var savedVariation = document.getElementById("saved_description_variation");

//         var variationDiv = document.createElement("div");
//         variationDiv.textContent = descriptionVariation;

//         var editButton = createButton("Edit", function() {
//             editDescriptionVariation(this);
//         });

//         var deleteButton = createButton("Delete", function() {
//             deleteDescriptionVariation(this);
//         });

//         var buttonContainer = document.createElement("div");
//         buttonContainer.appendChild(editButton);
//         buttonContainer.appendChild(deleteButton);

//         var entryContainer = document.createElement("div");
//         entryContainer.classList.add("description-variation"); 
//         entryContainer.appendChild(variationDiv);
//         entryContainer.appendChild(buttonContainer);

//         savedVariation.appendChild(entryContainer);
//         savedVariation.style.display = "block"; 
//     }
//     inputField.value = ''; 
// }

// function editDescriptionVariation(button) {
//     var savedVariation = button.closest('.description-variation'); 
//     var descriptionDiv = savedVariation.querySelector('div'); 
//     var description = descriptionDiv.textContent.trim(); 

//     var inputField = document.getElementById("description_variation_text");
//     inputField.value = description; 
//     inputField.style.display = "inline-block"; 
//     document.getElementById("description_save_button").style.display = "inline-block"; 
//     savedVariation.style.display = "none"; 
//     inputField.focus();
// }

// function createButton(text, clickHandler) {
//     var button = document.createElement("button");
//     button.textContent = text;
//     button.addEventListener("click", clickHandler);
//     return button;
// }

// function appendToContainer(container, elements) {
//     elements.forEach(function(element) {
//         container.appendChild(element);
//     });
// }

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
