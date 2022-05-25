<?php

namespace Base;

final class User
{
    public const USER_TABLE_COLUMNS = ['email', 'first_name', 'last_name', 'age'];
    public const USER_TABLE_COLUMNS_SHOW = ['ID', 'Почта', 'Имя', 'Фамилия', 'Возраст', 'Дата создания'];

    private \PDO $connection;

    public function __construct()
    {
        try {
            $this->connection = new \PDO('mysql:host=127.0.0.1;dbname=skillbox', 'root');
        } catch (\PDOException $error) {
            print_r($error->getMessage());
            exit;
        }
    }

    public function create(array $params) : bool
    {
        $queryParams = [];
        $insert = '';
        $value = '';

       foreach ([...self::USER_TABLE_COLUMNS, 'date_created'] as $column) {
           if (empty($params[$column])) {
               return false;
           }
           $queryParams[$column] = $params[$column];
           $insert .= "`$column`,";
           $value .= ":$column,";
        }

        $insert = rtrim($insert, ',');
        $value = rtrim($value, ',');

        try {
            $requst = $this->connection->prepare("INSERT INTO users($insert) VALUES ($value)");
            $requst->execute($queryParams);
        } catch (\PDOException $error) {
            print_r($error->getMessage());
            return false;
        }

        return true;
    }

    public function update(array $params) : bool
    {
        if (empty($params['id'])) {
            return false;
        }

        $queryParams = ['id' => $params['id']];
        $sets = '';

       foreach (self::USER_TABLE_COLUMNS as $column) {
           if (empty($params[$column])) {
               return false;
           }
           $queryParams[$column] = $params[$column];
           $sets .= "`$column`=:$column,";
        }

        $sets = rtrim($sets, ',');

        try {
            $requst = $this->connection->prepare("UPDATE users SET $sets WHERE id=:id");
            $requst->execute($queryParams);
        } catch (\PDOException $error) {
            print_r($error->getMessage());
            return false;
        }

        return true;
    }

    public function delete(int $id) : bool
    {
        try {
            $requst = $this->connection->prepare('DELETE FROM users WHERE id=:id');
            $requst->execute(['id' => $id]);

            return true;
        } catch (\PDOException $error) {
            print_r($error->getMessage());
            return false;
        }
    }

    public function list() : array
    {
        try {
            $requst = $this->connection->prepare('SELECT * FROM users ORDER BY id ASC');
            $requst->execute();

            return $requst->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $error) {
            print_r($error->getMessage());
            return [];
        }
    }
}
