<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Výpis místnosti";
    private string $warning;
    private ?stdClass $room = null;
    private Array $employees = [];

    protected function setUp(): void
    {
        parent::setUp();

        BasePage::checkLogined();

        $room_id = filter_input(INPUT_GET, "roomId");

        if($room_id) {
            $stmt = $this->pdo->prepare("SELECT * FROM `room` WHERE room_id=$room_id");
            $stmt->execute([]);

            $roomExist = true;
            if($stmt->rowCount() == 0) {
                $this->warning = "404 Not Found";
                $roomExist = false;
            } else {
                $this->warning = "";
            }

            if($roomExist) {
                $this->room = $stmt->fetch();
                $stmt2 = $this->pdo->prepare("SELECT * FROM `key` WHERE room=$room_id");
                $stmt2->execute([]);

                foreach ($stmt2->fetchAll() as $key) {
                    $id = $key->employee;
                    $stmt3 = $this->pdo->prepare("SELECT name, surname FROM `employee` WHERE employee_id=$id");
                    $stmt3->execute([]);

                    foreach ($stmt3->fetchAll() as $e) {
                        $this->employees[] = ["id" => $id, "name" => $e->name, "surname" => $e->surname];
                    }
                }
            }
        } else {
            $this->warning = "400 Bad Request";
        }

    }

    protected function body(): string
    {
        return $this->m->render("roomDetail", ["room" => $this->room, "employees" => $this->employees, "warning" => $this->warning]);
    }
}

(new CurrentPage())->render();
