function getTotal() {
    var total = 0;
    $("tr").each(function() {
        var td = $(this).find('td:eq(3)'); // lấy thẻ td thứ 3
        if (td.length) {
            var quantity = parseInt(td.text()); // chuyển đổi giá trị thành số nguyên
            total += quantity; // cộng thêm giá trị mới vào tổng
        }
    });
    return total; // trả về kết quả tổng
}

function searchBooks(event) {
    var category = document.getElementById("inputCategory").value;
    var keyword = document.getElementById("inputKeyword").value;

    // Kiểm tra xem category và keyword có rỗng hay không
    if (category === '' && keyword === '') {
        $(".title").text("Số quyển sách tìm thấy: 0"); // Xóa giá trị mặc định khi không có giá trị tìm kiếm
        $("#bookTableBody").css('display', 'none'); // Ẩn phần tbody
        return; // Không làm gì nếu không có giá trị tìm kiếm
    }

    // Thực hiện AJAX để gửi yêu cầu tìm kiếm và xử lý kết quả ở đây
    $.ajax({
        url: '../controller/book_search.php', // Đường dẫn đến file xử lý tìm kiếm
        method: 'POST',
        data: {
            category: category,
            keyword: keyword
        },
        success: function(response) {
            if (response.trim() !== "") {
                console.log(response);
                // Cập nhật nội dung của tbody và hiển thị nó
                $("#bookTableBody").html(response).css('display', 'table-row-group');
                var total = getTotal();
                $(".title").text("Số quyển sách tìm thấy: " + total);
            } else {
                // Ẩn phần tbody và hiển thị thông báo khi không có kết quả
                $("#bookTableBody").css('display', 'none');
                $(".title").text("Số quyển sách tìm thấy: 0");
            }
        }
    });
}


// Thêm một sự kiện onclick cho button Xóa
$(document).on('click', '.deleteButton', function() {
    // Lấy thông tin của bản ghi được chọn
    var row = $(this).closest('tr');
    var name = row.find('td:eq(1)').text();

    // Bật popup confirm
    if (confirm('Bạn chắc chắn muốn xóa quyển sách này?')) {
        // Gửi yêu cầu xóa bản ghi tới server
        $.ajax({
            url: '../controller/book_delete.php', // Đường dẫn đến file xử lý xóa
            method: 'POST',
            data: {
                name: name
            },
            success: function(response) {
                location.reload();
            }
        });
    }
});

// Thêm một sự kiện onclick cho button Sửa
$(document).on('click', '.editButton', function() {
    // Lấy thông tin của bản ghi được chọn
    var row = $(this).closest('tr');
    var id = row.find('td:eq(1)').text();

    // Chuyển đến trang edit.php với tham số name
    window.location.href = '../view/book_update.php?id=' + encodeURIComponent(id);
});

//Edit function


function updateItem() {
    var formData = new FormData($("#updateForm")[0]);

    var fileInput = $('#another_img')[0];
    if (fileInput.files.length > 0) {
        formData.append('image', fileInput.files[0]);
    }
    $.ajax({
        url: "../controller/book_edit.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            alert(response);
            // Display the alert, wait for a moment, and then redirect
            setTimeout(function() {
                window.location.href = 'book_list.php';
            }, 1000); // Redirect after 1000 milliseconds (1 second)
        }
    });
}

function displayEditItem(id) {
    $.ajax({
        url: "../controller/book_get.php",
        type: "POST",
        data: {
            id: id
        },
        success: function(response) {
            console.log(response);
            showEditItem(response);
        }
    })
}

function showEditItem(data) {
    var category_arr = {
        "Khoa học" : "",
        "Tiểu thuyết": "",
        "Manga": "",
        "Sách giáo khoa": ""
    };
    data = JSON.parse(data);
    var defaultCategory = data.category;
    $("option[value='" + category_arr[defaultCategory] + "']").prop("selected", true);

    category_arr[defaultCategory] = "selected";
    // console.log(data);
    $("#id").val(data.id);
    $("#name").val(data.name);
    $("option[value='"+category_arr[data.category]+"']").prop("selected", true);
    $("#author").val(data.author);
    $("#quantity").val(data.quantity);
    $("#description").val(data.description);
    $("#img_upload").attr("src", 'avatar/'+data.avatar);
    $("#img_path").val(data.avatar);
}
