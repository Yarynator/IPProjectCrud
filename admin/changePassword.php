<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Změnit heslo";
    private string $warning;
    private string $mess;

    protected function setUp(): void
    {
        parent::setUp();

        RoomModel::checkLogined();

        $this->warning = isset($_SESSION["changePassWarn"]) ? $_SESSION["changePassWarn"] : "";
        $this->message = isset($_SESSION["changePassMess"]) ? $_SESSION["changePassMess"] : "";
        $_SESSION["changePassWarn"] = "";
        $_SESSION["changePassMess"] = "";
    }

    protected function body(): string
    {

        return $this->m->render("changePassword", ["warning" => $this->warning, "message" => $this->message]);
    }
}

(new CurrentPage())->render();
