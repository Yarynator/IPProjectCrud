<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {

    protected string $title = "Update Employee";

    protected function body(): string
    {
        return "";
    }

}

(new CurrentPage())->render();
