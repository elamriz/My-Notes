$(document).ready(function() {
    // Gestion de la suppression de note
    $('.deleteNote').on('click', function() {
        var noteId = $(this).data('note-id');
        console.log('Setting noteId:', noteId);
        $('#confirmDelete').data('noteId', noteId);
    });

    $('#confirmDelete').click(function() {
        var noteId = $(this).data('noteId');  
        if (!noteId) {
            console.error("Note ID is undefined or null");
            return;
        }
        
        $.ajax({
            url: 'http://localhost/prwb_2324_c08/notes/delete_note', 
            type: 'POST',
            data: { noteId: noteId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#resultModal').modal('show');
                } else {
                    console.error("Server error: " + response.error);
                }
            },
            error: function(xhr) {
                console.error("AJAX error: ", xhr.responseText);
            }
        });
    });

    $('#resultModal').on('hidden.bs.modal', function () {
        window.location.reload();  
    });

    // Gestion des modifications non enregistrées
    var formOriginalData = $("#editTextNoteForm, #checklisteditForm").serialize();
    var unsavedChangesModal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'), {
        keyboard: false
    });

    function formHasChanged() {
        console.log("Checking form changes...");
        return $("#editTextNoteForm, #checklisteditForm").serialize() !== formOriginalData;
    }

    $("#backButton").click(function(e) {
        console.log("Back button clicked");
        if (formHasChanged()) {
            console.log("Form has changed");
            e.preventDefault();
            unsavedChangesModal.show();
        } else {
            console.log("No changes detected");
            window.history.back();
        }
    });

    $("#confirmExitButton").click(function() {
        window.history.back();
    });

    $("#cancelButton").click(function() {
        unsavedChangesModal.hide();
    });

    $('#unsavedChangesModal').on('hidden.bs.modal', function () {
        // Optional: Any action when modal is hidden
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('checklisteditForm');
    var originalData = new FormData(form);
    var backButton = document.getElementById('backButton');
    var unsavedChangesModal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));
    var confirmExitButton = document.getElementById('confirmExitButton');
    var cancelButton = document.querySelector('.modal-footer .btn-secondary');

    var isFormChanged = function () {
        var currentData = new FormData(form);
        for (var [key, value] of currentData.entries()) {
            if (originalData.get(key) !== value) {
                return true;
            }
        }
        return false;
    };

    backButton.addEventListener('click', function (e) {
        if (isFormChanged()) {
            e.preventDefault();
            unsavedChangesModal.show();
        } else {
            window.location.href = backButton.href;
        }
    });

    confirmExitButton.addEventListener('click', function () {
        window.location.href = backButton.href;
    });

    cancelButton.addEventListener('click', function () {
        unsavedChangesModal.hide();
    });

    document.getElementById('unsavedChangesModal').addEventListener('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        document.querySelector('.modal-backdrop').remove();
    });
});
