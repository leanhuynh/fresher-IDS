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
    <form id="formRole" action="/roles/create" method="POST" enctype="multipart/form-data">
        @csrf

        @if (session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif

        <div class="modal-header" style="background-color:black;">
            <h5 class="modal-title" style="color:white;">Role Information</h5>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="p-3 py-5">
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="labels">Role name <span style="color:red">*</span></label>
                                <select id="name" name="name" class="custom-select">
                                    <option value="" selected>--Select Role--</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->name}}" {{$role->isExisted ? "disabled" : ""}}>{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Description</label>
                                <input id="description" name="description" type="text" class="form-control" placeholder="Enter description">
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <button id="cancelBtn" class="btn btn-secondary profile-button" type="button">Cancel</button>
                            <button id="saveBtn" class="btn btn-primary profile-button" type="submit">Save Role</button>
                        </div>
                    </div>
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

        // CANCEL BUTTON
        $("#cancelBtn").click(function() {
            Swal.fire({
                title: "Hotel Management Alert",
                text: "Are you sure to cancel the new role creation process?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, back to list roles page!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/roles';
                    }
                });
        });
    });
</script>
@stop