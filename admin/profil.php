<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Profil";

    protected function setUp(): void
    {
        parent::setUp();

        BasePage::checkLogined();
    }

    protected function body(): string
    {

        $name = $_SESSION["name"];
        $stmt = $this->pdo->prepare("SELECT name, admin FROM employee WHERE login='$name'");
        $stmt->execute([]);

        $stmt = $stmt->fetch();

        $userName = $stmt->name;
        $admin = $stmt->admin;

        return $this->m->render("profil", ["name" => $userName, "admin" => $admin]);
    }
}

(new CurrentPage())->render();
