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
        $room = $_POST["room"];

        $stmt = $this->pdo->prepare("INSERT INTO `key` (employee, room) VALUES (?, ?)");
        $stmt->execute([$employee, $room]);
    }

    protected function body(): string
    {
        return $this->m->render("employeeSuccess", ["message" => "Key added successfully"]);
    }
}

(new CurrentPage())->render();
