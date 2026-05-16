<?php

namespace Tests\Feature;

use Tests\TestCase;

class TodoPageTest extends TestCase
{
    public function testTodoPageIsAccessible(): void
    {
        $this->get('/todo')
            ->assertOk()
            ->assertSee('Formulaire de tâches adapté au design Offitrade', false)
            ->assertSee('id="todo-form"', false)
            ->assertSee('id="todo-list"', false);
    }
}
