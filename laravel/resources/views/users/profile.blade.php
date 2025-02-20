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
    <form id="formProfile" action="/users/edit/{{$user->id}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        @if (session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif

        <div class="modal-body">
            <div class="row">
                <div class="col-md-3 border-right">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <!-- Profile Image -->
                        <label for="fileInput" class="position-relative" style="cursor: pointer;">
                            <div id="imageContainer">
                                <img id="profileImage" class="rounded-circle profile-pic mt-3" width="150px"
                                    src="{{ $user->avatar ? asset('storage/' . $user->avatar) : '' }}"
                                    alt="Profile Picture"
                                    style="{{ $user->avatar ? '' : 'display: none;' }}">
                                <span id="noImageText" class="text-muted" style="{{ $user->avatar ? 'display: none;' : '' }}">
                                    No Image (click to Upload)
                                </span>
                            </div>
                            <input type="file" id="fileInput" name="avatar" class="d-none" accept="image/*">
                        </label>
                        <span class="font-weight-bold">{{ $user->name }}</span>
                        <span class="text-black-50">{{ $user->email }}</span>
                    </div>
                </div>
                <div class="col-md-9 border-right">
                    <div class="p-3 py-5">
                        <div class="modal-header bg-black">
                            <h5 class="modal-title text-white">Profile Information</h5>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="labels">First name <span class="text-danger">*</span></label>
                                <input id="first_name" name="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    placeholder="First name" value="{{ old('first_name', $user->first_name) }}">
                                @error('first_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Last name <span class="text-danger">*</span></label>
                                <input id="last_name" name="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    placeholder="Last name" value="{{ old('last_name', $user->last_name) }}">
                                @error('last_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="labels">User name <span class="text-danger">*</span></label>
                                <input id="user_name" name="user_name" type="text" class="form-control @error('user_name') is-invalid @enderror"
                                    placeholder="User name" value="{{ old('user_name', $user->user_name) }}">
                                @error('user_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Role (Default is Admin) <span style="color:red">*</span></label>
                                <select id="role" class="custom-select">
                                    <option value="">--Select Role--</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $role->id == $user->role_id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="labels">Email <span class="text-danger">*</span></label>
                                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="labels">Address</label>
                                <input id="address" name="address" type="text" class="form-control @error('address') is-invalid @enderror"
                                    placeholder="Enter your address" value="{{ old('address', $user->address) }}">
                                @error('address')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-5 text-center">
                            <button id="cancelBtn" class="btn btn-secondary profile-button" type="button">Cancel</button>
                            <button id="saveBtn" class="btn btn-primary profile-button disabled" type="submit">Save Profile</button>
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
    let saveButton = document.getElementById("saveBtn");

    // save init value
    let initialValues = {
        first_name: first_name.value,
        last_name: last_name.value,
        user_name: user_name.value,
        role_id: role_id.value,
        address: address.value,
        email: email.value,
    };

    function checkChanges() {
        if (
            first_name.value !== initialValues.first_name ||
            last_name.value !== initialValues.last_name ||
            user_name.value !== initialValues.user_name ||
            role_id.value !== initialValues.role_id ||
            address.value !== initialValues.address ||
            email.value !== initialValues.email 
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
    // password.addEventListener("input", checkChanges);
</script>
<!-- upload Image -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const fileInput = document.getElementById("fileInput");
        const profileImage = document.getElementById("profileImage");
        const noImageText = document.getElementById("noImageText");

        // Khi người dùng chọn file
        fileInput.addEventListener("change", function () {
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    saveButton.classList.remove("disabled"); 
                    saveButton.removeAttribute("disabled");
                    profileImage.src = e.target.result;
                    profileImage.style.display = "block"; // Hiện ảnh
                    noImageText.style.display = "none"; // Ẩn chữ "No Image"
                };
                reader.readAsDataURL(fileInput.files[0]); // Đọc file
            }
        });

        // Nếu trang load mà không có ảnh, hiển thị chữ "No Image"
        if (!profileImage.src || profileImage.src === window.location.href) {
            profileImage.style.display = "none";
            noImageText.style.display = "block";
        }
    });

    $(document).ready(function() {
        // CANCEL BUTTON
        $("#cancelBtn").click(function() {
            Swal.fire({
                title: "Hotel Management Alert",
                text: "Are you sure to cancel the edit user profile process?",
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