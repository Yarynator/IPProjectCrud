<?php
require "./includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage
{
    protected string $title = "Redirect";
    protected function setUp(): void
    {
        parent::setUp();

        header("Location: ./rooms");
    }

    protected function body(): string
    {
        return "";
    }
}

(new CurrentPage())->render();
