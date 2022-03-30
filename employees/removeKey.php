<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage
{
    protected string $title = "Remove Key";
    protected function setUp(): void
    {
        parent::setUp();

        BasePage::checkLogined();

        $key = $_POST["key"];

        $stmt = $this->pdo->prepare("DELETE FROM `key` WHERE key_id=?");
        $stmt->execute([$key]);
    }

    protected function body(): string
    {
        return $this->m->render("employeeSuccess", ["message" => "Key removed successfully"]);
    }
}

(new CurrentPage())->render();
