<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMC</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/frontend') }}/assets/css/bootstrap.css">

    <link rel="stylesheet" href="{{ asset('/frontend') }}/assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="{{ asset('/frontend') }}/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="{{ asset('/frontend') }}/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('/frontend') }}/assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon">

    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">


    <style>
        .pdf-container {
            position: relative;
            width: 100%;
            padding-top: 56.25%; /* Aspect ratio: 16:9 (adjust as needed) */
        }

        .pdf-container embed,
        .pdf-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
    @stack('page-style')

</head>

<body>
<div id="app">


    @if(strpos(Request::url(),"mobile_raz")===false)
        @include('components.sidebar')
    @endif

    <div id="main">

        @if(strpos(Request::url(),"mobile_raz")===false)
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
        @endif


        <div class="page-heading">
            @yield('page-heading')
        </div>

        <div class="page-content">
            @yield('page-content')
        </div>

        <footer>
            <div class="footer clearfix mb-0 text-muted">
                <div class="float-start">
                    <p>2021 &copy; CMC</p>
                </div>
                <div class="float-end">

                </div>
            </div>
        </footer>
    </div>
</div>
</div>
<script src="{{ asset('/frontend') }}/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="{{ asset('/frontend') }}/assets/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('/frontend') }}/assets/vendors/apexcharts/apexcharts.js"></script>
<script src="{{ asset('/frontend') }}/assets/js/pages/dashboard.js"></script>

<script src="{{ asset('/frontend') }}/assets/js/main.js"></script>


<script src="{{ URL::to('frontend') }}/assets/libs/jquery/dist/jquery.min.js"></script>
<script src="{{ URL::to('frontend') }}/assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="{{ URL::to('frontend') }}/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>


@stack('script')

<script>
    $('body').on("click", ".hide-modal", function () {
        $(".modal:visible").modal('toggle');
    });
</script>

</body>

</html>
