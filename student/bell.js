$(document).ready(function() {
    // Toggle the notification list when clicking the bell icon
    $('#over').on('click', function() {
        $('#list').toggle(); // Toggles the notification list visibility
        
        let bellValue = $('#bell-count').attr('data-value');
        
        // Only hide if there's a value (active notifications)
        if (bellValue !== '') {
            if ($('#list').is(':visible')) {
                $(".round").hide(); // Hide the notification badge
            } else {
                $(".round").show(); // Show the notification badge when the list is hidden
            }
        }
    });

    // Hide the notification count when the bell is clicked
    $('#bell-count').on('click', function(e) {
        e.preventDefault();
        // Optional: Update data-value if needed
        let bellValue = $('#bell-count').attr('data-value');
        if (bellValue !== '') {
            $("#list").toggle(); // Toggle the notification list visibility
            $(".round").toggle(); // Toggle the notification badge visibility
        }
    });
});
    