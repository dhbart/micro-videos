<?php

namespace Tests\Feature\http\Controllers\Api;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CastMemberControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $cast_member;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cast_member = factory( CastMember::class)->create([
            'type'=> CastMember::TYPE_ACTOR
        ]);
    }

    public function testIndex(){
        $response = $this->get(route('cast_members.index'));

        $response
        ->assertStatus(200)
        ->assertJson([$this->cast_member->toArray()]);
    }

    public function testInvalidationData(){

        $data = [
            'name' => '',
            'type' => ''
        ];

        $this->assertInvalidationInStore($data, 'required');
        $this->assertInvalidationInUpdate($data, 'required');
        
        $data = [
            'type' => 's'
        ];

        $this->assertInvalidationInStore($data, 'in');
        $this->assertInvalidationInUpdate($data, 'in');
            
    }

    public function testStore (){
        $data = [
            [
                'name' => 'test',
                'type'=> CastMember::TYPE_ACTOR
            ],
            [
                'name' => 'test',
                'type'=> CastMember::TYPE_DIRECTOR
            ]
        ];
        
        foreach ($data as $key => $value) {
            $response = $this->assertStore($value, $value + ['deleted_at' => null]);
            $response->assertJsonStructure(
                ['created_at', 'updated_at']
            );
        }        

    }

    public function testUpdate ()
    {
        $data = [                
            'name' => 'test',
            'type'=> CastMember::TYPE_ACTOR
        ];
        $response = $this->assertUpdate($data, $data + [ 'deleted_at' => null]);

        $response->assertJsonStructure(
            ['created_at', 'updated_at']
        );
    }

    public function testDelete()
    {        
        $response = $this->json('DELETE', route('cast_members.destroy',['cast_member' => $this->cast_member->id ]));
        $response->assertStatus(204);
        $this->assertNull(CastMember::find($this->cast_member->id));
        $this->assertNotNull(CastMember::withTrashed()->find($this->cast_member->id));
    }

    protected function routeStore()
    {
        return route('cast_members.store');
    }

    protected function routeUpdate()
    {
        return route('cast_members.update', ['cast_member' => $this->cast_member->id]);
    }

    protected function model()
    {
        return CastMember::class;
    }
}