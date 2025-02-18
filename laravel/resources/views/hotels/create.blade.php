@extends('layouts.app')

@section('content')
<div class="container rounded bg-white">
    <form id="formHotel" enctype="multipart/form-data">
        @csrf
        <div class="modal-header" style="background-color:black;">
            <h5 class="modal-title" style="color:white;">Hotel Information</h5>
        </div>
        <div class="modal-body">
            <div class="container mt-4">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Hotel Name (EN) <span style="color:red">*</span></label>
                        <input id="name_en" placeholder="enter hotel name (en)" type="text" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hotel Name (JP) <span style="color:red">*</span></label>
                        <input id="name_jp" placeholder="enter hotel name (jp)" type="text" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">City <span style="color:red">*</span></label>
                        <select id="city_id" class="form-select">
                            <option>--Select City--</option>
                            @foreach($cities as $city)
                                <option value='{{$city->id}}'>{{$city->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hotel Code (6 Digits)<span style="color:red">*</span></label>
                        <input id="hotel_code" placeholder="enter hotel code" type="text" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Company Name <span style="color:red">*</span></label>
                        <input id="company_name" placeholder="enter hotel name" type="text" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email <span style="color:red">*</span></label>
                        <input id="email" placeholder="enter email" type="email" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Telephone <span style="color:red">*</span></label>
                        <input id="telephone" placeholder="enter telephone" type="text" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fax</label>
                        <input id="fax" placeholder="enter fax" type="text" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Tax Code</label>
                        <input id="tax_code" placeholder="enter tax code" type="text" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Address 1 <span style="color:red">*</span></label>
                        <textarea id="address_1" placeholder="enter address 1" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address 2</label>
                        <textarea id="address_2" placeholder="enter address 2" class="form-control"></textarea>
                    </div>
                </div>
                <div class="mt-5 text-center">
                    <button id="cancelBtn" class="btn btn-secondary profile-button" type="button">Cancel</button>
                    <button id="saveBtn" class="btn btn-primary disabled profile-button" type="button">Save Hotel</button>
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
    let name_en = document.getElementById('name_en');
    let name_jp = document.getElementById('name_jp');
    let city_id = document.getElementById('city_id');
    let hotel_code = document.getElementById('hotel_code');
    let company_name = document.getElementById('company_name');
    let email = document.getElementById('email');
    let telephone = document.getElementById('telephone');
    let fax = document.getElementById('fax');
    let tax_code = document.getElementById('tax_code');
    let address_1 = document.getElementById('address_1');
    let address_2 = document.getElementById('address_2');
    let saveButton = document.getElementById("saveBtn");

    // save init value
    const initialValues = {
        name_en: name_en.value,
        name_jp: name_jp.value,
        city_id: city_id.value,
        hotel_code: hotel_code.value,
        company_name: company_name.value,
        email: email.value,
        telephone: telephone.value,
        fax: fax.value,
        tax_code: tax_code.value,
        address_1: address_1.value,
        address_2: address_2.value
    };

    function checkChanges() {
        if (
            name_en.value !== initialValues.name_en ||
            name_jp.value !== initialValues.name_jp ||
            city_id.value !== initialValues.city_id ||
            hotel_code.value !== initialValues.hotel_code ||
            company_name.value !== initialValues.company_name ||
            email.value !== initialValues.email ||
            telephone.value !== initialValues.telephone ||
            fax.value !== initialValues.fax ||
            tax_code.value !== initialValues.tax_code ||
            address_1.value !== initialValues.address_1 ||
            address_2.value !== initialValues.address_2
        ) {
            saveButton.classList.remove("disabled"); 
            saveButton.removeAttribute("disabled");
        } else {
            saveButton.classList.add("disabled");
            saveButton.setAttribute("disabled", "true");
        }
    }

    // activate check changes
    name_en.addEventListener("input", checkChanges);
    name_jp.addEventListener("input", checkChanges);
    city_id.addEventListener("input", checkChanges);
    hotel_code.addEventListener("input", checkChanges);
    company_name.addEventListener("input", checkChanges);
    email.addEventListener("input", checkChanges);
    telephone.addEventListener("input", checkChanges);
    fax.addEventListener("input", checkChanges);
    tax_code.addEventListener("input", checkChanges);
    address_1.addEventListener("input", checkChanges);
    address_2.addEventListener("input", checkChanges);
</script>
<!-- AJAX -->
<script>
    $(document).ready(function() {
        $('#saveBtn').on('click', function() {

            // get infor
            const values = {
                name_en: name_en.value,
                name_jp: name_jp.value,
                owner_id: {{auth()->id()}},
                city_id: city_id.value,
                hotel_code: hotel_code.value,
                company_name: company_name.value,
                email: email.value,
                telephone: telephone.value,
                fax: fax.value,
                tax_code: tax_code.value,
                address_1: address_1.value,
                address_2: address_2.value
            };

            let formData = new FormData();

            // append data
            for (let key in values) {
                formData.append(key, values[key]);
            }

            // append crsf token
            let csrfToken = $('#formHotel').find('input[name="_token"]').val();
            formData.append('_token', csrfToken);

            $.ajax({
                    url: '/api/hotels',
                    method: 'POST',
                    data: formData,
                    contentType: false, 
                    processData: false,
                    success: function(response) {
                        customAlert(response?.message ?? 'Create successfully', 'success');
                    },
                    error: function(xhr) {
                        customAlert(xhr?.responseJSON?.message ?? 'Create failed', 'error');
                    }
                });
        });

        $("#cancelBtn").click(function() {
            Swal.fire({
                title: "Hotel Management Alert",
                text: "Are you sure to cancel the new hotel creation process?",
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