<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Login";

    protected function body(): string
    {

        if($_SESSION["name"])
            header("Location: ./profil.php");

        if(isset($_POST["login"]))
        {

            $name = $_POST["name"];
            $stmt = $this->pdo->prepare("SELECT password FROM employee WHERE login='$name'");
            $stmt->execute([]);

            $password = $stmt->fetch()->password;

            if(password_verify($_POST["password"], $password)){
                $_SESSION["name"] = $name;

            } else{
                //chybova hlaska
            }
            header("Location: ./profil.php");
        }

        /*$stmt = $this->pdo->prepare("SELECT * FROM `room` ORDER BY `name`");
        $stmt->execute([]);*/

        return $this->m->render("login");
    }
}

(new CurrentPage())->render();
