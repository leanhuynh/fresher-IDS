@extends('layouts.app')

@section('css')
<style>
    .profile-pic {
        cursor: pointer;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
<div class="container rounded bg-white">
    <form id="formProfile" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="row">
                <!-- <input type="file" name="avatar" id="avatar"> -->
                <div class="col-md-3 border-right">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <img id="profileImage" class="rounded-circle profile-pic mt-5" width="150px" 
                        src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg" alt="Profile Picture">
                        <span class="font-weight-bold"></span>
                        <span class="text-black-50"></span>
                        <span> </span>
                    </div>
                </div>
                <div class="col-md-9 border-right">
                    <div class="p-3 py-5">
                        <div class="modal-header" style="background-color:black;">
                            <h5 class="modal-title" style="color:white;">Profile Information</h5>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="labels">First name <span style="color:red">*</span></label>
                                <input id="first_name" type="text" class="form-control" placeholder="first name" value="">
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Last name <span style="color:red">*</span></label>
                                <input id="last_name" type="text" class="form-control" value="" placeholder="last name">
                            </div>
                            <div class="col-md-6">
                                <label class="labels">User name <span style="color:red">*</span></label>
                                <input id="user_name" type="text" class="form-control" value="" placeholder="user name">
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Role (Default is Admin) <span style="color:red">*</span></label>
                                <select id="role" class="custom-select">
                                    <option value="" selected>--Select Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="labels">Current Address</label>
                                <input id="address" type="text" class="form-control" placeholder="enter address line" value="">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Email <span style="color:red">*</span></label>
                                <input id="email" type="text" class="form-control" placeholder="enter email" value="">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Password <span style="color:red">*</span></label>
                                <input id="password" type="password" class="form-control" placeholder="enter your password" value="">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Confirm Password <span style="color:red">*</span></label>
                                <input id="password_confirmation" type="password" class="form-control" placeholder="enter new password again" value="">
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <button id="cancelBtn" class="btn btn-secondary profile-button" type="button">Cancel</button>
                            <button id="saveBtn" class="btn btn-primary disabled profile-button" type="button">Save Profile</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Ảnh Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="file" id="fileInput" class="form-control">
                <div class="text-center mt-3">
                    <img id="previewImage" src="" alt="" class="img-fluid d-none" width="150">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="saveImage">Lưu Ảnh</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@parent
<!-- activate "Save Button" -->
<script>

    let first_name = document.getElementById("first_name");
    let last_name = document.getElementById("last_name");
    let user_name = document.getElementById("user_name");
    let role_id = document.getElementById("role");
    let address = document.getElementById("address");
    let email = document.getElementById("email");
    let password = document.getElementById("password");
    let saveButton = document.getElementById("saveBtn");

    // save init value
    const initialValues = {
        first_name: first_name.value,
        last_name: last_name.value,
        user_name: user_name.value,
        role_id: role_id.value,
        address: address.value,
        email: email.value,
        password: password.value,
    };

    function checkChanges() {
        if (
            first_name.value !== initialValues.first_name ||
            last_name.value !== initialValues.last_name ||
            user_name.value !== initialValues.user_name ||
            role_id.value !== initialValues.role_id ||
            address.value !== initialValues.address ||
            email.value !== initialValues.email ||
            password.value !== initialValues.password
        ) {
            saveButton.classList.remove("disabled"); 
            saveButton.removeAttribute("disabled");
        } else {
            saveButton.classList.add("disabled");
            saveButton.setAttribute("disabled", "true");
        }
    }

    // activate check changes
    first_name.addEventListener("input", checkChanges);
    last_name.addEventListener("input", checkChanges);
    user_name.addEventListener("input", checkChanges);
    role_id.addEventListener("input", checkChanges);
    address.addEventListener("input", checkChanges);
    email.addEventListener("input", checkChanges);
    password.addEventListener("input", checkChanges);
</script>
<!-- upload Image -->
<script>
    document.getElementById("profileImage").addEventListener("click", function() {
        let uploadModal = new bootstrap.Modal(document.getElementById("uploadModal"));
        uploadModal.show();
    });

    document.getElementById("fileInput").addEventListener("change", function(event) {
        let file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let previewImage = document.getElementById("previewImage");
                previewImage.src = e.target.result;
                previewImage.classList.remove("d-none");
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById("saveImage").addEventListener("click", function() {
        // activate button "Save Profile"
        saveButton.classList.remove('disabled');
        let previewImage = document.getElementById("previewImage");
        if (previewImage.src) {
            const base64Image = previewImage.src;
            document.getElementById("profileImage").src = previewImage.src;
        }
        let uploadModal = bootstrap.Modal.getInstance(document.getElementById("uploadModal"));
        uploadModal.hide();
    });
</script>
<!-- AJAX -->
<script>
    // convert url to file
    async function urlToFile(url, filename) {
        const response = await fetch(url);
        const blob = await response.blob();
        return new File([blob], filename, { type: blob.type });
    }

    // convert base64 to file
    function base64ToFile(base64, fileName) {
        try {
            // Kiểm tra chuỗi Base64 có hợp lệ không
            if (!base64.startsWith("data:image")) {
                throw new Error("Base64 không hợp lệ");
            }

            // Tách phần header và dữ liệu Base64
            let arr = base64.split(',');
            let mime = arr[0].match(/:(.*?);/)[1]; // Lấy loại MIME (image/png, image/jpeg)
            let byteString = atob(arr[1]); // Giải mã Base64 thành chuỗi nhị phân
            let arrayBuffer = new Uint8Array(byteString.length);

            // Chuyển đổi thành Uint8Array
            for (let i = 0; i < byteString.length; i++) {
                arrayBuffer[i] = byteString.charCodeAt(i);
            }

            // Tạo Blob từ Uint8Array
            let blob = new Blob([arrayBuffer], { type: mime });

            // Tạo File từ Blob
            let file = new File([blob], fileName, { type: mime });

            console.log("File tạo thành công:", file);
            return file;
        } catch (error) {
            console.error("Lỗi chuyển đổi Base64:", error);
            return null;
        }
    }

    $(document).ready(function() {
        // SAVE BUTTON
        $('#saveBtn').on('click', async function() {
            // get image
            const base64Image = $("#profileImage").attr('src');
            let file = base64Image ? base64ToFile(base64Image, 'avatar.png') : null;

            // get infor
            const values = {
                first_name: first_name.value,
                last_name: last_name.value,
                user_name: user_name.value,
                role_id: role_id.value,
                address: address.value,
                email: email.value,
                password: password.value,
                password_confirmation: $('#password_confirmation').val()
            };

            let formData = new FormData();

            // append data
            if (file)
                formData.append('avatar', file);
            
            for (let key in values) {
                formData.append(key, values[key]);
            }

            // append crsf token
            let csrfToken = $('#formProfile').find('input[name="_token"]').val();
            formData.append('_token', csrfToken);

            $.ajax({
                    url: '/api/users',
                    method: 'POST',
                    data: formData,
                    contentType: false, 
                    processData: false,
                    success: function(response) {
                        customAlert(response?.message ?? 'User created successfully', 'success');
                    },
                    error: function(xhr) {
                        customAlert(xhr?.responseJSON?.message ?? 'Error occurred while creating user', 'error');
                    }
                });
        });

        // CANCEL BUTTON
        $("#cancelBtn").click(function() {
            Swal.fire({
                title: "Hotel Management Alert",
                text: "Are you sure to cancel the new user creation process?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, back to list users page!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();
                    }
                });
        });
    });
</script>
@endsection
