<!-- SweetAlert2 notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="application/json" id="flash-notifications">{!! json_encode([
    'success' => session('status'),
    'error' => session('error'),
    'validation' => $errors->all(),
]) !!}</script>
<script>
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

function notify(type, message, title) {
    const iconMap = { success: 'success', error: 'error', warning: 'warning', info: 'info' };
    Toast.fire({
        icon: iconMap[type] || 'info',
        title: title || message,
        text: title ? message : undefined,
        color: '#0f172a',
    });
}

const flashData = JSON.parse(document.getElementById('flash-notifications').textContent || '{}');
if (flashData.success) notify('success', flashData.success);
if (flashData.error) notify('error', flashData.error);
(flashData.validation || []).forEach(msg => notify('error', msg));
</script>
