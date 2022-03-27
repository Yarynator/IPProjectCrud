<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Login";
    private string $warning = "";
    private string $name = "";

    protected function body(): string
    {

        if($_SESSION["name"])
            header("Location: ./profil.php");

        if(isset($_POST["login"]))
        {

            $name = $_POST["name"];
            $stmt = $this->pdo->prepare("SELECT password, admin FROM employee WHERE login='$name'");
            $stmt->execute([]);

            $row = $stmt->fetch();

            $password = $row->password;
            $admin = $row->admin;
            
            if($password) {
                if(password_verify($_POST["password"], $password)){
                    $_SESSION["name"] = $name;
                    $_SESSION["admin"] = $admin;
                    header("Location: ./profil");
                } else{
                    //chybova hlaska
                    $this->warning = "Špatné heslo!";
                    $this->name = $name;
                }
            } else {
                $warning = "Špatné uživatelské jméno!";
            }
            
        }

        return $this->m->render("login", ["warning" => $this->warning, "name" => $this->name]);
    }
}

(new CurrentPage())->render();
