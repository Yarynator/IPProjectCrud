<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Výpis místností";

    protected function body(): string
    {
        RoomModel::checkLogined();

        $stmt = $this->pdo->prepare("SELECT * FROM `room` ORDER BY `name`");
        $stmt->execute([]);

        return $this->m->render("roomList", ["rooms" => $stmt, "admin" => isset($_SESSION["admin"]) ? $_SESSION["admin"] : false]);
    }
}

(new CurrentPage())->render();
