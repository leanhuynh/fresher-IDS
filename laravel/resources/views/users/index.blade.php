@extends('layouts.app')

@section('css')

@stop

@section('content')
<div class="container">
    <h5 class="mb-4 mt-2"><strong>User</strong></h5>

    <!-- Thanh tìm kiếm -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by name or email"
                id="search_input"
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
                        <button class="btn btn-danger btn-sm" data-id="{{$user->id}}">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
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
        $(document).on('click', '.btn.btn-danger', function(event) {
            event.preventDefault();

            var userId = $(this).data('id');
            var row = $('#tr-' + userId);

            // check to sure admin wants to delete this user
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete the user!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        // create form data
                        let formData = new FormData();
                        formData.append('auth_id', {{ auth()->id() }}); // get current user id
                        formData.append('_token', '{{ csrf_token() }}'); // get csrf token

                        // send request to delete user
                        $.ajax({
                            url: `/api/users/${userId}`,
                            method: 'DELETE',
                            success: function(response) {
                                row.remove();
                                customAlert(response?.message ?? 'Delete user successfully', 'success');
                            },
                            error: function(xhr, status, error) {
                                customAlert(xhr?.responseJSON?.message ?? 'Delete user failed', 'error');
                            }
                        });
                    }
                }
            );
        });

        // Trigger search on Enter key press
        $('#search_input').on('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchUsers(createUrlFromFilter());
            }
        });

        // Search users when pagination link is clicked 
        $(document).on("click", ".pagination a", function (event) {
            event.preventDefault(); // Ngăn chặn load lại trang
            var url = $(this).attr("href"); // Lấy URL của trang mới
            searchUsers(url);
        });

        // Search users
        $("#search_btn").click(function (event) {
            event.preventDefault(); // Ngăn form submit mặc định
            searchUsers(createUrlFromFilter());
        });

        // Create URL from filter
        function createUrlFromFilter() {
            const values = {
                keyword: $('#search_input').val()
            };

            // Convert values to query parameters
            return '/api/users/search?' + new URLSearchParams(values).toString();
        }

        // Update hotel table
        function updateUserTable(startIndex, users) {
            const tableBody = $('table tbody');
            tableBody.empty();

            if (users.length > 0) {
                users.forEach((user, index) => {
                    var row = `
                        <tr style="cursor: pointer; object-fit: cover;" ondblclick="window.location.href='/users/edit/${user.id}'" id="tr-${user.id}">
                            <td class="text-center">${startIndex + index}</td>
                            <td>
                                ${user.avatar 
                                    ? `<img src="/storage/${user.avatar}" alt="Uploaded Image" class="rounded-circle" width="90px" />` 
                                    : 'No Image'}
                            </td>
                            <td>${user.user_name || ''}</td>
                            <td>${user.email || ''}</td>
                            <td>${user.address || ''}</td>
                            <td>${user.role ? user.role.name : ''}</td>
                            <td>
                                <div class="d-flex flex-column" style="gap: 5px">
                                    <button class="btn btn-warning btn-sm" onclick="window.location.href='/users/edit/${user.id}'">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-id="${user.id}" onclick="deleteUser(${user.id})">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                    tableBody.append(row);
                });
            } else {
                const noUsersRow = `
                    <tr>
                        <td colspan="7" style="text-align: center">No users found</td>
                    </tr>
                `;
                tableBody.append(noUsersRow);
            }
        }


        // Search hotels
        function searchUsers(url) {

            // Example: AJAX request to search hotels
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    const startIndex = (response.users.current_page - 1) * response.users.per_page + 1;
                    updateUserTable(startIndex, response.users.data);
                    $("nav .pagination").html(response.paginationHtml); // Cập nhật phân trang
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "<strong>Hotel Management Notifications</strong>",
                        html: `<strong style='color:red'>${xhr.responseJSON.message}</strong>`,
                        timer: 2000,
                        position: 'top-end',
                        showConfirmButton: false,
                        timerProgressBar: true
                    });
                }
            });
        }
    });
    
</script>
@endSection