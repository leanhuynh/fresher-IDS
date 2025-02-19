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
                <table class="table">
                    <tbody>
                        <tr><th>City</th><td>{{ $hotel->city->name ?? 'N/A' }}</td></tr>
                        <tr><th>Hotel Code</th><td>{{ $hotel->hotel_code ?? 'N/A' }}</td></tr>
                        <tr>
                            <th>Hotel Name (EN)</th>
                            <td>{{ $hotel->name_en ?? 'N/A' }}</td>
                            <th>Hotel Name (JP)</th>
                            <td>{{ $hotel->name_jp ?? 'N/A' }}</td>
                        </tr>
                        <tr><th>Email</th><td>{{ $hotel->email ?? 'N/A' }}</td></tr>
                        <tr>
                            <th>Telephone</th>
                            <td>{{ $hotel->telephone ?? 'N/A' }}</td>
                            <th>Fax</th>
                            <td>{{ $hotel->fax ?? 'N/A' }}</td>
                        </tr>
                        <tr><th>Company Name</th><td>{{ $hotel->company_name ?? 'N/A' }}</td></tr>
                        <tr><th>Address 1</th><td>{{ $hotel->address_1 ?? 'N/A' }}</td></tr>
                        <tr><th>Address 2</th><td>{{ $hotel->address_2 ?? 'N/A' }}</td></tr>
                        <tr><th>Tax Code</th><td>{{ $hotel->tax_code ?? 'N/A' }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-5 text-center">
                <button id="backBtn" class="btn btn-primary profile-button" type="button" onclick="window.location.href='/hotels'">Back</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')
@parent
<!-- AJAX -->
<script>
</script>
@endsection