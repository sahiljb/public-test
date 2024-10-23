<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{$title}} 
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!-- Add necessary styles if needed -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

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

    <div class="row" id="cancel-row">
        <div class="col-xl-9 col-lg-9 col-sm-9 layout-top-spacing layout-spacing">
            
            <x-alert-component type="error" />
            <x-alert-component type="success" />

            <div class="widget-content widget-content-area br-8">
                <h5 class="mb-4">{{$title}}</h5>
                
                <!-- Add CSRF token -->
                <form id="upload-excel-form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Excel File:</label>
                        <input type="file" name="file" id="file" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Upload</button>
                </form>
            </div>
        </div>
    </div>

    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles></x-slot>

    <script>
       $(document).ready(function (e) {
    $('#upload-excel-form').on('submit', function (e) {
        e.preventDefault();
        
        var formData = new FormData(this);

        // Debugging: Log the FormData content
        for (var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]);
        }

        let buttonform = $(this).find('button');
        let currenttext = buttonform.text();
        buttonform.text('loading');
        buttonform.prop('disabled',true);

        $.ajax({
            type: 'POST',
            url: "{{ route('leads.store') }}",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function (response) {

                buttonform.text(currenttext);
                buttonform.prop('disabled',false);

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                });
            },
            error: function (response) {

                buttonform.text(currenttext);
                buttonform.prop('disabled',false);

                let errors = response.responseJSON.errors;
                let errorMessage = '';

                if (errors) {
                    $.each(errors, function (key, value) {
                        errorMessage += value[0] + '\n';
                    });
                } else {
                    errorMessage = response.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                });
            }
        });
    });
});

    </script>
    <!--  END CUSTOM SCRIPTS FILE  -->

</x-base-layout>
