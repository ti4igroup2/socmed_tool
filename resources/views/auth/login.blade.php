<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login | KLY Sosmed Tool</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Login | KLY Sosmed Tool"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon -->
    <link rel="icon" href="{{config('app.url')}}/assets/img/Favicon-KLY.png">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{config('app.url')}}/assets/assets/fonts/fontawesome/css/fontawesome-all.min.css">
    <!-- animation css -->
    <link rel="stylesheet" href="{{config('app.url')}}/assets/assets/plugins/animation/css/animate.min.css">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{config('app.url')}}/assets/assets/css/style.css">

</head>

<body>

        <div class="auth-wrapper">
                <div class="auth-content">
                    <div class="auth-bg">
                        <span class="r"></span>
                        <span class="r s"></span>
                        <span class="r s"></span>
                        <span class="r"></span>
                    </div>
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                    <img src="{{config('app.url')}}/assets/img/login.png" alt="KLY OFFICIAL" class="img-fluid">
                            </div><br><br><hr>
                            <a href="{{config('app.url')}}/login/auth" style="text-transform:none">
                                <div class="left">
                                    <img width="20px" alt="Google &quot;G&quot; Logo" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png"/>
                                </div>
                                Login with Google+
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!-- Required Js -->
    <script src="{{config('app.url')}}/assets/assets/js/vendor-all.min.js"></script>
    <script src="{{config('app.url')}}/assets/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
