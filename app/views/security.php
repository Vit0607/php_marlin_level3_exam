<?php $this->layout('layout', ['title' => 'Безопасность']) ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
    <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo" class="d-inline-block align-top mr-2" src="img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/users">Главная <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/login">Войти</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/logout">Выйти</a>
            </li>
        </ul>
    </div>
</nav>
<main id="js-page-content" role="main" class="page-content mt-3">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-lock'></i> Безопасность
        </h1>

    </div>
    <?php echo flash()->display(); ?>
    <form method="post">
        <div class="row">
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-container">
                        <div class="panel-hdr">
                            <h2>Обновление эл. адреса и пароля</h2>
                        </div>
                        <div class="panel-content">
                            <!-- email -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Email</label>
                                <input type="text" id="simpleinput" class="form-control" name="email" value="<?php echo
                                $user['email']; ?>">
                            </div>

                            <!-- password -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Пароль</label>
                                <input type="password" id="simpleinput" class="form-control">
                            </div>

                            <!-- password confirmation-->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Подтверждение пароля</label>
                                <input type="password" id="simpleinput" class="form-control" name="password">
                            </div>


                            <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                <button class="btn btn-warning" name="security" value="security">Изменить</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</main>