<?php
declare(strict_types=1);

namespace Tests\Traits;


use Illuminate\Foundation\Testing\TestResponse;


trait ValidationsTest
{
    public function assertValidationData(TestResponse $response, array $fields, string $rule, array $rulesParams = [])
    {
        $response->assertStatus(422);
        $response->assertJsonValidationErrors($fields);
        foreach ($fields as $field) {
            $fieldName = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
                trans("validation.{$rule}", ['attribute' => $fieldName] + $rulesParams)
            ]);
        }
    }
}
