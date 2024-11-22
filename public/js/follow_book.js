$(document).ready(function() {
    var dataTable = $('#data_table').DataTable({
        lengthMenu: [
            [20, 50, 100, -1],
            [20, 50, 100, 'ทั้งหมด']
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json',
        },
    });
});
