<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage
{
    protected string $title = "Remove Key";
    protected function setUp(): void
    {
        parent::setUp();

        BasePage::checkLogined();

        $employee = $_POST["employee"];
        $key = $_POST["key"];

        $stmt = $this->pdo->prepare("INSERT INTO `key` (employee, room) VALUES ((SELECT employee_id FROM employee WHERE employee_id=?), (SELECT room_id FROM room WHERE room_id=?))");
        $stmt->execute([$employee, $key]);
    }

    protected function body(): string
    {
        return $this->m->render("employeeSuccess", ["message" => "Key added successfully"]);
    }
}

(new CurrentPage())->render();
