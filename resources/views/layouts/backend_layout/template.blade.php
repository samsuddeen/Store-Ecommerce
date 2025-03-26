@include('layouts.backend_layout.partials.header')
  <!-- ======= Header ======= -->
  {{-- @include('layouts.backend_layout.partials.nav-bar') --}}
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  {{-- @include('layouts.backend_layout.partials.side-bar') --}}
  <!-- End Sidebar-->

  <main id="main" class="main">

   @yield('main-content')

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">

  </footer><!-- End Footer -->

@include('layouts.backend_layout.partials.footer')
