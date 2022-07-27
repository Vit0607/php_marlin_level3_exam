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


    public function register()
    {
//        ini_set('error_reporting', E_ALL);
//        ini_set('display_errors', 1);
//        ini_set('display_startup_errors', 1);

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

            if (!empty($_SESSION['flash_messages']['error'])) {
                header('Location: /register');
            } else {
                header('Location: /login');
            }
        }
    }

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

            if (!empty($_SESSION['flash_messages']['error'])) {
                header('Location: /login');
            } else {
                header('Location: /users');
            }
        }
    }

    public function logout()
    {
        $this->auth->logOut();

        header('Location: /login');
    }

    public function users()
    {
        if ($this->auth->isLoggedIn()) {
            $role = $this->auth->getRoles();
            $users = $this->qb->getAllTablesInfo('users', 'info_links');
//            unset($_SESSION['flash_messages']['success']);
            echo $this->templates->render('users', ['usersInView' => $users, 'role' => $role]);
        } else {
            header('Location: /login');
        }
    }

    public function create_user()
    {
        if ($this->auth->isLoggedIn()) {
            if (!$_POST['create_user']) {
                echo 1;
                echo $this->templates->render('create_user');
            } elseif ($_POST['create_user']) {
                echo 2;
                try {
                    $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username']);
                    flash()->message('Профиль успешно обновлен.', 'success');
                } catch (\Delight\Auth\InvalidEmailException $e) {
                    flash()->message('Invalid email address', 'error');
                } catch (\Delight\Auth\InvalidPasswordException $e) {
                    flash()->message('Invalid password', 'error');
                } catch (\Delight\Auth\UserAlreadyExistsException $e) {
                    flash()->message('User already exists', 'error');
                } catch (\Delight\Auth\TooManyRequestsException $e) {
                    flash()->message('Too many requests', 'error');
                }

                if (!empty($_SESSION['flash_messages']['error'])) {
                    header('Location: /create-user');
                } else {
                    $data = [
                        'user_id' => $userId,
                        'job_title' => $_POST['job_title'],
                        'phone' => $_POST['phone'],
                        'address' => $_POST['address'],
                        'online_status' => $_POST['online_status'],
                        'telegram' => 'https://telegram.org/',
                        'instagram' => 'https://instagram.com/',
                        'vk' => 'https://vk.com/id399462970',
                    ];

                    $this->qb->insert($data, 'info');


                    header('Location: /users');
                }
            }
        } else {
            header('Location: /login');
        }
    }

}
