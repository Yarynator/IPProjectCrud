<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Logout";

    protected function setUp(): void
    {
        parent::setUp();

        session_destroy();
        header("Location: ./");
    }

    protected function body(): string
    {
        return "";
    }
}

(new CurrentPage())->render();
