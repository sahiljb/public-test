<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{$title}} 
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/tomSelect/tom-select.default.min.css')}}">
        @vite(['resources/scss/light/plugins/tomSelect/custom-tomSelect.scss'])
        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>
    
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{$breadcrumb_url}}">{{$breadcrumb}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    
    <form action="{{ route('leads.process-assign') }}" method="POST" id="assign-leads-form">
    @csrf
    <div class="row" id="cancel-row">

    <div class="col-xl-6 col-md-6 mt-4 col-sm-6 mb-2 col-6">
        <select id="select-beast" name="employee_id" placeholder="Select a staff to assign lead..." autocomplete="off">
            <option value="">Select a staff to assign lead...</option>
            @foreach ($allEmployee as $employeeDetail)
                @php
                    // Determine profile image path
                    $profilePath = $employeeDetail->profile == '' ? url('images/avatar.png') : url('storage/'.$employeeDetail->profile);
                @endphp
                <option value="{{$employeeDetail->id}}" @if(old('employee_id') == $employeeDetail->id) selected @endif data-image="{{$profilePath}}">{{$employeeDetail->name}}</option>
            @endforeach
        </select>

    </div> 

    <x-alert-component type="error" />
    <x-alert-component type="success" />
       
    <div class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <h4>{{$title}}</h4>
                </div>

                <div class="widget-content widget-content-area">


                        <table id="table-list" class="table dt-table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>
                                    <div class="form-check form-check-info form-check-inline">
                                        <input class="form-check-input check-all" type="checkbox" value="" id="form-check-info" {{ old('lead_ids') && count(old('lead_ids')) === count($allColdLeads) ? 'checked' : '' }}>
                                    </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>City</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach ($allColdLeads as $index => $leadDetail)

                                <tr>
                                    <td>
                                    <div class="form-check form-check-success form-check-inline">
                                        <input class="form-check-input lead-checkbox" name="lead_ids[]" type="checkbox" value="{{$leadDetail->id}}" id="form-check-success_{{$index}}" {{ in_array($leadDetail->id, old('lead_ids', [])) ? 'checked' : '' }}>
                                    </div>
                                    </td>
                                    <td>#{{$leadDetail->id}}</td>
                                    <td>{{$leadDetail->name}}</td>
                                    <td>{{$leadDetail->phone}}</td>
                                    <td>{{$leadDetail->city}}</td>
                                    <td>
                                        @if($leadDetail->priority == 'normal')
                                            <span class="badge badge-light-dark mb-2 me-4">Normal</span>
                                        @elseif($leadDetail->priority == 'moderate')
                                            <span class="badge badge-light-info mb-2 me-4">Moderate</span>
                                        @elseif($leadDetail->priority == 'hot')
                                            <span class="badge badge-light-danger mb-2 me-4">Hot</span>
                                        @endif
                                    </td>
                                </tr>
                            
                            @endforeach

                            </tbody> 
                        </table>

                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <button type="submit" class="btn btn-primary">Assign Selected Leads</button>
        </div>

    </div>
    </form>
    
    
    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>
       
        <script src="{{asset('plugins/tomSelect/tom-select.base.js')}}"></script>
        <script src="{{asset('plugins/tomSelect/custom-tom-select.js')}}"></script>

        <script>
            new TomSelect("#select-beast", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            render: {
                option: function(data, escape) {
                    // Custom option rendering with image
                    return '<div>' +
                        '<img class="img-fluid rounded-circle" src="' + escape(data.image) + '" width="40" height="40" style="margin-right: 10px;" />' +
                        '<span>' + escape(data.text) + '</span>' +
                    '</div>';
                },
                item: function(data, escape) {
                    // Custom selected item rendering with image
                    return '<div>' +
                        '<img class="img-fluid rounded-circle" src="' + escape(data.image) + '" width="40" height="40" style="margin-right: 10px;" />' +
                        '<span>' + escape(data.text) + '</span>' +
                    '</div>';
                }
            }
        });


        document.querySelector('.check-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.lead-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        </script>



    </x-slot>
    <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>