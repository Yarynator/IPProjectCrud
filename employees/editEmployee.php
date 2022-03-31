<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage
{
    protected string $title = "Edit Employee";
    private stdClass $employee;
    private array $keys = [];
    private array $rooms;
    private array $avaibleRooms = [];

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

        $stmt2 = $this->pdo->prepare("SELECT room_id, name, no FROM room");
        $stmt2->execute([]);
        $this->rooms = $stmt2->fetchAll();

        $keysStmt = $this->pdo->prepare("SELECT * FROM `key` WHERE employee=?");
        $keysStmt->execute([$id]);

        foreach ($keysStmt->fetchAll() as $key)
        {
            $roomStmt = $this->pdo->prepare("SELECT name, no FROM room WHERE room_id=?");
            $roomStmt->execute([$key->room]);

            $row = $roomStmt->fetch();

            $this->keys[] = ["id" => $key->key_id, "room" => $row->name, "employee_id" => $id, "no" =>$row->no];
        }

        foreach ($this->rooms as $r) {
            $t = true;
            foreach ($this->keys as $k) {
                if ($r->name === $k["room"])
                    $t = false;
            }
            if ($t) {
                $this->avaibleRooms[] = ["room" => $r->room_id, "employee" => $id, "room_name" => $r->name, "no" => $r->no];
            }
        }
    }

    protected function body(): string
    {
        return $this->m->render("editEmployee", ["employees" => $this->employee, "rooms" => $this->rooms, "avaibleRooms" => $this->avaibleRooms, "keys" => $this->keys]);
    }
}

(new CurrentPage())->render();
