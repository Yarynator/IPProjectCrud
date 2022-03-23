<?php
session_start();
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Login";

    protected function body(): string
    {
        if(isset($_POST["login"]))
        {

            $name = $_POST["name"];
            $stmt = $this->pdo->prepare("SELECT password FROM 'users' WHERE name='$name'");
            $stmt->execute();

            dumpe($stmt);

            /*$_SESSION["name"] = "name";
            header("Location: ./");*/
        }

        /*$stmt = $this->pdo->prepare("SELECT * FROM `room` ORDER BY `name`");
        $stmt->execute([]);*/

    return $this->m->render("login"/*, ["rooms" => $stmt]*/);
    }
}

(new CurrentPage())->render();
