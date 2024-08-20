<?php

namespace App\Http\Controllers;

use App\Http\Models\Test;
use Core\App;
use Core\Database;
use Core\Exceptions\ContainerException;
use Core\Exceptions\NotFoundTemplate;
use Core\Redirect;
use Core\Request;
use Core\Validator;
use JsonException;
use ReflectionException;

class TestController
{
    public function __construct(public Request $request)
    {
    }

    /**
     * @throws NotFoundTemplate
     */
    public function test(): string
    {
        return view('index', ['method' => $this->request->getMethod()]);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function testRedirect(): Redirect
    {
        return redirect_back()->with('id', 1);
    }


    /**
     * @throws ContainerException
     * @throws ReflectionException
     * @throws JsonException
     */
    public function store(): Redirect
    {

        $validator = Validator::make($this->request->all(), [
            'name' => 'required|string|min:3|max:20',
            'description' => 'required|string'
        ]);

        $data = $validator->validated();

        if ($validator->fails()) {
            redirect('test/1');
        }

        $data = [
            'name' => 'Lorem Ipsum',
            'description' => 'lorem ipsum lorem ipsum lorem ipsum lorem ipsum',
        ];

        $db = App::resolve(Database::class);

        $db->execute("
            CREATE TABLE IF NOT EXISTS tests (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");

        $test = Test::create($data);

        return redirect('/test/2');
    }

    /**
     * @param $id
     * @return string
     * @throws ContainerException
     * @throws NotFoundTemplate
     * @throws ReflectionException
     */
    public function show($id): string
    {
        $test = Test::find($id);
        return view('show', compact('test'));
    }

    /**
     * @throws NotFoundTemplate
     */
    public function auth(): string
    {
        return view('index');
    }
}