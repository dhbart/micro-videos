<?php
declare(strict_types=1);
namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestValidations
{

    protected function assertInvalidationInStore(array $dados, string $rule, array $ruleParams = [])
    {
        $response = $this->json('POST', $this->routeStore(), $dados);
        $fields = array_keys($dados);
        $this->assertInvalidationFields($response, $fields, $rule, $ruleParams);
    }

    protected function assertInvalidationInUpdate(array $dados, string $rule, array $ruleParams = [])
    {
        $response = $this->json('PUT', $this->routeUpdate(), $dados);
        $fields = array_keys($dados);
        $this->assertInvalidationFields($response, $fields, $rule, $ruleParams);
    }

    protected function  assertInvalidationFields(TestResponse $response, array $fields, string $rule, array $ruleParams = [])
    {
        $response->assertStatus(422)
                ->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {
            $fieldname = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
                \Lang::get("validation.{$rule}", ['attribute' =>  $fieldname] + $ruleParams)
            ]);
        }
                
    }
}
