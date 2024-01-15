$(document).ready(function() {
    $("#registration-form").submit(function(event) {
        var username_valid = true;
        var type_valid = true;
        var file_valid = true; 
        var id_valid = true;

        var username = $("#user_name").val();
        if (username == "") {
            $("#user_name").addClass("is-invalid").removeClass("is-valid");
            username_valid = false;
        } else {
            $("#user_name").addClass("is-valid").removeClass("is-invalid");
        }
        
        if ($('input[type="radio"]:checked').length > 0) {
            $('#radio-invalid').hide();
            $('#radio-valid').show();
        } else {
            $('#radio-invalid').show();
            $('#radio-valid').hide();
            type_valid = false;
        }

        var id = $("#ID").val();
        if (id === "") {
            $("#ID").addClass("is-invalid").removeClass("is-valid");
            id_valid = false;
        } else {
            $("#ID").addClass("is-valid").removeClass("is-invalid");

            $.ajax({
                url: '/manage-library/controller/user_id_check_dup.php',    
                type: 'POST',
                data: { user_id: id },
                async: false, // Đặt async thành false để đảm bảo đồng bộ hóa AJAX
                success: function(response) {
                    if (response === 'duplicate') {
                        $("#ID").addClass("is-invalid").removeClass("is-valid");
                        $("#invalid-feedback").text("ID đã bị trùng. Vui lòng chọn ID khác.");
                        id_valid = false;
                    } else {
                        $("#ID").addClass("is-valid").removeClass("is-invalid");
                        $("#invalid-feedback").text("");
                    }
                }
            });
        }

        var file_input = $("#avatar");
        if (file_input.get(0).files.length === 0) {
            file_input.addClass("is-invalid").removeClass("is-valid");
            file_valid = false;
        } else {
            file_input.addClass("is-valid").removeClass("is-invalid");
            $("#file-feedback").text("");
        }

        if (!username_valid || !type_valid || !id_valid || !file_valid) {
            event.preventDefault();
            return false;
        }
    });
    $("#edit-form").submit(function(event) {
        var username_valid = true;
        var type_valid = true;
        var file_valid = true; 
        var id_valid = true;

        var username = $("#user_name").val();
        if (username == "") {
            $("#user_name").addClass("is-invalid").removeClass("is-valid");
            username_valid = false;
        } else {
            $("#user_name").addClass("is-valid").removeClass("is-invalid");
        }
        
        if ($('input[type="radio"]:checked').length > 0) {
            $('#radio-invalid').hide();
            $('#radio-valid').show();
        } else {
            $('#radio-invalid').show();
            $('#radio-valid').hide();
            type_valid = false;
        }

        var id = $("#ID").val();
        if (id === "") {
            $("#ID").addClass("is-invalid").removeClass("is-valid");
            id_valid = false;
        } else {
            $("#ID").addClass("is-valid").removeClass("is-invalid");
        }

        if (!username_valid || !type_valid || !id_valid || !file_valid) {
            event.preventDefault();
            return false;
        }
    });
});