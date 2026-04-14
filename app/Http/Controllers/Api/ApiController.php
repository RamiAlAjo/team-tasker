<?php

require_once __DIR__ . '/helpers.php';

class AuthController
{
    public static function login($request)
    {
        validateRequest([
            'email' => 'required|email',
            'password' => 'required',
        ], $request);

        $users = DB::all('users');
        $user = null;
        foreach ($users as $u) {
            if ($u['email'] === $request['email'] && $u['password'] === $request['password']) {
                $user = (object)$u;
                break;
            }
        }

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $plainTextToken = bin2hex(random_bytes(20));
        
        DB::table('personal_access_tokens')->insert([
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => $user->id,
            'name' => 'api-token',
            'token' => hash('sha256', $plainTextToken),
            'abilities' => ['*'],
        ]);

        return response()->json([
            'token' => $plainTextToken,
            'user' => $user,
        ]);
    }

    public static function register($request)
    {
        validateRequest([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], $request);

        $userId = DB::insertGetId('users', [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
        ]);

        $plainTextToken = bin2hex(random_bytes(20));
        
        DB::table('personal_access_tokens')->insert([
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => $userId,
            'name' => 'api-token',
            'token' => hash('sha256', $plainTextToken),
            'abilities' => ['*'],
        ]);

        $user = DB::find('users', $userId);

        return response()->json([
            'token' => $plainTextToken,
            'user' => $user,
        ], 201);
    }

    public static function logout($request)
    {
        DB::table('personal_access_tokens')
            ->where('tokenable_id', $request['user_id'])
            ->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public static function user($request)
    {
        return response()->json($request['user']);
    }
}

class UserController
{
    public static function index()
    {
        return response()->json(DB::all('users'));
    }

    public static function show($id)
    {
        $user = DB::find('users', $id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($user);
    }
}

class TaskController
{
    public static function index()
    {
        return response()->json(DB::all('tasks'));
    }

    public static function store($request)
    {
        validateRequest([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ], $request);

        $taskId = DB::insertGetId('tasks', [
            'title' => $request['title'],
            'description' => $request['description'],
            'user_id' => $request['user_id'],
            'completed' => false,
        ]);

        return response()->json(DB::find('tasks', $taskId), 201);
    }

    public static function show($id)
    {
        $task = DB::find('tasks', $id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        return response()->json($task);
    }

    public static function update($id, $request)
    {
        $task = DB::find('tasks', $id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $data = [];
        if (isset($request['title'])) $data['title'] = $request['title'];
        if (isset($request['description'])) $data['description'] = $request['description'];
        if (isset($request['completed'])) $data['completed'] = $request['completed'];
        if (isset($request['user_id'])) $data['user_id'] = $request['user_id'];

        if (empty($data)) {
            return response()->json($task);
        }

        // Update logic would go here
        $data['updated_at'] = date('Y-m-d H:i:s');

        return response()->json($task);
    }

    public static function destroy($id)
    {
        $task = DB::find('tasks', $id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        DB::deleteWhere('tasks', 'id', $id);

        return response()->json(['message' => 'Task deleted successfully']);
    }
}

// Export classes
return [
    'AuthController' => AuthController::class,
    'UserController' => UserController::class,
    'TaskController' => TaskController::class,
];