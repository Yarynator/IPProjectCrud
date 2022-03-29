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

        $name = $_POST["name"];
        $stmt = $this->pdo->prepare("SELECT password, admin FROM employee WHERE login=?");
        $stmt->execute([$name]);

        $row = $stmt->fetch();

        $password = $row->password;
        $admin = $row->admin;

        if($password) {
            if(password_verify($_POST["password"], $password)){
                $_SESSION["name"] = $name;
                $_SESSION["admin"] = $admin;
            } else{
                //chybova hlaska
                $this->warning = "Špatné heslo!";
                $this->name = $name;
            }
        } else {
            $this->warning = "Špatné uživatelské jméno!";
        }

        $_SESSION["loginWarn"] = $this->warning;
        $_SESSION["loginName"] = $this->name;

        header("Location: ./");
    }

    protected function body(): string
    {
        return "";
    }

    private function redirect() : void {

    }
}

(new CurrentPage())->render();
