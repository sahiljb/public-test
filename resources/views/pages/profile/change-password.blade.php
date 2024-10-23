<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{$title}} 
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
      
        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <div class="row" id="cancel-row">
                    
        <div class="col-xl-9 col-lg-9 col-sm-9 layout-top-spacing layout-spacing">
            <div class="widget-content widget-content-area br-8">
                <h5 class="mb-4">{{$title}}</h5>
                <form class="changepasswordForm"  method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form">
                        <div class="row">
                            <div class="col-md-12  mb-3">
                            <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="new_password_confirmation">Confirm New Password</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                </div>
                            </div>
                                        

                            <div class="col-md-12 mt-1">
                                <div class="form-group text-end">
                                    <button type="submit" class="btn btn-primary _effect--ripple waves-effect waves-light changepassword">Save</button>
                                </div>
                            </div>
                            <div class="col-md-12 mt-1">
                                <div id="response-message" class="mt-3"></div>
                            </div>
                            
                        </div>
                        
                    </div>
            
                </form>
             </div>
            </div>
        </div>
    
    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>

        <script>
            $(document).ready(function () {
                $(".changepasswordForm").on("submit", function (e) {
                    e.preventDefault();

                    const csrfToken = $("meta[name='csrf-token']").attr("content");

                    const formData = new FormData(this);

                    $.ajax({
                        url: '{{route("profile.update-password-process")}}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        success: function (response) {
                            if(response.status == 'false'){
                                $('#response-message').addClass('text-danger').removeClass('text-success').text(response.message);
                            }else{
                                $('.changepasswordForm')[0].reset();
                                $('#response-message').removeClass('text-danger').addClass('text-success').text(response.message);
                            }
                            
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                // Validation errors
                                const errors = xhr.responseJSON.errors;
                                // Construct a list of error messages
                                const errorMessage = Object.values(errors).map(error => error[0]).join('<br>');
                                $('#response-message').removeClass('text-success').addClass('text-danger').html(errorMessage);
                            } else {
                                // Other errors
                                $('#response-message').removeClass('text-success').addClass('text-danger').text(xhr.responseJSON.message);
                            }
                        },
                    });

                    return false;
                });
            });



        </script>
        
    </x-slot>
    <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>