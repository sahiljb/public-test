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
                <li class="breadcrumb-item"><a>{{$title}}</a></li>
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Action</th>
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
                
                let routeName = "{{$routeName}}";
                var tableList = $('#table-list').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": routeName,
                    "data": function(d) {
                        d.length = d.length || 10;
                        d.order = d.order || [];
                    }
                },
                "columns": [{
                        "data": "id",
                        "title": "ID"
                    },
                    {
                        "data": "name",
                        "title": "Name"
                    },
                    {
                        "data": "phone",
                        "title": "Phone"
                    },
                    {
                        "data": "city",
                        "title": "City"
                    },
                    {
                        "data": "id",
                        "render": function(data, type, row) {
                            var baseUrlEdit = "{{ route('leads.edit', ['id']) }}";
                            var editUrl = baseUrlEdit.replace('id', row.id);

                            return '<a class="badge badge-light-danger text-start bs-tooltip action-delete" href="javascript:void(0);" data-id="' + row.id + '" data-toggle="tooltip" data-placement="top" title="Delete">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">' +
                                '<polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>';
                        },
                        "orderable": false
                    }
                ],
                "dom": "<'inv-list-top-section'<'row'<'col-sm-12 col-md-6 d-flex justify-content-md-start justify-content-center'<'dt-action-buttons align-self-center'B>><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-3'f<'toolbar align-self-center'>>>>" +
                    "<'table-responsive'tr>" +
                    "<'inv-list-bottom-section d-sm-flex justify-content-sm-between text-center'<'inv-list-pages-count mb-sm-0 mb-3'i><'inv-list-pagination'p>>",
                "buttons": [],
                "order": [],
                "oLanguage": {
                    "oPaginate": {
                        "sPrevious": '...',
                        "sNext": '...'
                    },
                    "sInfo": "Showing page _PAGE_ of _PAGES_",
                    "sSearch": '<svg>...</svg>',
                    "sSearchPlaceholder": "Search...",
                    "sLengthMenu": "Results :  _MENU_",
                },
                "stripeClasses": [],
                "lengthMenu": [7, 10, 20, 50],
                "pageLength": 10,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var phoneData = api.column(2, { search: 'applied' }).data(); // Get all phone numbers
                    var phoneCount = {};
                    
                    // Count occurrences of each phone number
                    phoneData.each(function(phone) {
                        phoneCount[phone] = (phoneCount[phone] || 0) + 1;
                    });

                    // Loop through the rows and apply red background for duplicates
                    api.rows().every(function() {
                        var data = this.data();
                        if (phoneCount[data.phone] > 1) {
                            $(this.node()).css('background-color', '#ffd5d5'); // Apply red color for duplicate phones
                        }
                    });

                    // Reorder the rows by moving duplicate phone rows to the top
                    api.rows().nodes().sort(function(a, b) {
                        var phoneA = api.row(a).data().phone;
                        var phoneB = api.row(b).data().phone;
                        if (phoneCount[phoneA] > 1 && phoneCount[phoneB] > 1) {
                            return 0; // Both are duplicates, retain order
                        }
                        return phoneCount[phoneA] > 1 ? -1 : phoneCount[phoneB] > 1 ? 1 : 0; // Move duplicates to top
                    });
                }
            });

                // Handle delete button click event
                $('#table-list').on('click', '.action-delete', function() {
                    var itemid = $(this).data('id');
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

                            var baseUrlDelete = "{{ route('leads.duplicate.delete', ['id']) }}";
                            var DeleteUrl = baseUrlDelete.replace('id', itemid);

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
                                            response.message,
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