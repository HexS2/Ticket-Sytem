<?php

namespace App\Controllers;

use App\Models\User;
use Core\Controller;
use Core\View;

class LogController extends Controller
{


    public function loginAction()
    {

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $pass = $_POST['password'];

            if (User::login($email, $pass)) {
                $this->redirect("/public/tickets");
            }
        } else {
            View::renderTemplate('log/login.twig');
        }

    }

    public function logoutAction()
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect("/public/index");
    }

    public function registerAction()
    {

        if (isset($_POST['email']) && isset($_POST['password'])) {
            if (User::register($_POST['email'], $_POST['password'])) {
                View::renderTemplate('Home/index.html');
            }
        } else {
            View::renderTemplate('log/register.twig');
        }

    }
}
