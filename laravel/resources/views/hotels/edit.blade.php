@extends('layouts.app')

@section('content')
<div class="container rounded bg-white">
    <form id="formRole" enctype="multipart/form-data">
        @csrf
        <div class="modal-header" style="background-color:black;">
            <h5 class="modal-title" style="color:white;">Hotel Information</h5>
        </div>
        <div class="modal-body">
            <div class="container mt-4">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label" for="name_en">Hotel Name (EN) <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="name_en" name="name_en" value="{{ $hotel->name_en ?? '' }}" placeholder="Enter name (EN)">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="name_jp">Hotel Name (JP) <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="name_jp" name="name_jp" value="{{ $hotel->name_jp ?? '' }}" placeholder="Enter name (JP)">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="city_id">City <span style="color: red;">*</span></label>
                        <select class="form-select" id="city_id" name="city">
                            <option value="">--Select City--</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ (isset($hotel) && $hotel->city_id == $city->id) ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="hotel_code">Hotel Code</label>
                        <input type="text" class="form-control" id="hotel_code" name="hotel_code" value="{{ $hotel->hotel_code ?? '' }}" placeholder="Enter hotel code">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label" for="company_name">Company Name <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $hotel->company_name ?? '' }}" placeholder="Enter company name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="email">Email <span style="color: red;">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $hotel->email ?? '' }}" placeholder="Enter email">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="telephone">Telephone <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="telephone" name="telephone" value="{{ $hotel->telephone ?? '' }}" placeholder="Enter telephone number">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="fax">Fax</label>
                        <input type="text" class="form-control" id="fax" name="fax" value="{{ $hotel->fax ?? '' }}" placeholder="Enter fax number">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label" for="tax_code">Tax Code</label>
                        <input type="text" class="form-control" id="tax_code" name="tax_code" value="{{ $hotel->tax_code ?? '' }}" placeholder="Enter tax code">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="address_1">Address 1 <span style="color: red;">*</span></label>
                        <textarea class="form-control" id="address_1" name="address_1" placeholder="Enter address line 1">{{ $hotel->address_1 ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="address_2">Address 2</label>
                        <textarea class="form-control" id="address_2" name="address_2" placeholder="Enter address line 2">{{ $hotel->address_2 ?? '' }}</textarea>
                    </div>
                </div>
                <div class="mt-5 text-center">
                    <button id="backBtn" class="btn btn-primary profile-button" type="button" onclick="window.history.back()">Back</button>
                    <button id="saveBtn" class="btn btn-primary disabled profile-button" type="button">Save Hotel</button>
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
        $("#saveBtn").click(function () {
            // Kiểm tra nếu các trường bắt buộc bị bỏ trống
            // if ($("#name_en").val().trim() === "" || 
            //     $("#name_jp").val().trim() === "" || 
            //     $("#city_id").val().trim() === "" || 
            //     $("#company_name").val().trim() === "" || 
            //     $("#email").val().trim() === "" || 
            //     $("#telephone").val().trim() === "" || 
            //     $("#address_1").val().trim() === "") {
                
            //     Swal.fire({
            //         icon: "error",
            //         title: "Oops...",
            //         text: "Please enter all required fields!",
            //         confirmButtonText: "Yes, I understand!",
            //     });
            //     return;
            // }

            // Thu thập dữ liệu từ form
            let formData = new FormData();
            formData.append("name_en", $("#name_en").val());
            formData.append("name_jp", $("#name_jp").val());
            formData.append("city_id", $("#city_id").val());
            formData.append("owner_id", {{ auth()->id() }});
            formData.append("hotel_code", $("#hotel_code").val());
            formData.append("company_name", $("#company_name").val());
            formData.append("email", $("#email").val());
            formData.append("telephone", $("#telephone").val());
            formData.append("fax", $("#fax").val());
            formData.append("tax_code", $("#tax_code").val());
            formData.append("address_1", $("#address_1").val());
            formData.append("address_2", $("#address_2").val());
            formData.append("_token", "{{ csrf_token() }}"); // CSRF token Laravel
            formData.append("_method", "PUT"); // Laravel nhận diện là PUT request

            // Gửi AJAX request để lưu dữ liệu
            $.ajax({
                url: `/api/hotels/{{ $hotel->id }}`,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    customAlert(response?.message ?? 'Create successfully', 'success');
                },
                error: function(xhr) {
                    customAlert(xhr?.responseJSON?.message ?? 'Create failed', 'error');
                }
            });
        });
    });
</script>
@endsection