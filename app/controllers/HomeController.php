<?php

namespace App\controllers;

use App\exсeptions\NotEnoughMoneyException;
use App\QueryBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;
use PDO;

class HomeController
{
    private $templates;
    private $auth;
    private $qb;
    private $selector;
    private $token;

    public function __construct(QueryBuilder $qb, Engine $engine, Auth $auth)
    {
        $this->qb = $qb;
        $this->templates = $engine;
        $this->auth = $auth;
    }

    public function index()
    {
//        d($this->auth);die();

        $users = $this->qb->getAll('users');

        echo $this->templates->render('homepage', ['usersInView' => $users]);
    }

    public function about()
    {
        $db = new PDO('mysql:host=localhost;dbname=php_marlin_app3;charset=utf8;', 'root', '');
        $auth = new Auth($db);

        $one = $this->qb->getOne('users', 1);

//        d($one);die();

        try {
            $userId = $auth->register('rahim2@marlindev.ru', '123', 'Rahim2', function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
                echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
                echo '  For SMS, consider using a third-party service and a compatible SDK';
            });

            echo 'We have signed up a new user with the ID ' . $userId;
        } catch (\Delight\Auth\InvalidEmailException $e) {
            die('Invalid email address');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('User already exists');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }

//        echo $this->templates->render('about', ['name' => 'Jonathan about page']);
    }

    public function register()
    {
        if (!$_POST['reg']) {
            echo 1;
//            echo $bbb;die();
            var_dump($_SESSION);
            echo $this->templates->render('page_register');
        } elseif ($_POST['reg']) {
            echo 2;
            try {
                $this->auth->register($_POST['email'], $_POST['password'], $_POST['username']);
                flash()->message('Регистрация успешна', 'success');
            } catch (\Delight\Auth\InvalidEmailException $e) {
                flash()->message('Invalid email address', 'error');
            } catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->message('Invalid password', 'error');
            } catch (\Delight\Auth\UserAlreadyExistsException $e) {
                flash()->message('User already exists', 'error');
            } catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->message('Too many requests', 'error');
            }

            if(!empty($_SESSION['flash_messages']['error'])) {
                header('Location: /register');
            } else {
                header('Location: /login');
            }
        }
    }

//    public function email_verification()
//    {
//        try {
//            $this->auth->confirmEmail($this->selector, $this->token);
//            flash()->message('Регистрация успешна', 'success');
//        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
//            flash()->message('Invalid token 2', 'error');
//        } catch (\Delight\Auth\TokenExpiredException $e) {
//            flash()->message('Token expired', 'error');
//        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
//            flash()->message('Email address already exists', 'error');
//        } catch (\Delight\Auth\TooManyRequestsException $e) {
//            flash()->message('Too many requests 2', 'error');
//        }
//
//        if(!empty($_SESSION['flash_messages']['error'])) {
//            header('Location: /register');
//        } else {
//            header('Location: /login');
//        }
//
//    }

    public function login()
    {
        var_dump($_POST);
        if (!$_POST['login']) {
            echo 1;
            var_dump($_POST);
            var_dump($_SESSION);
            echo $this->templates->render('page_login');
            unset($_SESSION['flash_messages']['error']);
            unset($_SESSION['flash_messages']['success']);
        } elseif (!empty($_POST['login'])) {
            echo 2;
            try {
                $this->auth->login($_POST['email'], $_POST['password']);
                header('Location: /users');
            } catch (\Delight\Auth\InvalidEmailException $e) {
                flash()->message('Wrong email address', 'error');
            } catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->message('Wrong password', 'error');
            } catch (\Delight\Auth\EmailNotVerifiedException $e) {
                flash()->message('Email not verified', 'error');
            } catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->message('Too many requests', 'error');
            }

            if(!empty($_SESSION['flash_messages']['error'])) {
                header('Location: /login');
            } else {
                header('Location: /users');
            }
        }
    }

    public function logout()
    {
        $this->auth->logOut();

//// or
//
//        try {
//            $this->auth->logOutEverywhereElse();
//        } catch (\Delight\Auth\NotLoggedInException $e) {
//            die('Not logged in');
//        }
//
//// or
//
//        try {
//            $this->auth->logOutEverywhere();
//        } catch (\Delight\Auth\NotLoggedInException $e) {
//            die('Not logged in');
//        }

        header('Location: /login');

    }

    public function users()
    {

        if($this->auth->isLoggedIn()) {
            // d($this->auth);die();
// d($this->auth->isLoggedIn());die();

            $log = $this->auth->isLoggedIn();

// $this->auth->login('123@tutu.ru', '123');

// d($this->auth->getRoles());die();

            $role = $this->auth->getRoles();
//        var_dump($role);


// $this->auth->admin()->addRoleForUserById('11', \Delight\Auth\Role::ADMIN);
// $this->auth->admin()->addRoleForUserById('2', \Delight\Auth\Role::DIRECTOR);
// $this->auth->admin()->addRoleForUserById('3', \Delight\Auth\Role::MANAGER);

//            $users = $this->qb->getAll('users');
            $users = $this->qb->getAllTablesInfo('users', 'information_links');

            echo $this->templates->render('users', ['usersInView' => $users, 'role' => $role]);
        } else {
            header('Location: /login');
        }

    }
}
