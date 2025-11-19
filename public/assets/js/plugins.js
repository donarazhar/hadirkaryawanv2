/**
 * Plugins & Utilities
 * Sistem Presensi YPI Al Azhar
 */

(function($) {
    'use strict';

    // ===== BACK BUTTON HANDLER =====
    $(document).on('click', '.goBack', function(e) {
        e.preventDefault();
        window.history.back();
    });

    // ===== TOAST NOTIFICATION =====
    window.showToast = function(message, type = 'success') {
        const iconMap = {
            success: 'checkmark-circle',
            error: 'close-circle',
            warning: 'warning',
            info: 'information-circle'
        };

        const colorMap = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#0053C5'
        };

        const toast = $(`
            <div class="custom-toast" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                padding: 16px 20px;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: flex;
                align-items: center;
                gap: 12px;
                z-index: 9999;
                min-width: 250px;
                animation: slideInRight 0.3s ease;
                border-left: 4px solid ${colorMap[type]};
            ">
                <ion-icon name="${iconMap[type]}" style="
                    font-size: 24px;
                    color: ${colorMap[type]};
                "></ion-icon>
                <div style="flex: 1; font-size: 14px; font-weight: 500; color: #1e293b;">
                    ${message}
                </div>
            </div>
        `);

        $('body').append(toast);

        setTimeout(() => {
            toast.css({
                animation: 'slideOutRight 0.3s ease',
                opacity: 0,
                transform: 'translateX(400px)'
            });
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    };

    // ===== LOADING OVERLAY =====
    window.showLoading = function(message = 'Loading...') {
        const loading = $(`
            <div class="loading-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
            ">
                <div style="
                    background: white;
                    padding: 30px;
                    border-radius: 20px;
                    text-align: center;
                    min-width: 200px;
                ">
                    <div class="spinner-border text-primary" role="status" style="
                        width: 50px;
                        height: 50px;
                        border-width: 4px;
                    "></div>
                    <p style="margin-top: 15px; margin-bottom: 0; color: #1e293b; font-weight: 600;">
                        ${message}
                    </p>
                </div>
            </div>
        `);
        $('body').append(loading);
    };

    window.hideLoading = function() {
        $('.loading-overlay').fadeOut(300, function() {
            $(this).remove();
        });
    };

    // ===== CONFIRM DELETE =====
    window.confirmDelete = function(callback) {
        Swal.fire({
            title: 'Hapus Data?',
            text: 'Data yang dihapus tidak dapat dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed && typeof callback === 'function') {
                callback();
            }
        });
    };

    // ===== FORMAT RUPIAH =====
    window.formatRupiah = function(angka, prefix = 'Rp ') {
        const number_string = angka.toString().replace(/[^,\d]/g, '');
        const split = number_string.split(',');
        const sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            const separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix + rupiah;
    };

    // ===== FORMAT DATE =====
    window.formatDate = function(date, format = 'DD MMM YYYY') {
        const d = new Date(date);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        const day = String(d.getDate()).padStart(2, '0');
        const month = months[d.getMonth()];
        const year = d.getFullYear();
        const dayName = days[d.getDay()];

        return format
            .replace('DD', day)
            .replace('MMM', month)
            .replace('YYYY', year)
            .replace('dddd', dayName);
    };

    // ===== COPY TO CLIPBOARD =====
    window.copyToClipboard = function(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = 0;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        
        showToast('Berhasil disalin!', 'success');
    };

    // ===== BOOTSTRAP TOOLTIP =====
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // ===== BOOTSTRAP POPOVER =====
    if (typeof bootstrap !== 'undefined' && bootstrap.Popover) {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // ===== AUTO DISMISS ALERTS =====
    $('.alert').each(function() {
        const alert = $(this);
        setTimeout(function() {
            alert.fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    });

    // ===== PREVENT DOUBLE SUBMIT =====
    $('form').on('submit', function() {
        const form = $(this);
        const submitBtn = form.find('[type="submit"]');
        
        if (form.data('submitting') === true) {
            return false;
        }
        
        form.data('submitting', true);
        submitBtn.prop('disabled', true);
        
        setTimeout(function() {
            form.data('submitting', false);
            submitBtn.prop('disabled', false);
        }, 3000);
    });

    // ===== SMOOTH SCROLL =====
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 70
            }, 500);
        }
    });

    // ===== IMAGE PREVIEW =====
    window.previewImage = function(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result).show();
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    // ===== ACTIVE MENU HIGHLIGHT =====
    const currentPath = window.location.pathname;
    $('.nav-link, .menu-item a').each(function() {
        const href = $(this).attr('href');
        if (href && currentPath.includes(href) && href !== '/') {
            $(this).addClass('active');
            $(this).parents('.nav-item').addClass('active');
        }
    });

    // ===== AJAX SETUP =====
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ===== CONSOLE LOG CLEANER (Production) =====
    if (window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
        console.log = function() {};
        console.warn = function() {};
        console.error = function() {};
    }

    // ===== PRINT FUNCTION =====
    window.printDiv = function(divId) {
        const content = document.getElementById(divId).innerHTML;
        const printWindow = window.open('', '', 'height=600,width=800');
        
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<link rel="stylesheet" href="/assets/css/style.css">');
        printWindow.document.write('</head><body>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        
        printWindow.document.close();
        printWindow.focus();
        
        setTimeout(function() {
            printWindow.print();
            printWindow.close();
        }, 500);
    };

    // ===== NUMERIC INPUT ONLY =====
    $('input[type="number"], input.numeric-only').on('keypress', function(e) {
        const charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46) {
            e.preventDefault();
        }
    });

    // ===== AUTO UPPERCASE =====
    $('input.uppercase').on('input', function() {
        this.value = this.value.toUpperCase();
    });

    // ===== AUTO FORMAT PHONE =====
    $('input[type="tel"], input.phone-format').on('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.startsWith('0')) {
            this.value = value;
        } else if (value.startsWith('62')) {
            this.value = '0' + value.substring(2);
        }
    });

    // ===== SESSION FLASH MESSAGE =====
    @if(session('success'))
        showToast("{{ session('success') }}", 'success');
    @endif

    @if(session('error'))
        showToast("{{ session('error') }}", 'error');
    @endif

    @if(session('warning'))
        showToast("{{ session('warning') }}", 'warning');
    @endif

    @if(session('info'))
        showToast("{{ session('info') }}", 'info');
    @endif

    // ===== INIT COMPLETE =====
    console.log('%c✓ Plugins.js loaded', 'color: #10b981; font-weight: bold;');

})(jQuery);

// ===== CSS ANIMATIONS =====
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(400px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(400px);
        }
    }

    .fade-in {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
`;
document.head.appendChild(style);