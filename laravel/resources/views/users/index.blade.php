@extends('layouts.app')

@section('css')

@stop

@section('content')
<div class="container">
    <h5 class="mb-4 mt-2"><strong>User</strong></h5>

    <!-- Thanh tìm kiếm -->
    <form method="GET" action="/users" class="mb-4">
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
        <h5><strong>List of Users</strong></h5>
        <button class="btn btn-success" onclick="window.location.href='/users/create'">
            <i class="fas fa-user-plus"></i> Add New User
        </button>
    </div>

    <!-- list of hotels -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <thead class="table-dark">
            <tr>
                <th>No.</th>
                <th>Avatar</th>
                <th>User name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr style="cursor: pointer; object-fit: cover;" ondblclick="window.location.href='{{ $user->id == auth()->id() ? '/users/profile' : '/users/edit/' . $user->id }}'" id='tr-{{$user->id}}'>
                <td class="text-center">{{ $users->firstItem() + $index }}</td>
                @if ($user->avatar)
                    <td><img src="{{ asset('storage/' . $user->avatar) }}" alt="Uploaded Image" class="rounded-circle" width="90px" /></td>
                @else 
                    <td>No Image</td>
                @endif
                <td>{{$user->user_name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->address}}</td>
                <td>{{$user->role->name}}</td>
                <td>
                    <div class="d-flex flex-column" style="gap: 5px">
                        <button class="btn btn-warning btn-sm" onclick="window.location.href='{{ $user->id == auth()->id() ? '/users/profile' : '/users/edit/' . $user->id }}'">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <!-- Form Delete -->
                        <form id="delete-form-{{ $user->id }}" action="/users/delete/{{ $user->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm w-100 delete-btn" data-id="{{ $user->id }}">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links('vendor.pagination.custom') }}
</div>

@endsection

@section('js')
@parent
<script>
    $(document).ready(function() {

        // DELETE USER
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
                    confirmButtonText: 'Yes, delete it!'
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
@endSection