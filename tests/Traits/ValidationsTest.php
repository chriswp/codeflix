<?php
declare(strict_types=1);

namespace Tests\Traits;
use Illuminate\Foundation\Testing\TestResponse;

trait ValidationsTest
{
    protected function assertInvalidationDataInStoreAction(
        array $dadosEnviados,
        string $rule,
        array $rulesParams = []
    ) {
        $response = $this->json('POST', $this->routeStore(), $dadosEnviados);
        $fields = array_keys($dadosEnviados);
        $this->assertInValidationFields($response, $fields, $rule, $rulesParams);
    }

    protected function assertInvalidationDataInUpdateAction(
        array $dadosEnviados,
        string $rule,
        array $rulesParams = []
    ) {
        $response = $this->json('PUT', $this->routeUpdate(), $dadosEnviados);
        $fields = array_keys($dadosEnviados);
        $this->assertInValidationFields($response, $fields, $rule, $rulesParams);
    }

    protected function assertInValidationFields(TestResponse $response, array $fields, string $rule, array $rulesParams = [])
    {
        $response->assertStatus(422);
        $response->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {
            $fieldName = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
                utf8_decode(trans("validation.{$rule}", ['attribute' => $fieldName] + $rulesParams))
            ]);
        }
    }

}
