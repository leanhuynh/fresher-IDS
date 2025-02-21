@extends('layouts.app')

@section('content')
<div class="container rounded bg-white">
    <form id="formRole" action="/hotels/edit/{{$hotel->id}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header" style="background-color:black;">
            <h5 class="modal-title" style="color:white;">User Information</h5>
        </div>

        <!-- Hiển thị thông báo lỗi nếu có -->
        @if (session('error'))
            <div class="alert alert-danger text-center mt-2">
                {{ session('error') }}
            </div>
        @endif

        <div class="modal-body">
            <div class="container mt-4">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label" for="name_en">Hotel Name (EN) <span style="color: red;">*</span></label>
                        <input type="text" class="form-control {{ $errors->has('name_en') ? 'is-invalid' : '' }}" id="name_en" name="name_en" value="{{ old('name_en', $hotel->name_en ?? '') }}" placeholder="Enter name (EN)">
                        @error('name_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="name_jp">Hotel Name (JP) <span style="color: red;">*</span></label>
                        <input type="text" class="form-control {{ $errors->has('name_jp') ? 'is-invalid' : '' }}" id="name_jp" name="name_jp" value="{{ old('name_jp', $hotel->name_jp ?? '') }}" placeholder="Enter name (JP)">
                        @error('name_jp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="city_id">City <span style="color: red;">*</span></label>
                        <select class="form-select {{ $errors->has('city_id') ? 'is-invalid' : '' }}" id="city_id" name="city_id">
                            <option value="">--Select City--</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city', $hotel->city_id ?? '') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('city_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hotel Code (6 Digits)<span style="color:red">*</span></label>
                        <input type="text" class="form-control {{ $errors->has('hotel_code') ? 'is-invalid' : '' }}" id="hotel_code" name="hotel_code" value="{{ old('hotel_code', $hotel->hotel_code ?? '') }}" placeholder="Enter hotel code">
                        @error('hotel_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label" for="company_name">Company Name <span style="color: red;">*</span></label>
                        <input type="text" class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}" id="company_name" name="company_name" value="{{ old('company_name', $hotel->company_name ?? '') }}" placeholder="Enter company name">
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="email">Email <span style="color: red;">*</span></label>
                        <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" value="{{ old('email', $hotel->email ?? '') }}" placeholder="Enter email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="telephone">Telephone <span style="color: red;">*</span></label>
                        <input type="text" class="form-control {{ $errors->has('telephone') ? 'is-invalid' : '' }}" id="telephone" name="telephone" value="{{ old('telephone', $hotel->telephone ?? '') }}" placeholder="Enter telephone number">
                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="fax">Fax</label>
                        <input type="text" class="form-control" id="fax" name="fax" value="{{ old('fax', $hotel->fax ?? '') }}" placeholder="Enter fax number">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label" for="tax_code">Tax Code</label>
                        <input type="text" class="form-control" id="tax_code" name="tax_code" value="{{ old('tax_code', $hotel->tax_code ?? '') }}" placeholder="Enter tax code">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="address_1">Address 1 <span style="color: red;">*</span></label>
                        <textarea class="form-control {{ $errors->has('address_1') ? 'is-invalid' : '' }}" id="address_1" name="address_1" placeholder="Enter address line 1">{{ old('address_1', $hotel->address_1 ?? '') }}</textarea>
                        @error('address_1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="address_2">Address 2</label>
                        <textarea class="form-control" id="address_2" name="address_2" placeholder="Enter address line 2">{{ old('address_2', $hotel->address_2 ?? '') }}</textarea>
                    </div>
                </div>

                <div class="mt-5 text-center">
                    <button id="cancelBtn" class="btn btn-secondary profile-button" type="button">Cancel</button>
                    <button id="saveBtn" class="btn btn-primary profile-button" type="submit" disabled>Save Hotel</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')
@parent  {{-- inheritate section('js') from app.blade.php --}}
<!-- activate "Save Button" -->
<script>
    $(document).ready(function() {
        let saveButton = $("#saveBtn");
        let initialValues = {};

        // Lưu giá trị ban đầu của các input
        $("#formRole :input").each(function() {
            initialValues[this.id] = $(this).val();
        });

        function checkChanges() {
            let hasChanges = false;
            $("#formRole :input").each(function() {
                if ($(this).val() !== initialValues[this.id]) {
                    hasChanges = true;
                    return false; // Thoát vòng lặp sớm nếu có thay đổi
                }
            });

            if (hasChanges) {
                saveButton.removeClass("disabled").prop("disabled", false);
            } else {
                saveButton.addClass("disabled").prop("disabled", true);
            }
        }

        // Bắt sự kiện input trên tất cả các input trong form
        $("#formRole :input").on("input change", checkChanges);
    });
</script>
<!-- AJAX -->
<script>
    $(document).ready(function() {

        // CANCEL BUTTON
        $("#cancelBtn").click(function() {
            Swal.fire({
                title: "Hotel Management Alert",
                text: "Are you sure to cancel the edit hotel process?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, back to list hotels page!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/hotels';
                    }
                });
        });
    });
</script>
@endsection