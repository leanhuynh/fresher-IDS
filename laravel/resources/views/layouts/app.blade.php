@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // function to show alert by using sweetalert2
    function customAlert(message, type) {
        // type: error, warning, info, success
        var color = 'green';
        if (type == 'error') {
            color = 'red';
        } else if (type == 'warning') {
            color = 'orange';
        } else if (type == 'info') {
            color = 'blue';
        } else if (type == 'success') {
            color = 'green';
        }

        // alert message
        Swal.fire({
            title: "<strong>Hotel Management Notifications</strong>",
            html: `<strong style='color:${color}'>${message}</strong>`,
            timer: 2000,
            position: 'top-end',
            showConfirmButton: false,
            timerProgressBar: true,
        });
    }

    // session alert
    function sessionAlert() {
        // alert message
        if ('{{session('success')}}') {
            customAlert('{{session('success')}}', 'success');
        }
        if ('{{session('error')}}') {
            customAlert('{{session('error')}}', 'error');
        }
        // if ('{{session('warning')}}') {
        //     customAlert('{{session('warning')}}', 'warning');
        // }
        // if ('{{session('info')}}') {
        //     customAlert('{{session('info')}}', 'info');
        // }
    }

    $(document).ready(function() {
        sessionAlert();
    });
</script>
@endsection

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endsection

@section('content')
    
@endsection