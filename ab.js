function trackInteraction(variationId, interactionType) {
    const data = {
        variationId,
        interactionType,
    };

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/wp-admin/admin-ajax.php');
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Interaction tracked successfully!');
        } else {
            console.error('Failed to track interaction:', xhr.statusText);
        }
    };
    xhr.send(JSON.stringify(data));
}

// Example usage for a CTA button click
document.getElementById('cta-button').addEventListener('click', function() {
    const variationId = document.getElementById('cta-select').value;
    trackInteraction(variationId, 'cta_click');
});
