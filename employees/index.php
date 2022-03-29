<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Výpis místností";
    private array $employees;

    protected function setUp(): void
    {
        parent::setUp();

        BasePage::checkLogined();

        $stmt = $this->pdo->prepare("SELECT * FROM `employee` ORDER BY `name`");
        $stmt->execute([]);

        foreach ($stmt->fetchAll() as $row)
        {
            $room_id = $row->room;
            $stmt = $this->pdo->prepare("SELECT * FROM `room` WHERE room_id=$room_id");
            $stmt->execute([]);
            $room = $stmt->fetch()->name;

            $this->employees[] = [
                "employee_id" => $row->employee_id,
                "name" => $row->name,
                "surname" => $row->surname,
                "job" => $row->job,
                "wage" => $row->wage,
                "room" => $room,
                "room_id" => $room_id
            ];
        }
    }

    protected function body(): string
    {



        return $this->m->render("employeeList", ["employees" => $this->employees, "room" => "", "admining" => isset($_SESSION["admin"]) ? $_SESSION["admin"] : false]);
    }
}

(new CurrentPage())->render();
