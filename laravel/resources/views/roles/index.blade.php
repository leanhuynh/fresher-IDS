@extends('layouts.app')

@section('content')
<div class="container">
    <h5 class="mb-4 mt-2"><strong>Role</strong></h5>
    
    <!-- Thanh tìm kiếm -->
    <form action="{{ route('users.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by name"
                id="search_input"
            >
            <button id="search_btn" type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5><strong>List of Roles</strong></h5>
        <button class="btn btn-success" onclick="window.location.href='/roles/create'"     @if($roles->count() == 2) disabled @endif>
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
                <td style="display:flex; gap:5px">
                    <button class="btn btn-warning btn-sm" style="flex: 1;" onclick="window.location.href='/roles/edit/{{$role->id}}'">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-danger btn-sm" style="flex: 1;" data-id="{{$role->id}}">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
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
        $(document).on('click', '.btn.btn-danger', function(event) {
            event.preventDefault();

            var roleId = $(this).data('id');
            var row = $('#tr-' + roleId);

            // check to sure admin wants to delete this user
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete the role!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('auth_id', {{ auth()->id() }});
                        formData.append('_method', 'DELETE');

                        // send request to delete user
                        $.ajax({
                            url: `/api/roles/${roleId}`,
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                row.remove();
                                customAlert(response?.message ?? 'Role deleted successfully', 'success');
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr?.responseJSON?.message ?? 'Error occurred while deleting role');
                                customAlert(xhr?.responseJSON?.message ?? 'Error occurred while deleting role', 'error');
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
                searchRoles(createUrlFromFilter());
            }
        });

        // SEARCH HOTELS
        $('#search_btn').on('click', function(event) {
            event.preventDefault();
            searchRoles(createUrlFromFilter());
        });

        // Search hotels when pagination link is clicked 
        $(document).on("click", ".pagination a", function (event) {
            event.preventDefault(); // Ngăn chặn load lại trang
            var url = $(this).attr("href"); // Lấy URL của trang mới
            searchRoles(url);
        });

        // Create URL from filter
        function createUrlFromFilter() {
            const values = {
                keyword: $('#search_input').val()
            };

            // Convert values to query parameters
            return '/api/roles/search?' + new URLSearchParams(values).toString();
        }

        // Search roles
        function searchRoles(url) {
            // Send AJAX request
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    const startIndex = (response.roles.current_page - 1) * response.roles.per_page + 1;
                    updateRoleTable(startIndex, response.roles.data);
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

        // Update hotel table
        function updateRoleTable(startIndex, roles) {
            const tableBody = $('table tbody');
            tableBody.empty();

            if (roles.length > 0) {
                roles.forEach((role, index) => {
                    var row = `
                        <tr style="cursor: pointer; object-fit: cover;" ondblclick="window.location.href='/roles/edit/${role.id}'">
                            <td>${startIndex + index}</td>
                            <td>${role.id}</td>
                            <td>${role.name || ''}</td>
                            <td>${role.description || ''}</td>
                            <td style="display:flex; gap:5px">
                                <button class="btn btn-warning btn-sm" style="flex: 1;" onclick="window.location.href='/roles/edit/${role.id}'">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm" style="flex: 1;" data-id="${role.id}" onclick="deleteRole(${role.id})">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </td>
                        </tr>
                    `;
                    tableBody.append(row);
                });
            } else {
                const noRolesRow = `
                    <tr>
                        <td colspan="5" style="text-align: center">No roles found</td>
                    </tr>
                `;
                tableBody.append(noRolesRow);
            }
        }
    });
</script>
@endsection