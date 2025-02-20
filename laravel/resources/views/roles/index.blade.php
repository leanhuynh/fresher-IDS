@extends('layouts.app')

@section('content')
<div class="container">
    <h5 class="mb-4 mt-2"><strong>Role</strong></h5>
    
    <!-- Thanh tìm kiếm -->
    <form method="GET" action="/roles" class="mb-4">
        <div class="input-group">
            <input
                type="text"
                name="keyword"
                class="form-control"
                placeholder="Search by name or email"
                id="search_input"
                value="{{ request('keyword') }}"
            >
            <button id="search_btn" type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5><strong>List of Roles</strong></h5>
        <button class="btn btn-success" onclick="window.location.href='/roles/create'" @if($roles->count() == 2) disabled @endif>
            <i class="fas fa-user-shield"></i> Add New Role
        </button>
    </div>

    <!-- list of hotels -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr class="w-fit">
                <th>No.</th>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr id="tr-{{$role->id}}" style="cursor: pointer; object-fit: cover;" ondblclick="window.location.href='/roles/edit/{{$role->id}}'">
                <td>{{$loop->iteration}}</td>
                <td>{{$role->id}}</td>
                <td>{{$role->name}}</td>
                <td>{{$role->description}}</td>
                <td>
                    <div style="display: flex; gap: 5px; width: 100%;">
                        <button class="btn btn-warning btn-sm" style="flex: 1;" onclick="window.location.href='/roles/edit/{{$role->id}}'">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        
                        <!-- Form Delete -->
                        <form id="delete-form-{{ $role->id }}" action="/roles/delete/{{ $role->id }}" method="POST" style="flex: 1;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm w-100 delete-btn" data-id="{{ $role->id }}">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('js')
@parent
<script>
    $(document).ready(function() {

        // DELETE ROLE
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                let userId = this.getAttribute('data-id');
                let form = document.getElementById('delete-form-' + userId);

                // Hiển thị SweetAlert xác nhận
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete the role!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Gửi form nếu xác nhận
                    }
                });
            });
        });

        // Trigger search on Enter key press
        $('#search_input').on('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Ngăn chặn reload mặc định
                this.closest("form").submit(); // Gửi form tìm kiếm
            }
        });
    });
</script>
@endsection