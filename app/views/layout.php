<head>
    <meta charset="utf-8">
    <title><?= $this->e($title) ?></title>
    <meta name="description" content="Login">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <!-- Call App Mode on ios devices -->
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <!-- base css -->
    <link id="vendorsbundle" type="text/css" rel="stylesheet" media="screen, print" href="/css/vendors.bundle.css">
    <link id="appbundle" type="text/css" rel="stylesheet" media="screen, print" href="/css/app.bundle.css">
    <!--    <link id="mytheme" rel="stylesheet" media="screen, print" href="#">-->
    <link id="myskin" type="text/css" rel="stylesheet" media="screen, print" href="/css/skins/skin-master.css">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="mask-icon" href="img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" media="screen, print" href="/css/fa-brands.css">
    <!-- CSS only -->
    <!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">-->
</head>
<body>

<nav>
    <ul>
        <li><a href="/home">Homepage</a></li>
        <li><a href="/about">About</a></li>
        <li><a href="/login">Логин</a></li>
        <li><a href="/register">Регистрация</a></li>
        <li><a href="/logout">Выход</a></li>
    </ul>
</nav>

<?= $this->section('content') ?>

<script src="/js/vendors.bundle.js" type="text/javascript"></script>
<script>
    $("#js-login-btn").click(function(event)
    {

        // Fetch form to apply custom Bootstrap validation
        var form = $("#js-login")

        if (form[0].checkValidity() === false)
        {
            event.preventDefault()
            event.stopPropagation()
        }

        form.addClass('was-validated');
        // Perform ajax submit here...
    });

</script>

</body>


