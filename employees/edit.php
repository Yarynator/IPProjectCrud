<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage
{
    protected string $title = "Edit";

    private int $id;
    private string $name;
    private string $surname;
    private string $job ;
    private int $wage;
    private int $room_id;
    private string $login;
    private bool $isAdmin;

    private const RESULT_SUCCESS = 1;
    private const RESULT_FAIL = 2;

    private int $state;

    protected function setUp(): void
    {
        parent::setUp();

        $this->id = $_POST["id"];
        $this->name = $_POST["name"];
        $this->surname = $_POST["surname"];
        $this->job = $_POST["job"];
        $this->wage = $_POST["wage"];
        $this->room_id = $_POST["room"];
        $this->login = $_POST["login"];
        $this->isAdmin = $_POST["admin"] ? true : false;

        if($this->validate())
        {
            $this->state = self::RESULT_SUCCESS;
            $stmt = $this->pdo->prepare("UPDATE employee SET name=?, surname=?, job=?, wage=?, room=?, login=?, admin=? WHERE employee_id=?");
            $stmt->execute([$this->name, $this->surname, $this->job, $this->wage, $this->room_id, $this->login, $this->isAdmin ? 1 : 0, $this->id]);
        } else {
            $this->state = self::RESULT_FAIL;
        }

    }

    protected function body(): string
    {
        if($this->state == self::RESULT_SUCCESS)
            return $this->m->render("employeeSuccess", ['message' => "Employee edited successfully."]);
        else
            return $this->m->render("employeeFail", ['message' => "Employee edit failed."]);
    }

    private function validate() : bool {
        $isOk = true;

        if(!$this->name)
            $isOk = false;
        else if(!$this->surname)
            $isOk = false;
        else if(!$this->job)
            $isOk = false;
        else if(!$this->wage)
            $isOk = false;

        return $isOk;
    }

}

(new CurrentPage())->render();
