<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ✅ Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow p-4 text-center" style="max-width: 420px; width:100%; border-radius:15px;">

        @if ($status === 'just_verified')
            <div class="text-success display-4 mb-3">✔</div>
            <h3 class="fw-bold">Email Verified Successfully</h3>
            <p class="text-muted">
                Your email address has been successfully verified. You can now access your account.
            </p>
           {{-- <a href="" class="btn btn-primary px-4">
                Login Now
            </a> --}}

        @elseif ($status === 'already_verified')
            <div class="text-info display-4 mb-3">ℹ</div>
            <h3 class="fw-bold">Email Already Verified</h3>
            <p class="text-muted">
                This email has already been verified earlier. You can log in normally.
            </p>
            {{-- <a href="" class="btn btn-success px-4">
                Go to Login
            </a> --}}

        @else
            <div class="text-danger display-4 mb-3">✖</div>
            <h3 class="fw-bold">Invalid or Expired Link</h3>
            <p class="text-muted">
                This verification link is invalid or has expired. Please request a new verification email.
            </p>
            {{--<a href="" class="btn btn-danger px-4">
                Back to Login
            </a>--}}
        @endif

    </div>

    <!-- ✅ Bootstrap JS (optional for components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
