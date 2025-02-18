@extends('layouts.app')

@section('content')
<div class="container">
    <h5 class="mb-4 mt-2"><strong>Hotel</strong></h5>

    <div class="mb-5 d-flex gap-3 align-items-end">
        <div class="flex-grow-1">
            <label for="city" class="form-label">City</label>
            <select class="form-select" id="city_id" name="city_id">
                <option value="">--Select City--</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-grow-1">
            <label for="hotel_code" class="form-label">Hotel Code</label>
            <input type="text" class="form-control" id="hotel_code" placeholder="Enter hotel code">
        </div>
        <div class="flex-grow-1">
            <label for="name_en" class="form-label">Hotel Name (EN)</label>
            <input type="text" class="form-control" id="name_en" placeholder="Enter hotel name">
        </div>
        <button id="searchBtn" class="btn btn-primary">
            <i class="fas fa-search"></i> SEARCH
        </button>
    </div>

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
                    <td class="d-flex flex-column gap-1">
                        <button class="btn btn-info btn-sm" onclick="window.location.href='/hotels/view/{{$hotel->id}}'">
                            <i class="fas fa-eye"></i> View
                        </button>

                        <button class="btn btn-warning btn-sm" onclick="window.location.href='/hotels/edit/{{$hotel->id}}'">
                            <i class="fas fa-edit"></i> Edit
                        </button>

                        <button class="btn btn-danger btn-sm" data-id="{{$hotel->id}}">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>

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

        // DELETE USER
        $(document).on('click', '.btn.btn-danger', function(event) {
            event.preventDefault();

            var hotelId = $(this).data('id');
            var row = $('#tr-' + hotelId);

            // check to sure admin wants to delete this user
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // create form data
                        var formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('owner_id', '{{ auth()->id() }}');
                        formData.append('_method', 'DELETE');

                        // send request to delete hotel
                        $.ajax({
                            url: `/api/hotels/${hotelId}`,
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                row.remove();
                                customAlert(response?.message ?? 'Hotel deleted successfully!!', 'success');
                            },
                            error: function(xhr, status, error) {
                                customAlert(xhr?.responseJSON?.message ?? 'Something went wrong!!', 'error');
                            }
                        });
                    }
                }
            );
        });

        // Trigger search on Enter key press
        $('#city_id, #hotel_code, #name_en').on('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchHotels(createUrlFromFilter());
            }
        });

        // SEARCH HOTELS
        $('#searchBtn').on('click', function(event) {
            event.preventDefault();
            searchHotels(createUrlFromFilter());
        });

        // Search hotels when pagination link is clicked 
        $(document).on("click", ".pagination a", function (event) {
            event.preventDefault(); // Ngăn chặn load lại trang
            var url = $(this).attr("href"); // Lấy URL của trang mới
            searchHotels(url);
        });
        
        // Create URL from filter
        function createUrlFromFilter() {
            const values = {
                city_id: $('#city_id').val(),
                hotel_code: $('#hotel_code').val(),
                name_en: $('#name_en').val(),
                owner_id: {{ auth()->id() }}
            };

            // Convert values to query parameters
            return '/api/hotels/search?' + new URLSearchParams(values).toString();
        }

        // Search hotels
        function searchHotels(url) {

            // Example: AJAX request to search hotels
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    const startIndex = (response.hotels.current_page - 1) * response.hotels.per_page + 1;
                    updateHotelTable(startIndex, response.hotels.data);
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
        function updateHotelTable(startIndex, hotels) {
            const tableBody = $('table tbody');
            tableBody.empty();

            if (hotels.length > 0) {

                hotels.forEach((hotel, index) => {
                    var row = `
                        <tr ondblclick="window.location.href='/hotels/edit/${hotel.id}'">
                            <td>${startIndex + index}</td>
                            <td>${hotel.city.name || ''}</td>
                            <td><span class="badge bg-success">${hotel.hotel_code || ''}</span></td>
                            <td>${hotel.name_en || ''}</td>
                            <td>${hotel.email || ''}</td>
                            <td>${hotel.telephone || ''}</td>
                            <td>
                                <div class="d-flex flex-column" style="gap: 5px">
                                    <span class="w-100">${hotel.address_1 || ''}</span>
                                    <span class="w-100">${hotel.address_2 || ''}</span>
                                </div>
                            </td>
                            <td class="d-flex flex-column gap-1">
                                <button class="btn btn-info btn-sm" onclick="window.location.href='/hotels/view/${hotel.id}'">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="window.location.href='/hotels/edit/${hotel.id}'">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </td>
                        </tr>
                    `;
                    tableBody.append(row);
                });
            } else {
                const noHotelsRow = `
                    <tr>
                        <td colspan="8" style="text-align: center">No hotels found</td>
                    </tr>
                `;
                tableBody.append(noHotelsRow);
            }
        }
    });
    
</script>
@endsection