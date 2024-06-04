$(function() {
    $('.notes-container').sortable({
        connectWith: '.notes-container',
        items: '> div',
        placeholder: 'sortable-placeholder',
        opacity: 0.9,
        start: function(event, ui) {
            var originalPinnedStatus = ui.item.closest('[data-pinned]').data('pinned') ? 'true' : 'false';
            ui.item.data('original-pinned-status', originalPinnedStatus);
        },
        stop: function(event, ui) {
            var isPinned = ui.item.closest('.notes-container').hasClass('pinned-notes');
            var orderedIds = ui.item.parent().sortable('toArray', { attribute: 'data-id' });
            var movedNoteId = ui.item.data('id');
            var originalPinnedStatus = ui.item.data('original-pinned-status');
            var isCurrentlyPinned = ui.item.closest('[data-pinned]').data('pinned') ? 'true' : 'false';
            var dropZone = ui.item.closest('.notes-container').data('pinned') ? 'pinned-notes' : 'other-notes';

            console.log("Ordered IDs:", orderedIds);
            console.log("Moved Note ID:", movedNoteId);
            console.log("Original Pinned Status:", originalPinnedStatus);
            console.log("Is Currently Pinned:", isCurrentlyPinned);
            console.log("Drop Zone:", dropZone);
            updateNotesOrderAndPinStatus(isPinned, orderedIds, isCurrentlyPinned, originalPinnedStatus, movedNoteId, dropZone);
        }
    });
});

function updateNotesOrderAndPinStatus(isPinned, orderedIds, isCurrentlyPinned, originalPinnedStatus, movedNoteId, dropZone) {
    console.log("Sending AJAX with:", isPinned, orderedIds, isCurrentlyPinned, originalPinnedStatus, movedNoteId, dropZone);
    $.ajax({
        url: 'http://localhost/prwb_2324_c08/notes/updateNotesOrderAndPinStatus',
        type: 'POST',
        dataType: 'json',
        data: {
            isPinned: isPinned,
            orderedIds: orderedIds,
            isCurrentlyPinned: isCurrentlyPinned,
            originalPinnedStatus: originalPinnedStatus,
            movedNoteId: movedNoteId,
            dropZone: dropZone,
        },
        success: function(response) {
            console.log("Response:", response);
        },
        error: function(xhr, status, error) {
            console.error("Error in AJAX call:", status, error);
        }
    });
}
