<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Login";
    private string $warning = "";
    private string $name = "";

    protected function setUp(): void
    {
        parent::setUp();

        if(isset($_SESSION["name"]))
            header("Location: ./profil.php");

        $this->warning = isset($_SESSION["loginWarn"]) ? $_SESSION["loginWarn"] : "";
        $this->name = isset($_SESSION["loginName"]) ? $_SESSION["loginName"] : "";

        $_SESSION["loginWarn"] = "";
        $_SESSION["loginName"] = "";
    }

    protected function body(): string
    {
        return $this->m->render("loginForm", ["warning" => $this->warning, "name" => $this->name]);
    }
}

(new CurrentPage())->render();
