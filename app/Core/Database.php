<?php

namespace Core;

class Database
{
    public $connection;
    public \mysqli_stmt $statement;

    public function __construct($config, $username = 'root', $password = '')
    {
        $host = $config['host'];
        $dbname = $config['dbname'];
        $charset = $config['charset'];

        $this->connection = new \mysqli($host, $username, $password, $dbname);
        $this->connection->set_charset($charset);

        if ($this->connection->connect_error) {
            die('Connection failed: ' . $this->connection->connect_error);
        }
    }

    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);

        if ($this->statement === false) {
            die('Query preparation failed: ' . $this->connection->error);
        }


        if (!empty($params)) {
            $types = $this->getTypes($params);
            $this->statement->bind_param($types, ...$params);
        }


        $this->statement->execute();

        return $this;
    }

    public function find(): ?array
    {
        $result = $this->statement->get_result();
        $this->close();
        return $result->fetch_assoc();
    }

    public function findAll(): array
    {
        $result = $this->statement->get_result();
        $this->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function newId(): ?int
    {
        $result = $this->statement->insert_id;
        $this->close();
        return $result === 0 ? null : $result;
    }

    public function wasSuccessful(): bool
    {
        return $this->statement->affected_rows > 0;
    }

    public function close()
    {
        $this->statement->close();
    }

    private function getTypes(array $params)
    {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } elseif (is_string($param) || is_null($param)) {
                $types .= 's';
            } elseif (is_bool($param)) {
                $types .= 'i';
                $param = (int) $param;
            } else {
                abort(500);
            }
        }
        return $types;
    }
}
