<?php


namespace Tests\Traits;


use Illuminate\Foundation\Testing\TestResponse;

trait SaveDataTest
{
    protected function assertStore(array $dadosEnviados, array $databaseTest, array $jsonTest = null): TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('POST', $this->routeStore(), $dadosEnviados);
        if ($response->status() !== 201) {
            throw new \Exception("o status da resposta deve ser 201, retornado {$response->status()}:\n {$response->content()}");
        }
        $this->assertDatabase($response, $databaseTest);
        $this->assertJsonResponseContent($response, $databaseTest, $jsonTest);
        return $response;
    }

    protected function assertUpdate(array $dadosEnviados, array $databaseTest, array $jsonTest = null): TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('PUT', $this->routeUpdate(), $dadosEnviados);
        if ($response->status() !== 200) {
            throw new \Exception("o status da resposta deve ser 200, retornado {$response->status()}:\n {$response->content()}");
        }
        $this->assertDatabase($response, $databaseTest);
        $this->assertJsonResponseContent($response, $databaseTest, $jsonTest);
        return $response;
    }

    private function assertDatabase(TestResponse $response, array $databaseTest)
    {
        $model = $this->model();
        $tabela = (new $model)->getTable();
        $this->assertDatabaseHas($tabela, $databaseTest + ['id' => $response->json('id')]);
    }

    private function assertJsonResponseContent(TestResponse $response, array $databaseTest, array $jsonTest = null)
    {
        $testResponse = $jsonTest ?? $databaseTest;
        $response->assertJsonFragment($testResponse + ['id' => $response->json('id')]);
        return $response;
    }
}
