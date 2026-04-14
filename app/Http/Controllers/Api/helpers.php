<?php

use App\Http\Controllers\Api\helpers;

// API Response helper
function response()
{
    return new class {
        public function json($data, $status = 200)
        {
            http_response_code($status);
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
    };
}

// Request validation helper
function validateRequest($rules, $data = null)
{
    if ($data === null) {
        $data = $_POST;
    }
    
    foreach ($rules as $field => $rule) {
        if (strpos($rule, 'required') !== false && !isset($data[$field])) {
            throw new Exception("The $field field is required.");
        }
        if (strpos($rule, 'email') !== false && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("The $field field must be a valid email.");
        }
        if (strpos($rule, 'min') !== false) {
            $min = (int) preg_replace('/[^0-9]/', '', $rule);
            if (strlen($data[$field]) < $min) {
                throw new Exception("The $field field must be at least $min characters.");
            }
        }
    }
}

// Database facade - simplified version
class DB
{
    protected static $tables = [];
    protected static $lastId = 1000;
    
    public static function table($table)
    {
        if (!isset(self::$tables[$table])) {
            self::$tables[$table] = new Table($table);
        }
        return self::$tables[$table];
    }
    
    public static function insert($table, $records)
    {
        if (!isset(self::$tables[$table])) {
            self::$tables[$table] = new Table($table);
        }
        return self::$tables[$table]->insert($records);
    }
    
    public static function insertGetId($table, $record)
    {
        if (!isset(self::$tables[$table])) {
            self::$tables[$table] = new Table($table);
        }
        return self::$tables[$table]->insertGetId($record);
    }
    
    public static function where($table, $column, $value)
    {
        if (!isset(self::$tables[$table])) {
            self::$tables[$table] = new Table($table);
        }
        return self::$tables[$table]->where($column, $value);
    }
    
    public static function find($table, $id)
    {
        if (!isset(self::$tables[$table])) {
            self::$tables[$table] = new Table($table);
        }
        return self::$tables[$table]->find($id);
    }
    
    public static function all($table)
    {
        if (!isset(self::$tables[$table])) {
            self::$tables[$table] = new Table($table);
        }
        return self::$tables[$table]->all();
    }
    
    public static function deleteWhere($table, $column, $value)
    {
        if (!isset(self::$tables[$table])) {
            self::$tables[$table] = new Table($table);
        }
        return self::$tables[$table]->deleteWhere($column, $value);
    }
}

class Table
{
    private $name;
    private $data = [];
    private $lastId = 1000;
    
    public function __construct($name) { $this->name = $name; }
    
    public function insert($records)
    {
        if (!is_array($records[0])) {
            $records = [$records];
        }
        
        foreach ($records as $record) {
            $this->lastId++;
            $record['id'] = $this->lastId;
            $record['created_at'] = date('Y-m-d H:i:s');
            $record['updated_at'] = date('Y-m-d H:i:s');
            $this->data[$this->lastId] = $record;
        }
        
        return $this->data;
    }
    
    public function insertGetId($record)
    {
        $this->lastId++;
        $record['id'] = $this->lastId;
        $record['created_at'] = date('Y-m-d H:i:s');
        $record['updated_at'] = date('Y-m-d H:i:s');
        $this->data[$this->lastId] = $record;
        return $this->lastId;
    }
    
    public function where($column, $value)
    {
        $result = [];
        foreach ($this->data as $id => $record) {
            if (isset($record[$column]) && $record[$column] == $value) {
                $result[$id] = $record;
            }
        }
        return new QueryResult($result);
    }
    
    public function find($id)
    {
        return $this->data[$id] ?? null;
    }
    
    public function all()
    {
        return array_values($this->data);
    }
    
    public function deleteWhere($column, $value)
    {
        $count = 0;
        foreach ($this->data as $id => $record) {
            if (isset($record[$column]) && $record[$column] == $value) {
                unset($this->data[$id]);
                $count++;
            }
        }
        return $count;
    }
}

class QueryResult
{
    private $data;
    
    public function __construct($data) { $this->data = $data; }
    
    public function delete()
    {
        foreach (array_keys($this->data) as $id) {
            // Find which table contains this data
            foreach ($GLOBALS['DB_tables'] ?? [] as $tableObj) {
                if (isset($tableObj->data[$id])) {
                    unset($tableObj->data[$id]);
                    break;
                }
            }
        }
        return count($this->data);
    }
    
    public function get()
    {
        return array_values($this->data);
    }
}

// Facade helpers
function now() { return date('Y-m-d H:i:s'); }

// Exception handler
set_exception_handler(function($e) {
    response()->json(['error' => $e->getMessage()], 400);
});