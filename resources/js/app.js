import Alpine from 'alpinejs';
import 'bootstrap';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

// Global tracker for last clicked element to support synchronous confirm overrides
let lastClickedElement = null;
document.addEventListener('click', (e) => {
    lastClickedElement = e.target;
}, true);

// Override native window.alert with SweetAlert2
window.alert = function (message) {
    Swal.fire({
        title: 'Information',
        text: message,
        icon: 'info',
        confirmButtonText: 'OK',
        customClass: {
            popup: 'rounded-3xl border border-gray-200/50 shadow-2xl p-6 font-sans',
            title: 'text-lg font-bold text-gray-900',
            htmlContainer: 'text-sm text-gray-500 mt-2',
            confirmButton: 'inline-flex justify-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-500/20 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2'
        },
        buttonsStyling: false
    });
};

// Override native window.confirm with SweetAlert2
window.confirm = function (message) {
    const triggerEl = lastClickedElement;
    
    Swal.fire({
        title: 'Confirmation',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-3xl border border-gray-200/50 shadow-2xl p-6 font-sans',
            title: 'text-lg font-bold text-gray-900',
            htmlContainer: 'text-sm text-gray-500 mt-2',
            confirmButton: 'inline-flex justify-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-red-500/20 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 ml-3',
            cancelButton: 'inline-flex justify-center px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed && triggerEl) {
            // Find parent form or link and trigger action
            const form = triggerEl.closest('form');
            const link = triggerEl.closest('a');
            
            if (form) {
                form.dataset.confirmed = 'true';
                form.submit();
            } else if (link && link.href) {
                window.location.href = link.href;
            }
        }
    });

    return false; // Prevent default synchronous execution
};

// Intercept form submits and flash messages once DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // 1. Intercept forms with data-confirm
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (form.hasAttribute('data-confirm')) {
            if (form.dataset.confirmed) {
                return;
            }

            e.preventDefault();
            const message = form.getAttribute('data-confirm');
            
            Swal.fire({
                title: 'Confirmation',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl border border-gray-200/50 shadow-2xl p-6 font-sans',
                    title: 'text-lg font-bold text-gray-900',
                    htmlContainer: 'text-sm text-gray-500 mt-2',
                    confirmButton: 'inline-flex justify-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-red-500/20 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 ml-3',
                    cancelButton: 'inline-flex justify-center px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.confirmed = 'true';
                    form.submit();
                }
            });
        }
    });

    // 2. Process body flash attributes
    const body = document.body;
    if (body) {
        const success = body.getAttribute('data-flash-success');
        const error = body.getAttribute('data-flash-error');

        if (success || error) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            if (success) {
                Toast.fire({
                    icon: 'success',
                    title: success
                });
            } else if (error) {
                Toast.fire({
                    icon: 'error',
                    title: error
                });
            }
        }
    }
});

Alpine.start();

