<?php
session_start();
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Výpis místností";

    protected function body(): string
    {
        RoomModel::checkLogined();

        $room_id = $_GET["roomId"];
        dump($room_id);

        $stmt = $this->pdo->prepare("SELECT * FROM `room` WHERE room_id=$room_id");
        $stmt->execute([]);

        //TODO: room template
        return $this->m->render("roomList", ["rooms" => $stmt, "admin" => $_SESSION["admin"]]);
    }
}

(new CurrentPage())->render();
