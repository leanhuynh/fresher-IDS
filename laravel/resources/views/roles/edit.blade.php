@extends('layouts.app')

@section('css')
<style>
    .profile-pic {
        cursor: pointer;
        object-fit: cover;
    }
</style>
@stop

@section('content')
<div class="container rounded bg-white">
    <form id="formRole" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="p-3 py-5">
                        <div class="modal-header" style="background-color:black;">
                            <h5 class="modal-title" style="color:white;">Role Information</h5>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="labels">Role name</label>
                                <input id="name" type="text" class="form-control" placeholder="enter name" value="{{$role->name}}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Role description</label>
                                <input id="description" type="text" class="form-control" value="{{$role->description}}" placeholder="enter description">
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <button id="backBtn" class="btn btn-primary profile-button" type="button" onclick="window.history.back()">Back</button>
                            <button id="saveBtn" class="btn btn-primary disabled profile-button" type="button">Save Role</button>
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

    let name = document.getElementById('name');
    let description = document.getElementById('description');
    let saveButton = document.getElementById("saveBtn");

    // save init value
    const initialValues = {
        name: name.value,
        description: description.value
    };

    function checkChanges() {
        if (
            name.value !== initialValues.name ||
            description.value !== initialValues.description
        ) {
            saveButton.classList.remove("disabled"); 
            saveButton.removeAttribute("disabled");
        } else {
            saveButton.classList.add("disabled");
            saveButton.setAttribute("disabled", "true");
        }
    }

    // activate check changes
    name.addEventListener("input", checkChanges);
    description.addEventListener("input", checkChanges);
</script>
<!-- AJAX -->
<script>

    $(document).ready(function() {
        $('#saveBtn').on('click', async function() {
            // get image
            const base64Image = $("#profileImage").attr('src');

            // get infor
            const values = {
                name: name.value,
                description: description.value,
            };

            let formData = new FormData();

            // append data
            for (let key in values) {
                formData.append(key, values[key]);
            }

            // append crsf token
            let csrfToken = $('#formRole').find('input[name="_token"]').val();
            formData.append('_token', csrfToken);
            formData.append('_method', 'PUT');

            $.ajax({
                    url: '/api/roles/{{$role->id}}',
                    method: 'POST',
                    data: formData,
                    contentType: false, 
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            title: "<strong>Hotel Management Notifications</strong>",
                            html: `<strong style='color:green'>${response?.message ?? 'Updated successfully'}</strong>`,
                            timer: 2000,
                            position: 'top-end',
                            showConfirmButton: false,
                            timerProgressBar: true,
                            showClass: {
                                popup: `
                                    animate__animated
                                    animate__fadeInUp
                                    animate__faster
                                    `
                            },
                            hideClass: {
                                popup: `
                                    animate__animated
                                    animate__fadeOutDown
                                    animate__faster
                                    `
                            }
                        });
                    },
                    error: function(xhr) {
                        // Swal.fire({
                        //     title: "<strong>Hotel Management Notifications</strong>",
                        //     html: `<strong style='color:red'>${xhr?.responseJSON?.message ?? 'Errors occurs!'}</strong>`,
                        //     timer: 2000,
                        //     position: 'top-end',
                        //     showConfirmButton: false,
                        //     timerProgressBar: true,
                        //     showClass: {
                        //         popup: `
                        //             animate__animated
                        //             animate__fadeInUp
                        //             animate__faster
                        //             `
                        //     },
                        //     hideClass: {
                        //         popup: `
                        //             animate__animated
                        //             animate__fadeOutDown
                        //             animate__faster
                        //             `
                        //     }
                        // });
                    }
                });
        });
    });
</script>
@stop
