<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Login";
    private string $warning = "";
    private string $message = "";

    protected function setUp(): void
    {
        parent::setUp();

        $name = $_SESSION["name"];
        $stmt = $this->pdo->prepare("SELECT password FROM employee WHERE login='$name'");
        $stmt->execute([]);

        $password = $stmt->fetch()->password;

        if(password_verify($_POST["actualPassword"], $password))
        {
            if($_POST["password"] == $_POST["passwordAgain"])
            {
                $newPassword = password_hash($_POST["password"], PASSWORD_BCRYPT);
                $stmt = $this->pdo->prepare("UPDATE employee SET password='$newPassword' WHERE login='$name'");
                $stmt->execute([]);

                $this->message = "Heslo bylo úspěšně změněno.";
            } else {
                $this->warning = "Hesla se neshodují!";
            }
        }else {
            $this->warning = "Aktuální heslo není správné!";
        }

        $_SESSION["changePassWarn"] = $this->warning;
        $_SESSION["changePassMess"] = $this->message;
        header("Location: ./changePassword");
    }

    protected function body(): string
    {
        return "";
    }

    private function redirect() : void {

    }
}

(new CurrentPage())->render();
