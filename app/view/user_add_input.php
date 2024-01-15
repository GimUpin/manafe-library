        <?php
        include_once('../controller/user_add_edit.php');
        include '../view/header.php';
        ?>

        <body>
            <div class="container mt-5">
                <form method="POST" id="registration-form" enctype="multipart/form-data" name="register_form">
                    <div class="form-group">
                        <label for="user_name"><span class="text">Họ và tên</span></label>
                        <input type="text" id="user_name" name="user_name" class="form-control" maxlength="100">
                        <div class="invalid-feedback">
                            Hãy nhập họ và tên.
                        </div>
                        <div class="valid-feedback">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><span class="form-label">Phân loại</span></label>
                        <?php
                        $types = array(1 => 'Giáo viên', 2 => 'Sinh viên');
                        foreach ($types as $key => $type) {
                            echo '<div class="form-check">';
                            echo '<input class="form-check-input" type="radio" id="type' . $key . '" name="type[]" value="' . $key . '">';
                            echo '<label class="form-check-label" for="type' . $key . '">';
                            echo $type;
                            echo '</label>';
                            echo '</div>';
                        }
                        echo '<div id="radio-invalid" class="invalid-feedback">Hãy chọn phân loại.</div>';
                        echo '<div id="radio-valid" class="valid-feedback"></div>';
                        ?>
                    </div>

                    <div class="form-group">
                        <label for="ID">ID</label>
                        <input type="text" id="ID" name="ID" class="form-control" maxlength="10" pattern="[0-9A-Za-z]{1,10}">
                        <div class="invalid-feedback" id="invalid-feedback"> 
                            Hãy nhập ID
                        </div>
                        <div class="valid-feedback">
                        </div>
                    </div>
                    <label>Avatar</label>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="avatar" name="image" accept="image/*">
                            <label class="custom-file-label" for="avatar">Tải ảnh lên</label>
                            <div id="file-feedback" class="invalid-feedback">Hãy chọn avatar</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Mô tả thêm</label>
                        <textarea name="description" class="form-control" rows="4" cols="50" maxlength="1000"></textarea>
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" class="btn btn-primary btn-lg" id="registration-form" value="Xác nhận" name='register'>
                    </div>
            </div>
            </form>
            </div>
        </body>

        </html>
