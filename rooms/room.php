<?php
session_start();
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Výpis místností";

    protected function body(): string
    {
        RoomModel::checkLogined();

        $room_id = "";
        $employees = [];
        $warning = "";

        if(isset($_GET["roomId"]))
            $room_id = filter_input(INPUT_GET, "roomId");
        else
            $warning = "400 Bad Request";

        if($room_id) {
            $stmt = $this->pdo->prepare("SELECT * FROM `room` WHERE room_id=$room_id");
            $stmt->execute([]);
            $stmt->rowCount() == 0 ? $warning = "404 Not Found" : "";

            $stmt2 = $this->pdo->prepare("SELECT * FROM `key` WHERE room=$room_id");
            $stmt2->execute([]);

            foreach ($stmt2->fetchAll() as $key) {
                $id = $key->employee;
                $stmt3 = $this->pdo->prepare("SELECT name, surname FROM `employee` WHERE employee_id=$id");
                $stmt3->execute([]);

                foreach ($stmt3->fetchAll() as $e) {
                    $employees[] = ["id" => $id, "name" => $e->name, "surname" => $e->surname];
                }
            }
        }

        //TODO: room template
        return $this->m->render("roomDetail", ["room" => $stmt, "employees" => $employees, "warning" => $warning]);
    }
}

(new CurrentPage())->render();
