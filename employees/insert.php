<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Vložit zaměstnance";


    protected function setUp(): void
    {
        parent::setUp();

        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $job = $_POST["job"];
        $wage = $_POST["wage"];
        $room = $_POST["room"];
        $admin = $_POST["admin"];
        $color = $_POST["color"];

        $stmt = $this->pdo->prepare("INSERT INTO employee (name, surname, job, wage, room, admin, color) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $surname, $job, $wage, $room, $admin ? 1 : 0, $color]);

    }

    protected function body(): string
    {
        return $this->m->render("employeeSuccess", ["message" => "Employee added"]);
    }
}

(new CurrentPage())->render();
