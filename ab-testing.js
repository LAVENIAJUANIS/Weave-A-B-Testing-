function toggleCTADropdown() {
    var ctaDropdown = document.getElementById('cta-dropdown');
    ctaDropdown.style.display = document.getElementById('goal-cta').checked ? 'block' : 'none';
}

function toggleInquiryDropdown() {
    var inquiryDropdown = document.getElementById('inquiry-dropdown');
    inquiryDropdown.style.display = document.getElementById('goal-inquiry').checked ? 'block' : 'none';
}

function toggleTitleInput() {
    var checkbox = document.getElementById("title_variation_checkbox");
    var inputField = document.getElementById("title_variation_input");
    inputField.style.display = checkbox.checked ? "block" : "none";
    var savedVariation = document.getElementById("saved_title_variation");
    savedVariation.style.display = "none";
}

function saveTitleVariation() {
    var inputField = document.getElementById("title_variation_text");
    var titleVariation = inputField.value.trim();
    if (titleVariation !== '') {
        var savedVariation = document.getElementById("saved_title_variation");
        var variationDiv = document.createElement("div");
        variationDiv.textContent = titleVariation;
        var editButton = createButton("Edit", function() {
            editTitleVariation(this);
        });
        var deleteButton = createButton("Delete", function() {
            deleteTitleVariation(this);
        });
        var buttonContainer = document.createElement("div");
        buttonContainer.appendChild(editButton);
        buttonContainer.appendChild(deleteButton);
        var entryContainer = document.createElement("div");
        entryContainer.classList.add("title-variation");
        entryContainer.appendChild(variationDiv);
        entryContainer.appendChild(buttonContainer);
        savedVariation.appendChild(entryContainer);
        savedVariation.style.display = "block";
    }
    inputField.value = '';
}

function editTitleVariation(button) {
    var savedVariation = button.closest('.title-variation');
    var titleSpan = savedVariation.querySelector('div');
    var title = titleSpan.textContent.trim();
    var inputField = document.getElementById("title_variation_text");
    inputField.value = title;
    inputField.style.display = "block";
    document.getElementById("save_button").style.display = "block";
    savedVariation.style.display = "none";
    inputField.focus();
}

function deleteTitleVariation(button) {
    var savedVariation = button.parentNode.parentNode;
    savedVariation.remove();
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
        inputField.value = '';
        var savedVariations = document.getElementsByClassName("description-variation");
        Array.from(savedVariations).forEach(function(savedVariation) {
            savedVariation.style.display = 'none';
        });
    }
}

function saveDescriptionVariation() {
    var inputField = document.getElementById("description_variation_text");
    var descriptionVariation = inputField.value.trim();
    if (descriptionVariation !== '') {
        var savedVariation = document.getElementById("saved_description_variation");
        var variationDiv = document.createElement("div");
        variationDiv.textContent = descriptionVariation;
        var editButton = createButton("Edit", function() {
            editDescriptionVariation(this);
        });
        var deleteButton = createButton("Delete", function() {
            deleteDescriptionVariation(this);
        });
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
    inputField.style.display = "block";
    document.getElementById("description_save_button").style.display = "block";
    savedVariation.style.display = "none";
    inputField.focus();
}

function toggleImageInput() {
    var checkbox = document.getElementById("image_variation_checkbox");
    var inputField = document.getElementById("image_variation_input");
    inputField.style.display = checkbox.checked ? "block" : "none";
}

function uploadImage() {
    var uploadButton = document.getElementById("image_upload_button");
    uploadButton.click();
    uploadButton.addEventListener('change', handleImageUpload);
}

function handleImageUpload(event) {
    var imageFile = event.target.files[0];
    if (imageFile) {
        saveImageVariation(imageFile);
    }
}

function saveImageVariation(imageFile) {
    if (!imageFile) {
        return;
    }
    var savedVariations = document.getElementById("saved_image_variations");
    savedVariations.style.display = "block";
    var container = document.createElement("div");
    var img = document.createElement("img");
    img.src = URL.createObjectURL(imageFile);
    img.classList.add("image-variation");
    img.style.maxWidth = "200px";
    img.style.maxHeight = "200px";
    var editButton = createButton("Edit", function() {
        editImageVariation(img);
    });
    var deleteButton = createButton("Delete", function() {
        deleteImageVariation(container);
    });
    container.appendChild(img);
    container.appendChild(document.createElement("br"));
    container.appendChild(editButton);
    container.appendChild(deleteButton);
    savedVariations.appendChild(container);
}

function editImageVariation(imageElement) {
    // Implementation for editing image variation
}

function deleteImageVariation(container) {
    container.remove();
    var savedVariations = document.getElementById("saved_image_variations");
    if (savedVariations.childElementCount === 0) {
        savedVariations.style.display = "none";
    }
}

function toggleLayoutInput() {
    var layoutCheckbox = document.getElementById("layout_variation_checkbox");
    var layoutDropdown = document.getElementById("layout_variation_input");

    if (layoutCheckbox.checked) {
        layoutDropdown.style.display = "block";
    } else {
        layoutDropdown.style.display = "none";
    }
}

function saveLayoutVariation() {
    var selectedLayout = document.getElementById("layout_variation_select").value;
    var savedLayout = document.getElementById("saved_layout_variation");
    savedLayout.innerHTML = "Selected Layout: " + selectedLayout;
    savedLayout.style.display = "block";
}

function createButton(text, clickHandler) {
    var button = document.createElement("button");
    button.textContent = text;
    button.addEventListener("click", clickHandler);
    return button;
}

document.getElementById("set-test-duration").addEventListener("click", function() {
    var durationSelect = document.getElementById("test-duration");
    var selectedDuration = parseInt(durationSelect.value);
    console.log("Selected duration:", selectedDuration, "days");
});

document.getElementById("save-test").addEventListener("click", function() {
    // Implementation for saving the test
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
