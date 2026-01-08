<?php

require __DIR__ . "/BaseRepository.php";

abstract class UserRepository extends BaseRepository
{
    // private object $obj;
    protected function whichTable(): string
    {
        return 'users';
    }

    protected function mapToProp(): array
    {
        return [
            'username' => 'user',
            'password_hash' => 'pass',
            'first_name' => 'fName',
            'last_name' => 'lName',
            'phone' => 'phone',
            'email' => 'email'
        ];
    }

    protected function userObjToArray(): array
    {
        $reflection = new ReflectionClass(parent::class);
        $userAssocArr = [];

        foreach ($reflection->getProperties() as $prop) {
            $propName = $prop->getName();
            if ($propName === 'id') continue;
            $header = array_merge([$propName => $propName], parent::getPropToHeader())[$propName];
            $value = $prop->getValue($this->obj);
            $propType = $prop->getType();
            $typeName = $propType ? $prop->getType()->getName() : 'string';

            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d');
                // $typeName = 'date';
            } elseif ($propName === "fk") {
                $value = $value?->getId();
                $typeName = 'int';
            } elseif ($value instanceof Gender || $value instanceof Status) {
                $value = $value->value;
                // $typeName = 'enum';
            }

            $userAssocArr[$header] = [$value, $typeName];
        }
        return $userAssocArr;
    }

    protected function userArrayToObj(array $row, ?object $oldObj = null): object
    {
        $reflection = new ReflectionClass(parent::class);
        if ($oldObj) $this->obj = $oldObj;

        foreach ($row as $h => $v) {
            $propName = array_merge([$h => $h], parent::getHeaderToProp())[$h];
            if ($propName === "id") continue;
            $value = $v;
            $prop = $reflection->getProperty($propName);
            $typeName = $prop->getType()?->getName() ?? "string";

            if ($typeName === DateTime::class) {
                $value = $v ? new DateTime($v) : null;
            } elseif ($typeName === Gender::class) {
                $value = $v ? Gender::from($v) : null;
            } elseif ($typeName === Status::class) {
                $value = $v ? Status::from($v) : null;
            } elseif ($propName === 'fk') {
                $value = $v ? (int)$v : null;
            }
            $prop->setValue($this->obj, $value);
        }
        return clone $this->obj;
    }

    public function delete(): bool
    {
        $userTableName = parent::getTableName();
        $userIdColName = parent::getIdColName();
        $id = $this->obj->getId();

        if (!$id) {
            throw new Exception("Cannot delete: object that has no ID");
        }

        $sql = <<<SQL
        DELETE FROM :userTableName
        WHERE :userIdColName = :id
        SQL;
        $dbLink = DataBase::openDB();
        $pdo = $dbLink->getPdo();
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":userTableName", $userTableName, PDO::PARAM_STR);
        $stmt->bindParam(":userIdColName", $userIdColName, PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        try {
            $res = $stmt->execute() && (bool) $stmt->rowCount();
            $dbLink->closeDB();
            return $res;
        } catch (PDOException $err) {
            $dbLink->closeDB();
            throw new Exception("\n deleting row failed" . $err->getMessage() . "\n");
        }
    }

    public function create(): bool
    {
        $isTrue = BaseRepository::create();

        $userTableName = parent::getTableName();
        // $userAssocRow = parent::objToArray();
        $assocRow = $this->userObjToArray();

        $columns = implode(', ', array_keys($assocRow));
        $phsArr = array_fill(0, count($assocRow), '?');
        $phsStr = implode(', ', $phsArr);

        $sql = <<<SQL
        INSERT INTO :userTableName ({$columns})
        VALUES ({$phsStr})
        SQL;

        $dbLink = DataBase::openDB();
        $pdo = $dbLink->getPdo();
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":userTableName", $userTableName, PDO::PARAM_STR);

        $index = 1;
        foreach ($assocRow as [$value, $typeName]) {
            $pdoType = match ($typeName) {
                'int' => PDO::PARAM_INT,
                default => PDO::PARAM_STR
            };
            $stmt->bindValue($index++, $value, $pdoType);
        }

        try {
            $stmt->execute();
            $id = $pdo->lastInsertId();
            $this->obj->setId((int)$id);
            $dbLink->closeDB();
            return $isTrue && true;
        } catch (PDOException $err) {
            $dbLink->closeDB();
            throw new Exception("\n create row failed: " . $err->getMessage() . "\n");
        }
    }

    public function update(): bool
    {
        $res = BaseRepository::update();

        $tableName = parent::getTableName();
        $assocRow = $this->userObjToArray();
        $idColName = parent::getIdColName();
        $id = $this->obj->getId();

        if (!$id) {
            throw new Exception("Cannot update: object has no ID");
        }

        $headersArr = array_keys($assocRow);
        $setArr = array_map(fn($header) => "$header = ?", $headersArr);
        $setStr = implode(', ', $setArr);

        $sql = <<<SQL
        UPDATE :tableName
        SET $setStr
        WHERE :idColName = ?
        SQL;

        $dbLink = DataBase::openDB();
        $pdo = $dbLink->getPdo();
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':tableName', $tableName, PDO::PARAM_STR);
        $stmt->bindParam(':idColName', $idColName, PDO::PARAM_STR);

        $index = 1;
        foreach ($assocRow as [$value, $typeName]) {
            $pdoType = match ($typeName) {
                'int' => PDO::PARAM_INT,
                default => PDO::PARAM_STR
            };
            $stmt->bindValue($index++, $value, $pdoType);
        }

        $stmt->bindValue($index, $id, PDO::PARAM_INT);

        try {
            $res = $res && $stmt->execute() && (bool) $stmt->rowCount();
            $dbLink->closeDB();
            return $res;
        } catch (PDOException $err) {
            $dbLink->closeDB();
            throw new Exception("\n update row failed: " . $err->getMessage() . "\n");
        }
    }

    public function read(): ?object
    {
        $id = $this->obj->getId();
        if (!$id) {
            return null;
        }


        $userTableName = parent::getTableName();
        $userIdColName = parent::getIdColName();
        $userHeaders = parent::getHeaders();

        $headers = array_slice($this->getHeaders(), 1);
        $tableName = $this->getTableName();

        $colStr = implode(", ", [...$userHeaders, ...$headers]);

        $sql = <<<SQL
        SELECT :cols
        FROM :userTableName
        JOIN :tableName
        ON :userIdColName = :idColName
        WHERE :userIdColName = :id
        SQL;

        $dbLink = DataBase::openDB();
        $pdo = $dbLink->getPdo();
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':cols', $colStr, PDO::PARAM_STR);
        $stmt->bindParam(':userTableName', $userTableName, PDO::PARAM_STR);
        $stmt->bindParam(':tableName', $tableName, PDO::PARAM_STR);
        $stmt->bindParam(':userIdColName', $userIdColName, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $row = $stmt->fetch();
            $dbLink->closeDB();

            return $row ? $this->arrayToObj($row) : null;
        } catch (PDOException $err) {
            $dbLink->closeDB();
            throw new Exception("\n read row failed: " . $err->getMessage() . "\n");
        }
    }

    public function readAll(): array
    {
        $userTableName = parent::getTableName();
        $userIdColName = parent::getIdColName();
        $userHeaders = parent::getHeaders();

        $headers = array_slice($this->getHeaders(), 1);
        $tableName = $this->getTableName();

        $colStr = implode(", ", [...$userHeaders, ...$headers]);

        $sql = <<<SQL
        SELECT :cols
        FROM :userTableName
        JOIN :tableName
        ON :userIdColName = :idColName
        SQL;

        $dbLink = DataBase::openDB();
        $pdo = $dbLink->getPdo();
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':cols', $colStr, PDO::PARAM_STR);
        $stmt->bindParam(':userTableName', $userTableName, PDO::PARAM_STR);
        $stmt->bindParam(':tableName', $tableName, PDO::PARAM_STR);
        $stmt->bindParam(':userIdColName', $userIdColName, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $dbLink->closeDB();

            $objects = [];
            foreach ($rows as $row) {
                $obj = $this->userArrayToObj($row);
                $objects[] = $this->arrayToObj($row, $obj);
            }
            return $objects;
        } catch (PDOException $err) {
            $dbLink->closeDB();
            throw new Exception("\n read all rows failed: " . $err->getMessage() . "\n");
        }
    }
}
