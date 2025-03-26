    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('dashboard/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('js/app.js?v=').time() }}"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script src="{{ asset('/ckeditor4/ckeditor.js') }}"></script>
    <script src="{{ asset('js/ck-editor/build/ckeditor.js') }}"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="{{ asset('backend/js/pusher-sample.js') }}"></script>


    <script>
        // var route_prefix = "url-to-filemanager";
        // $('#lfm').filemanager('image', {prefix: "laravel-filemanager"});

        function loadUniSharp() {
            $(document).ready(function() {
                $('#lfm-1').filemanager("image", {
                    prefix: "/laravel-filemanager"
                });
                $('#lfm0').filemanager("image", {
                    prefix: "/laravel-filemanager"
                });
                $('#lfm1').filemanager("image", {
                    prefix: "/laravel-filemanager"
                });
                $('#lfm2').filemanager("image", {
                    prefix: "/laravel-filemanager"
                });
                $('#lfm3').filemanager("image", {
                    prefix: "/laravel-filemanager"
                });
                $('#lfm4').filemanager("image", {
                    prefix: "/laravel-filemanager"
                });
                $('#lfm5').filemanager("image", {
                    prefix: "/laravel-filemanager"
                });
                $('#lfm6').filemanager("image", {
                    prefix: "/laravel-filemanager"
                });
                $('#lfm7').filemanager("image", {
                    prefix: "/laravel-filemanager"
                });
                $('#lfm8').filemanager("image", {
                    prefix: "/laravel-filemanager"
                });
                $('#lfm9').filemanager("image", {
                    prefix: "/laravel-filemanager"
                });
                $('#lfm10').filemanager("image", {
                    prefix: "/laravel-filemanager"
                });
            });
        }

        function loadCKEditor1() {
            var options = {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
                filebrowserBrowseUrl: '/filemanager?type=Files',
                filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
            };
            CKEDITOR.replace('textarea-counter', options);
        }

        function loadCKEditor2() {
            var options = {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
                filebrowserBrowseUrl: '/filemanager?type=Files',
                filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
            };
            CKEDITOR.replace('textarea-counter1', options);
            
        }

        function loadCKEditor3() {
            var options = {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
                filebrowserBrowseUrl: '/filemanager?type=Files',
                filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
            };
            CKEDITOR.replace('content', options);
            
        }
        var colorValue=0;
        $(document).on('click','.changeDashboradColor',function()
        {
            var htmlClassValue=document.getElementById('htmlClassValue');

            var navBarClassValue=document.getElementById('navClassValue');

            var menuClassValue=document.getElementById('menuClassValue');
            var darkModeLabel=document.getElementById('darkModeLabel');
            
            htmlClassValue.classList.toggle('dark-layout');
            navBarClassValue.classList.toggle('navbar-dark');
            menuClassValue.classList.toggle('menu-dark');
           
            darkModeLabel.classList.toggle('darkModeClass');
            var hasDarkClass = navBarClassValue.classList.contains("navbar-dark");
            var darkValue=false;
             if(hasDarkClass)
                {
                    darkValue=true;
                }
            $.ajax({
                url:"{{route('changeColorMode')}}",
                type:"get",
                data:{
                    darkCheck:darkValue
                },
                success:function(response)
                {
                    $('#navColor').addClass(response.nav);
                }
            });
        });

        $(document).on('click','.sellerChangeDashboradColor',function()
        {
         
            var htmlClassValue=document.getElementById('htmlClassValue');

            var navBarClassValue=document.getElementById('navClassValue');

            var menuClassValue=document.getElementById('menuClassValue');
            

            var darkModeLabel=document.getElementById('darkModeLabel');

            
            htmlClassValue.classList.toggle('dark-layout');
            navBarClassValue.classList.toggle('navbar-dark');
            menuClassValue.classList.toggle('menu-dark');
            darkModeLabel.classList.toggle('darkModeClass');

           

            var hasDarkClass = navBarClassValue.classList.contains("navbar-dark");
            var darkValue=false;
             if(hasDarkClass)
                {
                    darkValue=true;
                }
            $.ajax({
                url:"{{route('sellerdarkmodedata')}}",
                type:"get",
                data:{
                    darkCheck:darkValue
                },
                success:function(response)
                {
                    $('#navColor').addClass(response.nav);
                }
            });
        });
      

        
    </script>





    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('dashboard/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('dashboard/js/core/app.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('dashboard/js/core/app-menu.js') }}"></script>

    <script src="{{ asset('dashboard/js/scripts/ui/ui-feather.js') }}" defer></script>

    <!-- choose one -->
    {{-- <script src="path/to/dist/feather.js"></script> --}}
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.js.map"></script>

    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->
    @include('admin.includes.messages')
    @stack('script')
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
            date = new Date();
            $('#footerDate').append(date.getFullYear());
        })
    </script>

    <script>
        $('#icons').on('click', function() {
            $('#exampleModal').modal('show');
        });
    </script>
