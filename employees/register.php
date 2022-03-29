<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Login";

    protected function body(): string
    {
        if(isset($_SESSION["name"]))
            header("Location: ./profil.php");

        return $this->m->render("register");
    }
}

(new CurrentPage())->render();
