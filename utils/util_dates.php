<?php
$now = new DateTime();
$interval = $now->diff($note->created_at);

// Function to format the interval into a human-readable format
function formatTimeDifference($dateTime) {
    if ($dateTime === null) {
        return null;
    }
    $now = new DateTime();
    $interval = $now->diff($dateTime);

    if ($interval->y > 0) {
        return $interval->y . " year" . ($interval->y > 1 ? "s" : "");
    } elseif ($interval->m > 0) {
        return $interval->m . " month" . ($interval->m > 1 ? "s" : "");
    } elseif ($interval->d > 0) {
        return $interval->d . " day" . ($interval->d > 1 ? "s" : "");
    } elseif ($interval->h > 0) {
        return $interval->h . " hour" . ($interval->h > 1 ? "s" : "");
    } elseif ($interval->i > 0) {
        return $interval->i . " minute" . ($interval->i > 1 ? "s" : "");
    } elseif ($interval->s > 0) {
        return $interval->s . " second" . ($interval->s > 1 ? "s" : "");
    } else {
        return "just now";
    }
}

// Display the time difference in italic
$createdAtMessage = "<i>Created " . formatTimeDifference($note->created_at) . " ago.</i>";

// Logic for displaying the edited message
$editedAtMessage = "";
if ($note->edited_at !== null) { // Vérifier si edited_at n'est pas null
    $editedTime = formatTimeDifference($note->edited_at);
    if ($editedTime !== "just now") {
        $editedAtMessage = "<i>Edited " . $editedTime . " ago</i>";
    } else {
        $editedAtMessage = "<i>Edited just now</i>";
    }
}

echo "<p>$createdAtMessage $editedAtMessage</p>";
?>