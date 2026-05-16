<?php

namespace Tests\Feature;

use Tests\TestCase;

class TodoPageTest extends TestCase
{
    public function test_todo_page_is_accessible(): void
    {
        $this->get('/todo')->assertOk();
    }
}

