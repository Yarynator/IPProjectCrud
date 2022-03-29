<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage
{
    protected string $title = "Edit";

    private int $state;

    protected function setUp(): void
    {
        parent::setUp();

        $id = $_POST["id"];
        $password = password_hash("Hesl0", PASSWORD_BCRYPT);

        $stmt = $this->pdo->prepare("UPDATE employee SET password=? WHERE employee_id='$id'");
        $stmt->execute([$password]);

    }

    protected function body(): string
    {
        return $this->m->render("employeeSuccess", ['message' => "Password reset successfully."]);
    }

}

(new CurrentPage())->render();
