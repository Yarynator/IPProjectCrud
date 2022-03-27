<?php
session_start();
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Výpis místností";

    protected function body(): string
    {
        RoomModel::checkLogined();

        $stmt = $this->pdo->prepare("SELECT * FROM `employee` ORDER BY `name`");
        $stmt->execute([]);

        $employees = [];
        foreach ($stmt->fetchAll() as $row)
        {
            $room_id = $row->room;
            $stmt = $this->pdo->prepare("SELECT * FROM `room` WHERE room_id=$room_id");
            $stmt->execute([]);
            $room = $stmt->fetch()->name;

            $employees[] = [
                "employee_id" => $row->employee_id,
                "name" => $row->name,
                "surname" => $row->surname,
                "job" => $row->job,
                "wage" => $row->wage,
                "room" => $room,
                "room_id" => $room_id
            ];
        }

        return $this->m->render("employeeList", ["employees" => $employees, "room" => "", "admining" => $_SESSION["admin"]]);
    }
}

(new CurrentPage())->render();
