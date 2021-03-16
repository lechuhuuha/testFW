<?php

namespace Lchh;


class DatabaseTable
{
    private $pdo;
    private $table;
    private $primaryKey;
    private $className;
    private $constructorArgs;

    public function __construct(
        \PDO $pdo,
        string $table,
        string $primaryKey,
        string $className = '\stdClass',
        array $constructorArgs = []
    ) {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->className = $className;
        $this->constructorArgs = $constructorArgs;
    }
    /**
     * Query the db with provided sql and optional param
     * @param string $sql
     * @param array $parameters
     */
    private function query($sql, $parameters = [])
    {
        $query = $this->pdo->prepare($sql);

        $query->execute($parameters);
        return $query;
    }
    /**
     * Find all the records in the db 
     * 
     */
    public function findAll()
    {
        $result = $this->query('SELECT *
        FROM ' . $this->table);
        return ($result->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs));
    }
    /**
     * Find the last record of the table provided
     * @return mixed
     */
    public function lastRecord()
    {
        $query = 'SELECT id   FROM ' . $this->table . ' ORDER BY id DESC LIMIT 1 ';
        $result = $this->query($query);
        return $result->fetch();
    }
    /**
     * Get the total record of the provided table
     * @return mixed
     */
    public function total()
    {
        $query = $this->query('SELECT COUNT(*)
        FROM `' . $this->table . '`');
        $row = $query->fetch();
        return $row[0];
    }

    /**
     * Find the record by primary id 
     * @param mixed $value
     * @return object 
     */
    public function findById($value)
    {

        $query = 'SELECT * FROM `' . $this->table . '` WHERE
        `' . $this->primaryKey . '` = :value';
        $parameters = [
            'value' => $value
        ];
        $query = $this->query($query, $parameters);
        // return $query->fetch();
        return $query->fetchObject($this->className, $this->constructorArgs);
    }
    /**
     * Find the record by column and value provided
     * @param string $column
     * @param mixed $value
     * @return mixed 
     */
    public function find($column, $value)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' .
            $column . ' = :value';
        $parameters = [
            'value' => $value
        ];
        $query = $this->query($query, $parameters);
        return $query->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
    }
    /**
     * Insert into table provided
     * @param mixed $fields
     * @return mixed lastInsertId
     */
    private function insert($fields)
    {
        $query = 'INSERT INTO `' . $this->table . '` (';
        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '`,';
        }
        $query = rtrim($query, ',');
        $query .= ') VALUES (';
        foreach ($fields as $key => $value) {
            $query .= ':' . $key . ',';
        }
        $query = rtrim($query, ',');
        $query .= ')';
        $fields = $this->processDates($fields);
        $this->query($query, $fields);
        return $this->pdo->lastInsertId();
    }


    /**
     * Update the record by fields provided
     * @param mixed $fields
     * @return void
     */
    private function update($fields)
    {
        $query = ' UPDATE `' . $this->table . '` SET ';
        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '` = :' . $key . ',';
        }
        $query = rtrim($query, ',');
        $query .= ' WHERE `' . $this->primaryKey . '`
        = :primaryKey';
        // Set the :primaryKey variable
        $fields['primaryKey'] = $fields['id'];
        $fields = $this->processDates($fields);
        $this->query($query, $fields);
    }

    /**
     * Delete the record with the id provided
     * @param mixed $id
     * @return void
     */
    public function delete($id)
    {
        $parameters = [':id' => $id];
        $this->query('DELETE FROM `' . $this->table . '` WHERE
        `' . $this->primaryKey . '` = :id', $parameters);
    }
    /**
     * Delete the record with the column and value provided
     * @param mixed $column 
     * @param mixed $value
     * @return void
     */
    public function deleteWhere($column, $value)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $column . '=:value';
        $parameters = [
            'value' => $value
        ];
        $query = $this->query($query, $parameters);
    }
    /**
     * Process Date with fields provided
     * @param mixed $fields
     * @return mixed $fields
     */
    private function processDates($fields)
    {
        foreach ($fields as $key => $value) {
            if ($value instanceof \DateTime) {
                $fields[$key] = $value->format('Y-m-d');
            }
        }
        return $fields;
    }
    /**
     * If its has id then update or insert 
     * @param mixed $record
     * @return mixed $entity
     */
    public function save($record)
    {
        $entity = new $this->className(...$this->constructorArgs);
        try {
            // print_r($this->primaryKey);
            // print_r($this->className);

            // print_r($this->primaryKey);
            if ($record[$this->primaryKey] == '') {
                $record[$this->primaryKey] = null;
            }
            $insertId =  $this->insert($record);
            $entity->{$this->primaryKey} = $insertId;
        } catch (\PDOException $e) {
            $this->update($record);
        }
        foreach ($record as $key => $value) {
            if (!empty($value)) {
                $entity->$key = $value;
            }
        }
        return $entity;
    }
}
