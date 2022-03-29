<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage
{
    protected string $title = "Edit Employee";
    private stdClass $employee;
    private array $rooms;

    protected function setUp(): void
    {
        parent::setUp();

        BasePage::checkLogined();

        if(!isset($_POST["employee_id"]))
            header("Location: ./index.php");

        $id = $_POST["employee_id"];

        $stmt = $this->pdo->prepare("SELECT * FROM employee WHERE employee_id='$id'");
        $stmt->execute([]);
        $this->employee = $stmt->fetch();

        $stmt2 = $this->pdo->prepare("SELECT room_id, name FROM room");
        $stmt2->execute([]);
        $this->rooms = $stmt2->fetchAll();
    }

    protected function body(): string
    {
        return $this->m->render("editEmployee", ["employees" => $this->employee, "rooms" => $this->rooms]);
    }
}

(new CurrentPage())->render();
