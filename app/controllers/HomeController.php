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
    private $id;
    private $user;
    private $user_info;

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
        if (!$_POST['reg']) {
            echo $this->templates->render('page_register');
        } elseif ($_POST['reg']) {
            try {
                $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username']);
                $_SESSION['id'] = $userId;
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
                $this->qb->insert(['user_id' => $_SESSION['id']], 'info_links');
                header('Location: /login');
            }
        }
    }

    public function login()
    {
        if ($this->auth->isLoggedIn()) {
            header('Location: /users');
        } else {
            if (!$_POST['login']) {
                echo $this->templates->render('page_login');
                unset($_SESSION['flash_messages']['error']);
                unset($_SESSION['flash_messages']['success']);
            } elseif (!empty($_POST['login'])) {
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
            echo $this->templates->render('users', ['usersInView' => $users, 'role' => $role]);
        } else {
            header('Location: /login');
        }
    }

    public function create_user()
    {
        if ($this->auth->isLoggedIn() && array_search('ADMIN', $this->auth->getRoles())) {
            if (!$_POST['create_user']) {
                echo 1;
                echo $this->templates->render('create_user');
            } elseif ($_POST['create_user']) {
                echo 2;
                try {
                    $this->id = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username']);
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
                        'user_id' => $this->id,
                        'job_title' => $_POST['job_title'],
                        'phone' => $_POST['phone'],
                        'address' => $_POST['address'],
                        'online_status' => $_POST['online_status'],
                        'telegram' => 'https://telegram.org/',
                        'instagram' => 'https://instagram.com/',
                        'vk' => 'https://vk.com/id399462970'
                    ];

                    $this->qb->insert($data, 'info_links');

                    $uniqId = $this->qb->upload_avatar($_FILES['avatar']['name']);

                    $this->qb->updateByUserId(['avatar' => $uniqId], $this->id, 'info_links');

                    header('Location: /users');
                }
            }
        } else {
            header('Location: /login');
        }
    }

    public function edit()
    {
        if ($this->auth->isLoggedIn()) {
            if ($this->auth->getUserId() != $_GET['id'] && !array_search('ADMIN', $this->auth->getRoles())) {
                flash()->message('Можно редактировать только свой профиль', 'warning');
                header('Location: /users');
            } elseif (array_search('ADMIN', $this->auth->getRoles()) || $this->auth->getUserId() == $_GET['id']) {
                if (!$_POST['edit']) {
                    $this->id = $_GET['id'];
                    $this->user = $this->qb->getOne('users', $this->id);
                    $this->user_info = $this->qb->getOneByUserId('info_links', $this->id);
                    $_SESSION['id'] = $this->id;
                    echo $this->templates->render('edit', ['username' => $this->user['username'], 'user_info' => $this->user_info]);
                } elseif ($_POST['edit']) {
                    $this->qb->update(['username' => $_POST['username']], $_SESSION['id'], 'users');
                    $this->qb->updateByUserId([
                        'job_title' => $_POST['job_title'],
                        'phone' => $_POST['phone'],
                        'address' => $_POST['address']
                    ], $_SESSION['id'],
                        'info_links');

                    flash()->message('Профиль успешно обновлен.', 'success');

                    header('Location: /page-profile');
                }
            }
        } else {
            header('Location: /login');
        }
    }

    public function page_profile()
    {
        if ($this->auth->isLoggedIn()) {
            $this->user = $this->qb->getOne('users', $_SESSION['id']);
            $this->user_info = $this->qb->getOneByUserId('info_links', $_SESSION['id']);
            echo $this->templates->render('page_profile', ['user' => $this->user, 'user_info' => $this->user_info]);
            unset($_SESSION);
        } else {
            header('Location: /login');
        }
    }

    //Email свободен?:
    private function is_email_free($users, $edit_email)
    {
        $is_email_free = true;
        foreach ($users as $user) {
            if ($user['email'] == $edit_email) {
                $is_email_free = false;
                break;
            }
        }
        return $is_email_free;
    }


    public function security()
    {
        if ($this->auth->isLoggedIn()) {
            if ($this->auth->getUserId() != $_GET['id'] && !array_search('ADMIN', $this->auth->getRoles())) {
                flash()->message('Можно редактировать только свой профиль', 'warning');
                header('Location: /users');
            } elseif (array_search('ADMIN', $this->auth->getRoles()) || $this->auth->getUserId() == $_GET['id']) {
                if (!$_POST['security']) {
//                    var_dump($_POST);die();
                    $this->id = $_GET['id'];
                    $this->user = $this->qb->getOne('users', $this->id);
                    $_SESSION['id'] = $this->id;
                    echo $this->templates->render('security', ['user' => $this->user]);
                } elseif ($_POST['security']) {
                    $edit_email = $_POST['email'];
                    $current_user_id = $this->auth->getUserId();;

                    $current_email = $this->auth->getEmail();

                    $users = $this->qb->getAll('users');

                    $is_email_free = $this->is_email_free($users, $edit_email);

                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    if ($is_email_free == false && $edit_email == $current_email) {
                        $this->qb->update(['email' => $edit_email, 'password' => $password], $_SESSION['id'], 'users');
                        flash()->message('Профиль успешно обновлен.', 'success');
                        header('Location: /page-profile');
                    }
                    if ($is_email_free == false && $edit_email != $current_email) {
                        flash()->message('Email не свободен.', 'error');
                        header('Location: /security?id=' . $current_user_id);
                    }
                    if ($is_email_free == true && $edit_email != $current_email) {
                        $this->qb->update(['email' => $edit_email, 'password' => $password], $_SESSION['id'], 'users');
                        flash()->message('Профиль успешно обновлен.', 'success');
                        header('Location: /page-profile');
                    }
                }
            }
        } else {
            header('Location: /login');
        }
    }

    public function status()
    {
        if ($this->auth->isLoggedIn()) {
            if ($this->auth->getUserId() != $_GET['id'] && !array_search('ADMIN', $this->auth->getRoles())) {
                flash()->message('Можно редактировать только свой профиль', 'warning');
                header('Location: /users');
            } elseif (array_search('ADMIN', $this->auth->getRoles()) || $this->auth->getUserId() == $_GET['id']) {
                if (!$_POST['set_status']) {
                    $_SESSION['id'] = $_GET['id'];
                    $edit_user = $this->qb->getOneByUserId('info_links', $_GET['id']);
                    $edit_user_status = $edit_user['online_status'];
                    echo $this->templates->render('status', ['edit_user_status' => $edit_user_status]);
                } elseif ($_POST['set_status']) {
                    $this->qb->updateByUserId(['online_status' => $_POST['online_status']], $_SESSION['id'], 'info_links');
                    flash()->message('Профиль успешно обновлен.', 'success');
                    header('Location: /page-profile');
                }
            }
        } else {
            header('Location: /login');
        }
    }

    public function avatar()
    {
        if ($this->auth->isLoggedIn()) {
            if ($this->auth->getUserId() != $_GET['id'] && !array_search('ADMIN', $this->auth->getRoles())) {
                flash()->message('Можно редактировать только свой профиль', 'warning');
                header('Location: /users');
            } elseif (array_search('ADMIN', $this->auth->getRoles()) || $this->auth->getUserId() == $_GET['id']) {
                if (!$_POST['upload_avatar']) {
                    $_SESSION['id'] = $_GET['id'];
                    $edit_user = $this->qb->getOneByUserId('info_links', $_GET['id']);
                    $edit_user_avatar = $edit_user['avatar'];
                    echo $this->templates->render('avatar', ['edit_user_avatar' => $edit_user_avatar]);
                } elseif ($_POST['upload_avatar']) {
                    $uniqId = $this->qb->upload_avatar($_FILES['avatar']['name']);
                    $this->qb->updateByUserId(['avatar' => $uniqId], $_SESSION['id'], 'info_links');
                    flash()->message('Профиль успешно обновлен.', 'success');
                    header('Location: /page-profile');
                }
            }
        } else {
            header('Location: /login');
        }
    }

    public function delete()
    {
//       echo $this->auth->getUserId(); die();
        if ($this->auth->isLoggedIn()) {
            if ($this->auth->getUserId() != $_GET['id'] && !array_search('ADMIN', $this->auth->getRoles())) {
                flash()->message('Можно редактировать только свой профиль', 'warning');
                header('Location: /users');
            } elseif (array_search('ADMIN', $this->auth->getRoles()) || $this->auth->getUserId() == $_GET['id']) {
                $delete_user = $this->qb->getOneByUserId('info_links', $_GET['id']);
                $img = $delete_user['avatar'];
                unlink($_SERVER['DOCUMENT_ROOT'] . '/img/uploaded/' . $img);
                $this->qb->delete('users', $_GET['id']);
                $this->qb->deleteByUserId('info_links', $_GET['id']);

                if ($_GET['id'] == $this->auth->getUserId()) {
                    $this->auth->logOut();
                    header('Location: /register');
                } else {
                    header('Location: /users');
                }
            }
        } else {
            header('Location: /login');
        }
    }

}
