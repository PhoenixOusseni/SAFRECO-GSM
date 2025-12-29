<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.style')
</head>

<body>

    <!-- ======= Header ======= -->
    @include('partials.topNav')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('partials.sidebar')
    <!-- End Sidebar-->

    <main id="main" class="main">
        @if (session('success') || session('error'))
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
                <div class="toast align-items-center text-white {{ session('success') ? 'bg-success' : 'bg-danger' }} border-0"
                    role="alert" aria-live="assertive" aria-atomic="true" id="statusToast" data-bs-delay="5000">
                    <div class="d-flex">
                        <div class="toast-body">
                            @if (session('success'))
                                <div class="text-center">
                                    <h5 class="mb-2 text-white">Succès !</h5>
                                    <p class="mt-3 text-white">
                                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                                    </p>
                                </div>
                            @else
                                <div class="text-center">
                                    <h5 class="mb-2 text-white">Erreur !</h5>
                                    <p class="mt-3 text-white">
                                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Yield method for content --}}
        @yield('content')

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('partials.footer')
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    @include('partials.script')

    @yield('scripts')

    <!-- Script pour initialiser le toast Bootstrap -->
    @if (session('success') || session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var toastElement = document.getElementById('statusToast');
                if (toastElement) {
                    var toast = new bootstrap.Toast(toastElement);
                    toast.show();
                }
            });
        </script>
    @endif

</body>

</html>
