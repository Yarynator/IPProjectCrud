<?php
//session_start();

final class EmployeeModel
{
    public ?int $employee_id;
    public string $name;
    public string $surname;
    public string $job;
    public int $wage;
    public ?int $room;
    public ?string $login;
    public ?string $password;
    public bool $admin;

    private array $validationErrors = [];
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    public function __construct(array $employeeData = [])
    {

        $id = $employeeData['employee_id'] ?? null;
        $wage = $employeeData['wage'] ?? 0;
        $room = $employeeData['room'] ?? null;
        if (is_string($id))
            $id = filter_var($id, FILTER_VALIDATE_INT);
        if(is_string($wage))
            $wage = filter_var($wage, FILTER_VALIDATE_INT);
        if(is_string($room))
            $room = filter_var($room, FILTER_VALIDATE_INT);


        $this->employee_id = $id;
        $this->name = $employeeData['name'] ?? "";
        $this->surname = $employeeData['surname'] ?? "";
        $this->job = $employeeData['job'] ?? "";
        $this->wage = $wage;
        $this->room = $room;
        $this->login = $employeeData['login'] ?? null;
        $this->password = $employeeData['password'] ?? null;
        $this->admin = $employeeData['admin'] !== null ? $employeeData['admin'] : false;
    }

    public function validate() : bool
    {
        $isOk = true;

        if (!$this->name) {
            $isOk = false;
            $this->validationErrors['name'] = "Name cannot be empty";
        }
        if (!$this->surname) {
            $isOk = false;
            $this->validationErrors['surname'] = "Surname cannot be empty";
        }
        if (!$this->job){
            $isOk = false;
            $this->validationErrors['job'] = "Job cannot be empty";
        }
        if(!$this->login)
        {
            $this->login = null;
        }

        if(!$this->password)
        {
            $this->password = null;
        }

        return $isOk;
    }

    public function insert() : bool
    {

        $query = "INSERT INTO employee (name, surname, job, wage, room, admin) VALUES (:name, :surname, :job, :wage, :room, :admin)";
        $adminValue = $this->admin ? 1 : 0;

        $stmt = DB::getConnection()->prepare($query);


        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':surname', $this->surname);
        $stmt->bindParam(':job', $this->job);
        $stmt->bindParam(':wage', $this->wage);
        $stmt->bindParam(':room', $this->room);
        $stmt->bindParam(':admin', $adminValue);

        if (!$stmt->execute())
            return false;

        $this->employee_id = DB::getConnection()->lastInsertId();
        return true;
    }

    public function updateEmployee() : bool
    {
        $query = "UPDATE employee SET name=:name, surname=:surname, job=:job, wage=:wage, room=:room, login=:login, admin=:admin WHERE employee_id=:employeeId";
        $adminValue = $this->admin ? 1 : 0;

        $stmt = DB::getConnection()->prepare($query);
        $stmt->bindParam(':employeeId', $this->employee_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':surname', $this->surname);
        $stmt->bindParam(':job', $this->job);
        $stmt->bindParam(':wage', $this->wage);
        $stmt->bindParam(':room', $this->room);
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':admin', $adminValue);

        return $stmt->execute();
    }

    public function resetPasswordById(int $employee_id) : bool
    {
        $query = "UPDATE employee SET password=:password WHERE employee_id=:employeeId";
        $password = password_hash("Hesl0", PASSWORD_BCRYPT);

        $stmt = DB::getConnection()->prepare($query);
        $stmt->bindParam(':employeeId', $employee_id);
        $stmt->bindParam(':password', $password);

        return $stmt->execute();
    }

    public function delete() : bool
    {
        return self::deleteById($this->employee_id);
    }

    public static function deleteById(int $employee_id) : bool {

        $query = "DELETE FROM employee WHERE employee_id=:employeeId";

        $stmtKeys = DB::getConnection()->prepare("DELETE FROM `key` WHERE employee=:employeeId");
        $stmtKeys->bindParam(':employeeId', $employee_id);
        $stmtKeys->execute();

        $stmt = DB::getConnection()->prepare($query);
        $stmt->bindParam(':employeeId', $employee_id);

        return $stmt->execute();
    }

    public static function getActiveKeys(int $employee_id) : array {
        $query = "SELECT * FROM `key` WHERE employee=:employeeId";

        $stmt = DB::getConnection()->prepare($query);
        $stmt->bindParam(':employeeId', $employee_id);

        $stmt->execute();

        $keys = [];
        foreach ($stmt->fetchAll() as $key) {
            $roomStmt = DB::getConnection()->prepare("SELECT name, no FROM room WHERE room_id=:roomId");
            $roomStmt->bindParam('roomId', $key->room);

            $roomStmt->execute();

            $row = $roomStmt->fetch();

            $keys[] = ["id" => $key->key_id, "room" => $row->name, "employee_id" => employee_id, "no" =>$row->no];
        }

        return $keys;
    }

    public static function getAvaibleKeys(int $employee_id) : array {
        $roomStmt = DB::getConnection()->prepare("SELECT name, room_id, no FROM room");
        $roomStmt->execute();
        $rooms = $roomStmt->fetchAll();

        $keyStmt = DB::getConnection()->prepare("SELECT * FROM `key` WHERE employee=:employeeId");
        $keyStmt->bindParam(":employeeId", $employee_id);
        $keyStmt->execute();
        $keys = $keyStmt->fetchAll();

        $avaible = [];
        foreach ($rooms as $r) {
            $t = true;
            foreach ($keys as $k) {
                if ($r->name === $k->room)
                    $t = false;
            }
            if ($t) {
                $avaible[] = ["room" => $r->room_id, "employee" => $employee_id, "room_name" => $r->name, "no" => $r->no];
            }
        }

        return $avaible;
    }

    public static function findById(int $employee_id) : ?EmployeeModel
    {
        $query = "SELECT * FROM employee WHERE employee_id=:employeeId";

        $stmt = DB::getConnection()->prepare($query);
        $stmt->bindParam(':employeeId', $employee_id);

        $stmt->execute();

        $dbData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dbData)
            return null;

        return new self($dbData);
    }

    public static function readPostData() : EmployeeModel
    {
        return new self($_POST); //není úplně košer, nefiltruju
    }
}