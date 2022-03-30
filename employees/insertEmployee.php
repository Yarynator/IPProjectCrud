<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Vložit zaměstnance";

    protected function setUp(): void
    {
        parent::setUp();

        BasePage::checkLogined();
    }

    protected function body(): string
    {
        $stmt =  $this->pdo->prepare("SELECT room_id, name FROM room");
        $stmt->execute([]);

        return $this->m->render("employeeForm", ["rooms" => $stmt]);
    }
}

(new CurrentPage())->render();
