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
                <form method="post" action="{{ route('customer.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-xxl-6 mb-4">
                            <label>Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="uname" name="uname" placeholder="Name" value="{{ old('uname') }}" >
                            @error('uname')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xxl-6 mb-4">
                            <label>Email<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="uemail" name="uemail" placeholder="Email" value="{{ old('uemail') }}" >
                            @error('uemail')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-xxl-6 mb-4">
                            <label>Phone<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="uphone" name="uphone" placeholder="Phone" value="{{ old('uphone') }}" >
                            @error('uphone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xxl-6 mb-4">
                            <label>Password<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="upassword" name="upassword" placeholder="Password" value="{{ old('upassword') }}" >
                            @error('upassword')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xxl-6 mb-4">
                            <label>Status<span class="text-danger">*</span></label>
                            <select class="form-control" id="ustatus" name="ustatus">
                                <option value="active" @if(old('ustatus') == 'active') selected @endif>Active</option>
                                <option value="deactive" @if(old('ustatus') == 'deactive') selected @endif>Deactive</option>
                            </select>
                            @error('ustatus')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                       

                        <div class="col-xxl-6 mb-4">
                            <label>Profile Photo<span class="text-danger"></span></label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                            @error('profile_photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-12 mb-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles></x-slot>
    <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>