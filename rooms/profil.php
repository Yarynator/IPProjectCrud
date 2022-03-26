<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Profil";


    protected function body(): string
    {
        RoomModel::checkLogined();

        $name = $_SESSION["name"];
        $stmt = $this->pdo->prepare("SELECT name, color, admin FROM employee WHERE login='$name'");
        $stmt->execute([]);

        $stmt = $stmt->fetch();

        $userName = $stmt->name;
        $color = $stmt->color;
        $admin = $stmt->admin;

        return $this->m->render("profil", ["name" => $userName, "color" => $color, "admin" => $admin]);
    }
}

(new CurrentPage())->render();
