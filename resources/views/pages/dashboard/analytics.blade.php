<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{$title}} 
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        
        <link rel="stylesheet" href="{{asset('plugins/apex/apexcharts.css')}}">

        @vite(['resources/scss/light/assets/components/list-group.scss'])
        @vite(['resources/scss/light/assets/widgets/modules-widgets.scss'])


        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">{{$breadcrumb}}</a></li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->

    <!-- Analytics -->

    <div class="row layout-top-spacing">

        <div class="">

            <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <x-widgets._w-six title="Users"/>
            </div>
            
           

        
            
        </div>
        

    </div>
    
    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>
        <script src="{{asset('plugins/apex/apexcharts.min.js')}}"></script>
        
        @vite(['resources/assets/js/widgets/_wSix.js'])
       
    </x-slot>
    <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>