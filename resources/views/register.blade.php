<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    {{-- Font google --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Anton">
    <style>
        h2{
            color: white !important;
        }
        h1,
        h2,
        h3,
        h4 {
            font-family: "Anton";
            text-shadow: 1px 2px black;
        }

        body {
            background-image: url('/images/newbg.jpg') !important;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
    </style>
    <title>Register | {{ env('APP_NAME') }} </title>
</head>

<body>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="text-center mb-4">
                        <b>
                            <h2>SISTEM PENYIMPANAN FINISH GOODS</h2>
                            <h2>PT. TAKITA MANUFACTURING INDONESIA</h2>
                        </b>
                    </div>
                    <div class="card shadow-2-strong" style="border-radius: 1rem;background-color: #F1EFEF">
                        <div class="card-body p-5 text-center">

                            <h3 class="mb-5">Register</h3>

                            @if (Session::has('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ Session::get('success') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="name" id="floatingInput"
                                        placeholder="Name" @required(true)>
                                    <label for="floatingInput">Name</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="username" id="floatingInput"
                                        placeholder="Username" @required(true)>
                                    <label for="floatingInput">Username</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" name="email" id="floatingInput"
                                        placeholder="E-Mail" @required(true)>
                                    <label for="floatingInput">E-Mail</label>
                                </div>
                                <div class="form-floating">
                                    <input type="password" class="form-control" name="password" id="floatingPassword"
                                        placeholder="Password" @required(true)>
                                    <label for="floatingPassword">Password</label>
                                </div>

                                <div class="d-grid gap-2 mt-4">
                                    <button class="btn btn-primary" type="submit">Register</button>
                                    <a class="btn btn-light border border-danger" type="button"
                                        href="{{ url('login') }}">Login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    -->
</body>

</html>
