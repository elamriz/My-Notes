function checkForErrors() {
    if ($('.is-invalid').length > 0) {
        $('.bi-floppy2-fill').parent().addClass('disabled').css('pointer-events', 'none');
    } else {
        $('.bi-floppy2-fill').parent().removeClass('disabled').css('pointer-events', '');
    }
}
function checkForErrorsNote() {
    var hasErrors = $('.is-invalid').length > 0;

    // Select the submit button using its class
    var submitButton = $('.btn-create-note');

    if (hasErrors) {
        submitButton.prop('disabled', true); // Disable the button if there are errors
        submitButton.css('pointer-events', 'none'); // Optionally, you can also disable pointer events to make the button non-interactive
    } else {
        submitButton.prop('disabled', false); // Enable the button if no errors
        submitButton.css('pointer-events', ''); // Restore pointer events
    }
}

function trySubmitForm() {
    if (!$("#saveButton").hasClass('disabled')) {
        document.getElementById('checklisteditForm').submit();
    } else {
        console.log('Form has errors and cannot be submitted.');
    }
}
function updateAddButtonState() {
    var hasErrors = $('.is-invalid').length > 0;
    var addButton = $('.btn-add'); // Assuming this is the class for your "+" button

    if (hasErrors) {
        addButton.prop('disabled', true); // Disable the button if there are errors
    } else {
        addButton.prop('disabled', false); // Enable the button if no errors
    }
}

function disableEnterKeySubmission() {
    $('.form-control').keypress(function(event) {
        if (event.which === 13) { // 13 is the Enter key
            var form = $(this).closest('form'); // Get the closest form element
            if (form.find('.is-invalid').length > 0) {
                event.preventDefault(); // Prevent form submission if there are invalid fields
            }
        }
    });
}

function validateContent() {
    var contentInput = $("#text"); // Access the textarea by its ID
    var contentError = contentInput.next('.invalid-feedback'); // Get the next sibling element used for showing validation error messages
    var contentValue = contentInput.val().trim(); // Trim whitespace from the content value

    contentError.html(""); // Clear any previous error messages
    contentInput.removeClass("is-invalid"); // Remove the invalid class if it was previously added
    contentInput.removeClass("is-valid"); // Remove the valid class if it was previously added

    // Check if content length is within the specified limits
    if (contentValue.length < minLengthContent || contentValue.length > maxLengthContent) {
        contentError.html("Content must be between " + minLengthContent + " and " + maxLengthContent + " characters long.");
        contentInput.addClass("is-invalid"); // Add the invalid class if the condition fails
    } else {
        contentInput.addClass("is-valid"); // Add the valid class if the content is within the specified limits
    }

    checkForErrorsNote(); // Call the function that checks for any errors in the form and updates the UI accordingly
}

function validateTitle() {
    var titleInput = $("#title");
    var titleError = titleInput.next('.invalid-feedback');
    var titleValue = titleInput.val().trim();

    titleError.html("");
    titleInput.removeClass("is-invalid");
    titleInput.removeClass("is-valid");

    if (titleValue.length < minLengthTitle || titleValue.length > maxLengthTitle) {
        titleError.html("Title must be between " + minLengthTitle + " and " + maxLengthTitle + " characters long.");
        titleInput.addClass("is-invalid");
    } else {
        $.ajax({
            url: baseURL + 'notes/check_title_uniqueness',
            type: 'POST',
            data: {
                title: titleValue,
                noteId: $("#title").data('note-id')
            },
            success: function(data) {
                console.log('Success callback data:', data);
                console.log('Type of isUnique:', typeof data.isUnique);
            
                if(data.isUnique === true) {
                    console.log('Adding is-valid class');
                    titleInput.addClass("is-valid");
                } else if(data.isUnique === false){
                    console.log('Adding is-invalid class because title is not unique');
                    titleError.html("Title must be unique among all notes.");
                    titleInput.addClass("is-invalid");
                }
                checkForErrorsNote()

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error callback:', textStatus, errorThrown);
                titleError.html("Error checking title uniqueness.");
                titleInput.addClass("is-invalid");
                checkForErrorsNote()

            }
        });
    }
    checkForErrors();


}


function validateItem(itemElement) {
    var item = $(itemElement);
    var content = item.val();
    var errorElement = item.next('.invalid-feedback');

    if (content !== item.data('original')) {
        errorElement.text("");
        item.removeClass("is-invalid");
        item.removeClass("is-valid");

        if (content.length < minLengthItem || content.length > maxLengthItem) {
            errorElement.text("Content must be between " + minLengthItem + " and " + maxLengthItem + " characters.");
            item.addClass("is-invalid");
        } else {
            var duplicates = [];
            $('.item-control').each(function() {
                if (this !== itemElement && $(this).val() === content) {
                    duplicates.push(this);
                }
            });

            if (duplicates.length > 0) {
                errorElement.text("Item content must be unique within the same note.");
                item.addClass("is-invalid");
                duplicates.forEach(function(duplicate) {
                    $(duplicate).addClass("is-invalid");
                    $(duplicate).next('.invalid-feedback').text("Item content must be unique within the same note.");
                });
            } else {
                item.addClass("is-valid");
            }
        }
    }
    checkForErrors();
    updateAddButtonState(); // Update the "+" button state after validation


}

$(document).ready(function() {
    // Store original values for validation comparison
    $("#title").data('original', $("#title").val());
    $(".item-control").each(function() {
        $(this).data('original', $(this).val());
    });
    // Bind the validateContent function to the input and change events of the textarea

    $("#text").on('input change', function() {
        validateContent();
        checkForErrors();
    });

    // Event bindings for title and item validation
    $("#title").on('input', function() {
        validateTitle();
        checkForErrors();
    });
    $(".item-control").each(function() {
        $(this).on("input", function() {
            validateItem(this);
            checkForErrors();
        });
    });

    // Prevent form submission with Enter key based on validation errors
    disableEnterKeySubmission();
});
