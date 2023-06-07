<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\User;
use Core\Controller;
use Core\View;

class Ticket extends Controller
{

    public function indexAction()
    {
        $user = User::getUser();
        $projects = Project::getAll($user->getCompanyId());
        View::renderTemplate('tickets/tickets.twig', array(
            "user" => User::getUser(),
            "tickets" => \App\Models\Ticket::getAll($user->getId())
        ));
    }

    protected function before(): bool
    {

        if (User::isLogged()) {
            return true;
        }
        View::renderTemplate('403.html');
        return false;
    }

    public function createAction()
    {
        $user = User::getUser();
        if (isset($_POST['validate'])) {
            if (\App\Models\Ticket::create(new \App\Models\Ticket($_POST['title'], $_POST['desc'], "En Attente", $_POST['project'], $user->getId(), date("Y-m-d H:i:s"), date("Y-m-d H:i:s")))) {
                $this->redirect('/public/tickets');
            }

        } else {
            $projects = Project::getAll($user->getCompanyId());
            View::renderTemplate('tickets/create.twig', array("projects" => $projects));
        }
    }
}
