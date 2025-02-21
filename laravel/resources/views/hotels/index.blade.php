@extends('layouts.app')

@section('content')
<div class="container">
    <h5 class="mb-4 mt-2"><strong>Hotel</strong></h5>

    <form action="/hotels" method="GET">
        <div class="mb-5 d-flex gap-3 align-items-end">
            <div class="flex-grow-1">
                <label for="city_id" class="form-label">City</label>
                <select class="form-select" id="city_id" name="city_id">
                    <option value="">--All--</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-grow-1">
                <label for="hotel_code" class="form-label">Hotel Code</label>
                <input type="text" class="form-control" id="hotel_code" name="hotel_code" value="{{ request('hotel_code') }}" placeholder="Enter hotel code">
            </div>
            <div class="flex-grow-1">
                <label for="name_en" class="form-label">Hotel Name (EN)</label>
                <input type="text" class="form-control" id="name_en" name="name_en" value="{{ request('name_en') }}" placeholder="Enter hotel name">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> SEARCH
            </button>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5><strong>List of Hotels</strong></h5>
        <button class="btn btn-success" onclick="window.location.href='/hotels/create'">
            <i class="fas fa-hotel"></i> Add New Hotel
        </button>
    </div>

    <!-- list of hotels -->
    @if($hotels->isNotEmpty())
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No.</th>
                <th>City</th>
                <th>Owner</th>
                <th>Hotel Code</th>
                <th>Hotel Name (EN)</th>
                <th>Email</th>
                <th>Telephone</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hotels as $index => $hotel)
                <tr id="tr-{{$hotel->id}}" style="cursor: pointer; object-fit: cover;" ondblclick="window.location.href='/hotels/edit/{{$hotel->id}}'">
                    <td>{{$hotels->firstItem() + $index}}</td>
                    <td>{{$hotel->city->name}}</td>
                    <td>{{$hotel->user->user_name}}</td>
                    <td>
                        <span class="badge bg-success">{{$hotel->hotel_code}}</span>
                    </td>
                    <td>{{$hotel->name_en}}</td>
                    <td>{{$hotel->email}}</td>
                    <td>{{$hotel->telephone}}</td>
                    <td>
                        <div class="d-flex flex-column" style="gap: 5px">
                            <span class="w-100">{{$hotel->address_1}}</span>
                            <span class="w-100">{{$hotel->address_2}}</span>
                        </div>
                    </td>
                    <td class="d-grid gap-1">
                        <!-- View Button -->
                        <button class="btn btn-info btn-sm w-100" onclick="window.location.href='/hotels/view/{{$hotel->id}}'">
                            <i class="fas fa-eye"></i> View
                        </button>

                        <!-- Edit Button -->
                        <button class="btn btn-warning btn-sm w-100" onclick="window.location.href='/hotels/edit/{{$hotel->id}}'">
                            <i class="fas fa-edit"></i> Edit
                        </button>

                        <!-- Delete Button with Confirmation -->
                        <form id="delete-form-{{ $hotel->id }}" action="/hotels/delete/{{$hotel->id}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100 delete-btn" data-id="{{ $hotel->id }}">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $hotels->links('vendor.pagination.custom') }}
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>City</th>
                    <th>Hotel Code</th>
                    <th>Hotel Name (EN)</th>
                    <th>Email</th>
                    <th>Telephone</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="8" style="text-align: center">No hotels found</td>
                </tr>
            </tbody>
        </table>
    @endif
</div>
@endsection

@section('js')
@parent
<script>

    $(document).ready(function() {

        // DELETE ROLE
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                let hotelId = this.getAttribute('data-id');
                let form = document.getElementById('delete-form-' + hotelId);

                // Hiển thị SweetAlert xác nhận
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete the hotel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Gửi form nếu xác nhận
                    }
                });
            });
        });

        // Trigger search on Enter key press
        $('#city_id, #hotel_code, #name_en').on('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Ngăn chặn reload mặc định
                this.closest("form").submit(); // Gửi form tìm kiếm
            }
        });
    });
    
</script>
@endsection