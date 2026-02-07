@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 1800,
        timerProgressBar: true,
        position: 'center',
        backdrop: true,
    });


});
</script>
@endif

