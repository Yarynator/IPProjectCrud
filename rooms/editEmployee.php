<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage
{
    protected string $title = "Profil";


    protected function body(): string
    {
        RoomModel::checkLogined();

        if(!isset($_POST["employee_id"]))
            header("Location: ./pruvodceLidi.php");

        $warning = "";
        $id = $_POST["employee_id"];

        $stmt = $this->pdo->prepare("SELECT * FROM employee WHERE employee_id='$id'");
        $stmt->execute([]);

        $stmt2 = $this->pdo->prepare("SELECT room_id, name FROM room");
        $stmt2->execute([]);

        return $this->m->render("editEmployee", ["employees" => $stmt, "rooms" => $stmt2, "warning" => $warning]);
    }
}

(new CurrentPage())->render();
