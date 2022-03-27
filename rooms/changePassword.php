<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Změnit heslo";
    private string $warning = "";

    protected function body(): string
    {

        RoomModel::checkLogined();

        if(isset($_POST["submit"]))
        {
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

                    header("Location: ./profil.php");
                } else {
                    $this->warning = "Hesla se neshodují!";
                }
            }else {
                $this->warning = "Aktuální heslo není správné!";
            }
        }

        return $this->m->render("changePassword", ["warning" => $this->warning]);
    }
}

(new CurrentPage())->render();
