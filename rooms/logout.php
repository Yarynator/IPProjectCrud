<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Logout";

    protected function body(): string
    {

        session_destroy();
        header("Location: ./");

        return $this->m->render("login", []);
    }
}

(new CurrentPage())->render();
