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

    public function email_verification()
    {
        try {
            $this->auth->confirmEmail($this->selector, $this->token);

//            echo 'Email address has been verified';
            flash()->message('Регистрация успешна', 'success');
            $_SESSION['flash_messages']['reg_success'] = 'success';
            header('Location: /login');
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
//            die('Invalid token');
            flash()->message('Invalid token', 'error');
        } catch (\Delight\Auth\TokenExpiredException $e) {
//            die('Token expired');
            flash()->message('Token expired', 'error');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
//            die('Email address already exists');
            flash()->message('Email address already exists', 'error');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
//            die('Too many requests');
            flash()->message('Too many requests', 'error');
        }

        $_SESSION['flash_messages']['reg_error'] = 'error';

        header('Location: /register');
    }

    public function login()
    {
        try {
            $this->auth->login('rahim@marlindev.ru', '123');

            echo 'User is logged in';
        } catch (\Delight\Auth\InvalidEmailException $e) {
            die('Wrong email address');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Wrong password');
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            die('Email not verified');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

    public function register()
    {
        if (!$_POST['reg']) {
            var_dump($_SESSION);
            echo $this->templates->render('page_register');
            unset($_SESSION['flash_messages']['reg_error']);
        } elseif ($_POST['reg']) {
            try {
                $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
                    $this->selector = $selector;
                    $this->token = $token;
                    header('Location: /verification');
                });

                echo 'We have signed up a new user with the ID ' . $userId;
            } catch (\Delight\Auth\InvalidEmailException $e) {
                flash()->message('Invalid email address', 'error');
            } catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->message('Invalid password', 'error');
            } catch (\Delight\Auth\UserAlreadyExistsException $e) {
                flash()->message('User already exists', 'error');
            } catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->message('Too many requests', 'error');
            }

//            var_dump(flash()->display('error'));
//            die();

//            $error = flash()->display('error');
//
//            if (!empty($error)) {
//                $_SESSION['flash_messages']['reg_error'] = 'error';
//
//                header('Location: /register');
//            } else {
//                header('Location: /verification');
//            }

            $_SESSION['flash_messages']['reg_error'] = 'error';

            header('Location: /register');
        }
    }

    public function logout()
    {
        $this->auth->logOut();

// or

        try {
            $this->auth->logOutEverywhereElse();
        } catch (\Delight\Auth\NotLoggedInException $e) {
            die('Not logged in');
        }

// or

        try {
            $this->auth->logOutEverywhere();
        } catch (\Delight\Auth\NotLoggedInException $e) {
            die('Not logged in');
        }
    }
}
