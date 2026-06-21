// script.js - JavaScript Standar

$(document).ready(function() {
    
    // Toggle Sidebar untuk Mobile
    $('#sidebarToggle').click(function() {
        $('#sidebar').toggleClass('show');
    });

    // Inisialisasi DataTables dasar (jika ada class datatable)
    if ($.fn.DataTable) {
        $('.datatable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: ">>",
                    previous: "<<"
                },
                zeroRecords: "Data tidak ditemukan",
                emptyTable: "Belum ada data"
            }
        });
    }

    // Konfirmasi hapus
    $('.btn-delete').click(function() {
        return confirm('Yakin ingin menghapus data ini?');
    });

    // Preview gambar sederhana saat form upload
    $('#gambarInput').change(function() {
        const file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $('#imgPreview').attr('src', event.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });

});
