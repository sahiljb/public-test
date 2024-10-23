<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{$title}} 
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link rel="stylesheet" href="{{asset('plugins/table/datatable/datatables.css')}}">
        @vite(['resources/scss/light/plugins/table/datatable/dt-global_style.scss'])
        @vite(['resources/scss/light/assets/apps/invoice-list.scss'])
        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a >{{$title}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$breadcrumb}}</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->
    
    <div class="row" id="cancel-row">
                    
        <div class="col-xl-12 col-lg-12 col-sm-12 layout-top-spacing layout-spacing">

        <x-alert-component type="error" />
            <x-alert-component type="success" />


            <div class="widget-content widget-content-area br-8">


                <table id="table-list" class="table dt-table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>#Id</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody> <!-- This will be populated by DataTables -->
                </table>
            </div>
        </div>

    </div>
    
    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>
        <script src="{{asset('plugins/global/vendors.min.js')}}"></script>
        <script src="{{asset('plugins/table/datatable/datatables.js')}}"></script>
        <script src="{{asset('plugins/table/datatable/button-ext/dataTables.buttons.min.js')}}"></script>

        <script>

$(document).ready(function() {
    var tableList = $('#table-list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('customer.list', ['role' => $roleName]) }}",
            "data": function (d) {
                d.length = d.length || 10;
                d.order = d.order || [];
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "name", "render": function(data, type, row) {
                if (data) {
                    var profileImage = row.profile ? window.location.origin + '/storage/' + row.profile : '{{ url('images/avatar.png') }}';                    
                    return '<div class="d-flex"><div class="usr-img-frame me-2 rounded-circle"><img alt="avatar" class="img-fluid rounded-circle" src="' + profileImage + '"></div><p class="align-self-center mb-0 user-name">' + data + ' <br> ' + row.email + '</p></div>';
                } else {
                    return '';
                }
            }, "orderable": false },
            { "data": "phone", "orderable": false },
            { "data": "status", "render": function(data, type ,row){

                let classStatusName = data === 'active' ? 'badge badge-light-success' : 'badge badge-light-danger';
                return '<div class="'+classStatusName+'"><p class="align-self-center mb-0 user-name">' + data + '</p></div>';
            
            }, "orderable": false },
            { "data": "created_at", "render": function(data, type, row) {
                var date = new Date(data);
                var options = { day: 'numeric', month: 'short', year: 'numeric' };
                return '<span class="inv-date">' + date.toLocaleDateString('en-GB', options) + '</span>';
            }, "orderable": false },
            { "data": "id", "render": function(data, type, row) {

                var baseUrlEdit = "{{ route('customer.update', ['id']) }}";
                var editUrl = baseUrlEdit.replace('id', row.id);

                return '<a class="badge badge-light-primary text-start me-2 bs-tooltip action-edit" href="' + editUrl + '" data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></a>' +
                    '<a class="badge badge-light-danger text-start bs-tooltip action-delete" href="javascript:void(0);" data-id="' + row.id + '" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>';
            }, "orderable": false },
        ],
        "dom": "<'inv-list-top-section'<'row'<'col-sm-12 col-md-6 d-flex justify-content-md-start justify-content-center'<'dt-action-buttons align-self-center'B>><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-3'f<'toolbar align-self-center'>>>>" +
               "<'table-responsive'tr>" +
               "<'inv-list-bottom-section d-sm-flex justify-content-sm-between text-center'<'inv-list-pages-count mb-sm-0 mb-3'i><'inv-list-pagination'p>>",
        "buttons": [
            {
                text: 'Add New',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    window.location.href = "{{ route('customer.create') }}"; // Add your route here
                }
            }
        ],
        "order": [],
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
            "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [7, 10, 20, 50],
        "pageLength": 10
    });

    // Handle delete button click event
    $('#table-list').on('click', '.action-delete', function() {
        var userId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {

                var baseUrlDelete = "{{ route('customer.delete', ['id']) }}";
                var DeleteUrl = baseUrlDelete.replace('id', userId);

                $.ajax({
                    url: DeleteUrl,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                'User has been deleted.',
                                'success'
                            );
                            tableList.ajax.reload(); // Refresh the DataTable
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the user.',
                                'error'
                            );
                        }
                    },
                    error: function(response) {
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the user.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});


       
        </script>


    </x-slot>
    <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>