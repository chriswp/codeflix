<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriaTest extends TestCase
{

    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
