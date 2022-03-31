<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage
{
    protected string $title = "Remvoe Employee";

    protected function setUp(): void
    {
        parent::setUp();

        BasePage::checkLogined();

        $employee = $_POST["id"];

        $stmtKey = $this->pdo->prepare("DELETE FROM `key` WHERE employee=?");
        $stmtKey->execute([$employee]);

        $stmtEmployee = $this->pdo->prepare("DELETE FROM employee WHERE employee_id=?");
        $stmtEmployee->execute([$employee]);
    }

    protected function body(): string
    {
        return $this->m->render("employeeSuccess", ["message" => "Employee remove success"]);
    }
}

(new CurrentPage())->render();
