<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{$title}} 
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        @vite(['resources/scss/light/assets/authentication/auth-boxed.scss'])

        
        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>

    <style>
            body{
                background-image:url('{{url("images/logback.jpg")}}') !important;
                background-size: cover;
                background-repeat: no-repeat;
            }
        </style>
        
    <!-- END GLOBAL MANDATORY STYLES -->
    
    <div class="auth-container d-flex">

        <div class="container mx-auto align-self-center">
    
            <div class="row">
    
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                    <div class="card mt-3 mb-3">
                        <div class="card-body">

                        <form method="post" id="adminLogins" action="{{route('login')}}" onsubmit="return validateForm()">
                                {{csrf_field()}}
    
                            <div class="row">
                                <div class="col-md-12 mb-3">

                                    @if(session()->has('error'))
                                        <div class="alert alert-danger">{{session()->get('error')}}</div>
                                    @endif

                                    @if(session()->has('success'))
                                        <div class="alert alert-success">{{session()->get('success')}}</div>
                                    @endif
                                    
                                    <h2>Sign In</h2>
                                    <p>Enter your email and password to login</p>
                                    
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" id="email" placeholder="Email address" class="form-control">
                                        <div id="emailError" class="text-danger p-1" style="display: none;">Email is required</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" id="password" placeholder="Password" class="form-control">
                                        <div id="passwordError" class="text-danger p-1" style="display: none;">Password is required</div>
                                    </div>
                                </div>
                               
                                
                                <div class="col-12">
                                    <div class="mb-4">
                                        <button class="btn btn-primary w-100">SIGN IN</button>
                                    </div>
                                </div>
    
                                
                            </div>

                        </form>
                            
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>

    </div>
    
    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>

    <script>
    function validateForm() {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var termsCheckbox = document.getElementById("form-check-default").checked;

        var emailError = document.getElementById("emailError");
        var passwordError = document.getElementById("passwordError");
        var termsError = document.getElementById("termsError");

        emailError.style.display = "none";
        passwordError.style.display = "none";
        termsError.style.display = "none";

        if (email.trim() === "") {
            emailError.style.display = "block";
            return false;
        }

        if (password.trim() === "") {
            passwordError.style.display = "block";
            return false;
        }

        if (!termsCheckbox) {
            termsError.style.display = "block";
            return false;
        }

        return true;
    }
</script>

    </x-slot>
    <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>