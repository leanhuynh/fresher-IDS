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
    <form id="formProfile" action="/users/create" method="POST" enctype="multipart/form-data">
        @csrf

        @if (session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif

        <div class="modal-body">
            <div class="row">
                <!-- <input type="file" name="avatar" id="avatar"> -->
                <div class="col-md-3 border-right">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <!-- Profile Image -->
                        <label for="fileInput" class="position-relative" style="cursor: pointer;">
                            <div id="imageContainer">
                                <img id="profileImage" class="rounded-circle profile-pic mt-3 d-none" width="150px" alt="Profile Picture">
                                <span id="noImageText" class="text-muted">No Image (click to Upload)</span>
                            </div>
                            <input type="file" id="fileInput" name="avatar" class="d-none" accept="image/*">
                        </label>
                        <span class="font-weight-bold"></span>
                        <span class="text-black-50"></span>
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
                                <input id="first_name" name="first_name" type="text" class="form-control" 
                                        placeholder="First name" value="{{ old('first_name') }}">
                                @error('first_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-danger error-message"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Last name <span style="color:red">*</span></label>
                                <input id="last_name" name="last_name" type="text" class="form-control" 
                                        placeholder="Last name" value="{{ old('last_name') }}">
                                @error('last_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-danger error-message"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="labels">User name <span style="color:red">*</span></label>
                                <input id="user_name" name="user_name" type="text" class="form-control" 
                                        placeholder="User name" value="{{ old('user_name') }}">
                                @error('user_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-danger error-message"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Role (Default is Admin) <span style="color:red">*</span></label>
                                <select id="role" name="role" class="custom-select">
                                    <option value="" selected>--Select Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-danger error-message"></div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="labels">Current Address</label>
                                <input id="address" name="address" type="text" class="form-control" 
                                        placeholder="Enter address line" value="{{ old('address') }}">
                                <div class="text-danger error-message"></div>
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Email <span style="color:red">*</span></label>
                                <input id="email" name="email" type="text" class="form-control" 
                                        placeholder="Enter email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-danger error-message"></div>
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Password <span style="color:red">*</span></label>
                                <input id="password" name="password" type="password" class="form-control" 
                                        placeholder="Enter your password" value="{{ old('password') }}">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-danger error-message"></div>
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Confirm Password <span style="color:red">*</span></label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Confirm password">
                                @error('password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="text-danger error-message"></div>
                            </div>
                        </div>

                        <div class="mt-5 text-center">
                            <button id="cancelBtn" class="btn btn-secondary profile-button" type="button">Cancel</button>
                            <button id="saveBtn" class="btn btn-primary disabled profile-button" type="submit">Save Profile</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
    document.addEventListener("DOMContentLoaded", function () {
        const fileInput = document.getElementById("fileInput");
        const profileImage = document.getElementById("profileImage");
        const noImageText = document.getElementById("noImageText");

        // Khi chọn file, cập nhật ảnh hoặc hiển thị "No Image"
        fileInput.addEventListener("change", function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    profileImage.src = e.target.result; // Cập nhật ảnh
                    profileImage.classList.remove("d-none"); // Hiển thị ảnh
                    noImageText.classList.add("d-none"); // Ẩn chữ "No Image"
                };
                reader.readAsDataURL(file);
            } else {
                profileImage.classList.add("d-none"); // Ẩn ảnh
                noImageText.classList.remove("d-none"); // Hiện chữ "No Image"
            }
        });
    });
</script>
<!-- AJAX -->
<script>
    $(document).ready(function() {
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
                        window.location.href = '/users';
                    }
                });
        });
    });
</script>
@endsection
