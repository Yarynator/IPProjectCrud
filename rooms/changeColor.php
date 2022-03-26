<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Změnit barvu";

    protected function body(): string
    {

        RoomModel::checkLogined();

        if(isset($_POST["submit"]))
        {
            $name = $_SESSION["name"];
            $newColor = mb_strtolower($_POST["color"]);
            $stmt = $this->pdo->prepare("UPDATE employee SET color='$newColor' WHERE login='$name'");
            $stmt->execute([]);
            header("Location: ./profil.php");
        }

        return $this->m->render("changeColor");
    }
}

(new CurrentPage())->render();
