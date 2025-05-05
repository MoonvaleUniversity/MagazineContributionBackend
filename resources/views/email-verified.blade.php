<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        .main-container {
            display: flex;
        }
    </style>
</head>

<body class="main-container" style="background-color: #E4E0F5">
    <div class="d-flex flex-column justify-content-center p-4 text-center ">
        <img src="/assets/logo/logo dark.png" alt="logo" class="w-25 mx-auto d-block" />

        <h2 class="fs-4 fw-bold mt-3">Email Verified</h2>

        <p class="text-dark">
            Your email has been verified. Please login again to use our service.
            <a class="text-decoration-none" href="http://localhost:5174/login">Login now</a>
        </p>
    </div>
</body>

</html>

<script>
    setTimeout(() => {
        window.location.href= "http://localhost:5174/login";
    },2000)
</script>
