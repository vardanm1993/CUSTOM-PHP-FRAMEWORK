<?php

namespace App\Http\Controllers;

use Core\App;
use Core\Database;
use Core\Exceptions\ContainerException;
use Core\Exceptions\NotFoundTemplate;
use Core\Redirect;
use Core\Request;
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
     */
    public function store()
    {
        $data = [
            'name' => 'name',
            'description' => 'description'
        ];

        $db = App::resolve(Database::class);

        $db->query("
            CREATE TABLE IF NOT EXISTS test (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");

        $db->query("INSERT INTO test (name, description) VALUES (:name, :description)", $data);

        redirect('/test/1');
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
        $test = App::resolve(Database::class)
            ?->query("SELECT * FROM test WHERE id = :id", ['id' => $id])->find();

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