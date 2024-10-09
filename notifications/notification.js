$(document).ready(function() {
    // Toggle the notifications list
    $('#over').on('click', function() {
        $('#list').toggle();
    });

    // Message with Ellipsis
    $('div.msg').each(function() {
        var len = $(this).text().trim(" ").split(" ");
        if (len.length > 12) {
            var add_elip = $(this).text().trim().substring(0, 65) + "…";
            $(this).text(add_elip);
        }
    });

    // Handle notification creation
    $('#notify').on('click', function(e) {
        e.preventDefault();
        var name = $('#notifications_name').val();
        var ins_msg = $('#message').val();
        if ($.trim(name).length > 0 && $.trim(ins_msg).length > 0) {
            var form_data = $('#frm_data').serialize();
            $.ajax({
                url: './connection/insert.php',
                type: 'POST',
                data: form_data,
                success: function(data) {
                    location.reload();
                }
            });
        } else {
            alert("Please Fill All the fields");
        }
    });

    // Handle notification deletion
    $(document).on('click', '.delete-notification', function(e) {
        e.preventDefault();
        let notification_id = $(this).data('id'); // Get notification ID

        // Confirm before deletion
        if (confirm('Are you sure you want to delete this notification?')) {
            $.ajax({
                url: './connection/delete.php',
                type: 'POST',
                data: { "id": notification_id },
                success: function(response) {
                    console.log(response);
                    location.reload(); // Reload the page after deletion
                },
                error: function() {
                    alert("Error deleting notification.");
                }
            });
        }
    });
});
