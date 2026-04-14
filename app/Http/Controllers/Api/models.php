<?php

// Simulated Eloquent Models for API compatibility
class Model
{
    use HasFactory;
}

trait HasFactory
{
    // Factory simulation
}

class User extends Model
{
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    
    public function tasks()
    {
        return new class {
            public function where($column, $value) { return $this; }
            public function get() { return []; }
        };
    }
}

class Task extends Model
{
    protected $fillable = ['title', 'description', 'completed'];
    
    public function user()
    {
        return new class {
            public function find($id) { return null; }
        };
    }
}

// Eloquent facades
class DB extends \Illuminate\Database\DatabaseManager {}
class Auth extends \Illuminate\Auth\AuthManager {}

// Request/Response helpers (simplified for API simulation)
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