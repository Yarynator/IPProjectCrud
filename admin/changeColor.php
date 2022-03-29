<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {
    protected string $title = "Změnit barvu";

    protected function body(): string
    {

        BasePage::checkLogined();

        return $this->m->render("changeColor");
    }
}

(new CurrentPage())->render();
