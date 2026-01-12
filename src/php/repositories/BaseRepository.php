<?php

abstract class BaseRepository
{
    private readonly string $tableName;
    private readonly array $headers;
    private readonly string $idColName;
    protected object $obj;
    protected array $headerToProp = [];
    protected array $propToHeader = [];

    public function __construct(object $obj)
    {
        $this->tableName = $this->whichTable();
        $this->headers = $this->fetchHeaders();
        $this->idColName = $this->getHeaders()[0];
        $this->obj = $obj;
        $this->headerToProp = $this->mapToProp();
        $this->propToHeader = array_flip($this->headerToProp);
    }

    public abstract function whichTable(): string;

    public abstract function mapToProp(): array;

    // protected function normalizePropName(string $propName): string
    // {
    //     $map = $this->getHeaderToProp();
    //     return $map[$propName] ?? $propName;
    // }


    public function getTableName(): string
    {
        return $this->tableName;
    }
    public function getHeaders(): array
    {
        return $this->headers;
    }
    public function getIdColName(): string
    {
        return $this->idColName;
    }
    public function getHeaderToProp(): array
    {
        return $this->headerToProp;
    }
    public function getPropToHeader(): array
    {
        return $this->propToHeader;
    }

    private function fetchHeaders(): array
    {
        $db = DataBase::getDBName();
        $tableName = $this->getTableName();
        $sql = <<<SQL
        SELECT COLUMN_NAME
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = :db
        AND TABLE_NAME = :tableName
        ORDER BY ORDINAL_POSITION
        SQL;

        $dbLink = DataBase::openDB();
        $pdo = $dbLink->getPdo();
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":db", $db, PDO::PARAM_STR);
        $stmt->bindParam(":tableName", $tableName, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $res = array_values($stmt->fetchAll(PDO::FETCH_COLUMN));
            $dbLink->closeDB();
            return $res;
        } catch (PDOException $err) {
            $dbLink->closeDB();
            throw new Exception("\n fetching headers failed: " . $err->getMessage() . "\n");
        }
    }

    public function objToArray(): array
    {
        $reflection = new ReflectionClass($this->obj);
        $assocArr = [];

        foreach ($reflection->getProperties() as $prop) {
            $propName = $prop->getName();
            if ($propName === 'id') continue;
            $header = array_merge([$propName => $propName], $this->getPropToHeader())[$propName];
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

            $assocArr[$header] = [$value, $typeName];
        }
        return $assocArr;
    }

    public function arrayToObj(array $row, ?object $oldObj = null): object
    {
        $reflection = new ReflectionClass($this->obj);
        if ($oldObj) $this->obj = $oldObj;

        foreach ($row as $h => $v) {
            $propName = array_merge([$h => $h], $this->getHeaderToProp())[$h];
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
        $tableName = $this->getTableName();
        $idColName = $this->getIdColName();
        $id = $this->obj->getId();

        if (!$id) {
            throw new Exception("Cannot delete: object that has no ID");
        }

        $sql = <<<SQL
        DELETE FROM :tableName
        WHERE :idColName = :id
        SQL;
        $dbLink = DataBase::openDB();
        $pdo = $dbLink->getPdo();
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":tableName", $tableName, PDO::PARAM_STR);
        $stmt->bindParam(":idColName", $idColName, PDO::PARAM_STR);
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
        $tableName = $this->getTableName();
        $assocRow = $this->objToArray();

        $columns = implode(', ', array_keys($assocRow));
        $phsArr = array_fill(0, count($assocRow), '?');
        $phsStr = implode(', ', $phsArr);

        $sql = <<<SQL
        INSERT INTO :tableName ({$columns})
        VALUES ({$phsStr})
        SQL;

        $dbLink = DataBase::openDB();
        $pdo = $dbLink->getPdo();
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":tableName", $tableName, PDO::PARAM_STR);

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
            return true;
        } catch (PDOException $err) {
            $dbLink->closeDB();
            throw new Exception("\n create row failed: " . $err->getMessage() . "\n");
        }
    }

    public function update(): bool
    {
        $tableName = $this->getTableName();
        $assocRow = $this->objToArray();
        $idColName = $this->getIdColName();
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
            $res = $stmt->execute() && (bool) $stmt->rowCount();
            $dbLink->closeDB();
            return $res;
        } catch (PDOException $err) {
            $dbLink->closeDB();
            throw new Exception("\n update row failed: " . $err->getMessage() . "\n");
        }
    }

    public function read(): ?object
    {
        $tableName = $this->getTableName();
        $idColName = $this->getIdColName();
        $id = $this->obj->getId();

        if (!$id) {
            return null;
        }

        $sql = <<<SQL
        SELECT * FROM :tableName
        WHERE :idColName = ?
        SQL;

        $dbLink = DataBase::openDB();
        $pdo = $dbLink->getPdo();
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':tableName', $tableName, PDO::PARAM_STR);
        $stmt->bindParam(':idColName', $idColName, PDO::PARAM_STR);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

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
        $tableName = $this->getTableName();

        $sql = <<<SQL
        SELECT * FROM :tableName
        SQL;

        $dbLink = DataBase::openDB();
        $pdo = $dbLink->getPdo();
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':tableName', $tableName, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $dbLink->closeDB();

            $objects = [];
            foreach ($rows as $row) {
                $objects[] = $this->arrayToObj($row);
            }
            return $objects;
        } catch (PDOException $err) {
            $dbLink->closeDB();
            throw new Exception("\n read all rows failed: " . $err->getMessage() . "\n");
        }
    }
}
