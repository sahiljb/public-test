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

    <div class="row" >
        <div class="col-xl-9 col-lg-9 col-sm-9 layout-top-spacing layout-spacing">
            
            <x-alert-component type="error" />
            <x-alert-component type="success" />

            <div class="widget-content widget-content-area br-8">
                <h5 class="mb-4">{{$title}}</h5>
                <form method="post" action="{{ route('leads.single-store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-xxl-6 mb-4">
                            <label>Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ old('name') }}" >
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xxl-6 mb-4">
                            <label>Phone<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" value="{{ old('phone') }}" >
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xxl-6 mb-4">
                            <label>Email<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="City" value="{{ old('city') }}" >
                            @error('city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-xxl-6 mb-4">
                            <label>Priority<span class="text-danger">*</span></label>
                            <select class="form-control" id="priority" name="priority">
                                <option value="normal" @if(old('priority') == 'normal') selected @endif>Normal</option>
                                <option value="moderate" @if(old('priority') == 'moderate') selected @endif>Moderate</option>
                                <option value="hot" @if(old('priority') == 'hot') selected @endif>Hot</option>
                            </select>
                            @error('priority')
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