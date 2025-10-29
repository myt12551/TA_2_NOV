<!DOCTYPE html>
<html lang="en" style="min-width: 100vh !important;">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title>{{ config('APP_NAME', 'Teaching factory') }}</title>
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
  <!-- Custom CSS -->
  <link href="/dist/css/style.min.css" rel="stylesheet">

  <script src="/assets/libs/jquery/dist/jquery.min.js"></script>
</head>

<body>
  <!-- ============================================================== -->
  <!-- Preloader - style you can find in spinners.css -->
  <!-- ============================================================== -->
  <div class="preloader">
    <div class="lds-ripple">
      <div class="lds-pos"></div>
      <div class="lds-pos"></div>
    </div>
  </div>

  <div class="preloader" style="display: none; background: transparent;" id="preloader">
    <div class="lds-ripple">
      <div class="lds-pos"></div>
      <div class="lds-pos"></div>
    </div>
  </div>
  <div class="main-wrapper mt-5">
    <!-- ============================================================== -->
    <!-- Login box.scss -->
    <!-- ============================================================== -->
    <div class="pt-5 pb-2"></div>
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center pt-5">
      <div class="auth-box border-top border-secondary bg-dark p-4">
        <div id="loginform">
          <div class="text-center pt-3 pb-3">
            <span class="db">
              <img src="assets/images/icon.png" alt="logo" class="light-logo" />
              <b class="text-white">TEACHING FACTORY</b>
            </span>
          </div>
          <!-- Form -->
          <form class="form-horizontal mt-3" id="loginform" action="/login" method="POST">
            @csrf
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <div class="row">
              <div class="col-12">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-success text-white h-100" id="basic-addon1"><i
                        class="mdi mdi-account fs-4"></i></span>
                  </div>
                  <input type="text" class="form-control form-control-lg @error('error') is-invalid @enderror"
                    placeholder="Username" name="username" aria-label="Username" aria-describedby="basic-addon1"
                    required="" autofocus />
                </div>
                <div class="input-group mt-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-warning text-white h-100" id="basic-addon2"><i
                        class="mdi mdi-lock fs-4"></i></span>
                  </div>
                  <input type="password" class="form-control form-control-lg @error('error') is-invalid @enderror"
                    placeholder="Password" name="password" aria-label="Password" aria-describedby="basic-addon1"
                    required="" />
                </div>
              </div>
            </div>
              <div class="col-12">
                <div class="row mt-3">
            <span class="text-sm text-gray-600">Belum punya akun?</span>
              <a href="{{ route('customer.register') }}"
                class="inline-block px-4 py-2 mt-2 rounded-lg border hover:bg-gray-50 transition">
                  Buat Akun Customer
              </a>
          </div>
                <div class="form-group">
                  <button class="btn btn-success float-end text-white" type="submit">
                    Login
                  </button>
                </div>
              </div>
              <div class="mt-4 text-center">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(".preloader").fadeOut();
  </script>
</body>

</html>
