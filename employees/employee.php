<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Výpis zaměstnance";
    private string $warning;
    private ?stdClass $employee = null;
    private ?stdClass $room = null;
    private array $rooms = [];

    protected function setUp(): void
    {
        parent::setUp();

        BasePage::checkLogined();

        $employee_id = filter_input(INPUT_GET, "employeeId");

        if($employee_id) {
            $stmt = $this->pdo->prepare("SELECT * FROM `employee` WHERE employee_id=$employee_id");
            $stmt->execute([]);

            $employeeExist = true;
            if($stmt->rowCount() == 0) {
                $this->warning = "404 Not Found";
                $employeeExist = false;
            } else {
                $this->warning = "";
            }

            if($employeeExist) {
                $this->employee = $stmt->fetch();

                $stmt2 = $this->pdo->prepare("SELECT * FROM `room` WHERE room_id=?");
                $stmt2->execute([$this->employee->room]);
                $this->room = $stmt2->fetch();

                $stmt3 = $this->pdo->prepare("SELECT * FROM `key` WHERE employee=$employee_id");
                $stmt3->execute([]);
                foreach ($stmt3->fetchAll() as $key) {
                    $id = $key->room;
                    $stmt4 = $this->pdo->prepare("SELECT name FROM `room` WHERE room_id=$id");
                    $stmt4->execute([]);


                    foreach ($stmt4->fetchAll() as $e) {
                        $this->rooms[] = ["id" => $id, "name" => $e->name];
                    }
                }
            }
        } else {
            $this->warning = "400 Bad Request";
        }

    }

    protected function body(): string
    {
        return $this->m->render("employeeDetail", ["employee" => $this->employee, "roomName" => $this->room->name, "keys" => $this->rooms, "warning" => $this->warning]);
    }
}

(new CurrentPage())->render();
