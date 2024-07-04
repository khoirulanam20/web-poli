document.addEventListener("DOMContentLoaded", function() {
    // Ambil elemen dropdown obat
    var selectObat = document.getElementById('obat');

    // Tambahkan event listener untuk tombol checklist
    selectObat.addEventListener('click', function(e) {
        if (e.target.classList.contains('checklist-btn')) {
            e.preventDefault();
            var option = e.target.parentNode;
            option.selected = true;
        }
    });

    // Tambahkan event listener untuk tombol unchecklist
    selectObat.addEventListener('click', function(e) {
        if (e.target.classList.contains('unchecklist-btn')) {
            e.preventDefault();
            var option = e.target.parentNode;
            option.selected = false;
        }
    });
});
