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
        $newColor = mb_strtolower($_POST["color"]);

        $newColor = htmlspecialchars($newColor);


        $stmt = $this->pdo->prepare("UPDATE employee SET color='$newColor' WHERE login='$name'");
        $stmt->execute([]);

        header("Location: ./profil.php");
    }

    protected function body(): string
    {
        return "";
    }

    private function redirect() : void {

    }
}

(new CurrentPage())->render();
