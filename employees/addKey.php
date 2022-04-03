<?php
require "../includes/bootstrap.inc.php";

final class CurrentPage extends BaseDBPage {

    const STATE_DELETE_REQUESTED= 4;
    const STATE_PROCESSED = 3;

    const RESULT_SUCCESS = 1;
    const RESULT_FAIL = 2;

    private int $state;
    private int $result = 0;

    //když nepřišla data a není hlášení o výsledku, chci zobrazit formulář
    //když přišla data
    //validuj
    //když jsou validní
    //ulož a přesměruj zpět (PRG)
    //jinak vrať do formuláře
    public function __construct()
    {
        BasePage::checkLogined();

        parent::__construct();
        $this->title = "Add Key";
    }


    protected function setUp(): void
    {
        parent::setUp();

        if(!$_SESSION["admin"])
            header("Location: ./");

        $this->state = $this->getState();

        if ($this->state == self::STATE_PROCESSED) {
            //reportuju

        } elseif ($this->state == self::STATE_DELETE_REQUESTED) {
            //přišla data
            //načíst

            $employee = filter_input(INPUT_POST, "employee");
            $room = filter_input(INPUT_POST, "room");

            if($employee && $room)
            {
                $stmt = $this->pdo->prepare("INSERT INTO `key` (employee, room) VALUES (:employee, :room)");
                $stmt->bindParam(":employee", $employee);
                $stmt->bindParam(":room", $room);

                if(!$stmt->execute())
                    $this->redirect(self::RESULT_FAIL);

                $this->redirect(self::RESULT_SUCCESS);
            } else {
                $this->redirect(self::RESULT_FAIL);
            }



        }

    }


    protected function body(): string
    {
        if ($this->state == self::STATE_PROCESSED){
            //vypiš výsledek zpracování
            if ($this->result == self::RESULT_SUCCESS) {
                return $this->m->render("roomSuccess", ['message' => "Key added successfully."]);
            } else {
                return $this->m->render("roomFail", ['message' => "Key added failed."]);
            }
        }
        return "";
    }

    protected function getState() : int
    {
        //když mám result -> zpracováno
        $result = filter_input(INPUT_GET, 'result', FILTER_VALIDATE_INT);
        if($result) {
            if ($result == self::RESULT_SUCCESS) {
                $this->result = self::RESULT_SUCCESS;
                return self::STATE_PROCESSED;
            } elseif ($result == self::RESULT_FAIL) {
                $this->result = self::RESULT_FAIL;
                return self::STATE_PROCESSED;
            }

            return self::STATE_PROCESSED;
        } else{
            return self::STATE_DELETE_REQUESTED;
        }
    }

    private function redirect(int $result) : void {
        $location = strtok($_SERVER['REQUEST_URI'], '?');
        header("Location: {$location}?result={$result}");
        exit;
    }

}

(new CurrentPage())->render();
