<?php

namespace Tests\Feature\http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $genre;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = factory( Genre::class)->create();
    }

    public function testIndex()
    {
        
        $response = $this->get(route('genres.index'));

        $response
        ->assertStatus(200)
        ->assertJson([$this->genre->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('genres.show', ['genre' => $this->genre->id]));

        $response
        ->assertStatus(200)
        ->assertJson($this->genre->toArray());
    }

    public function testInvalidationData(){
        $data = [
            'name' => ''
        ];
                
        $this->assertInvalidationInStore($data, 'required');
        $this->assertInvalidationInUpdate($data, 'required');

        $data = [
            'name' => str_repeat('a', 256),
        ];

        $this->assertInvalidationInStore($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdate($data, 'max.string', ['max' => 255]);

        $data = [
            'is_active' => 'a'
        ];
        
        $this->assertInvalidationInStore($data, 'boolean');
        $this->assertInvalidationInUpdate($data, 'boolean');
                   
    }    

    public function testStore ()
    {
        $data = [
            'name' => 'test'
        ];

        $response = $this->assertStore($data, $data + ['name' => 'test', 'is_active' => true, 'deleted_at' => null]);

        $response->assertJsonStructure(
            ['created_at', 'updated_at']
        );

        $data = [
            'name' => 'test',
            'is_active' => false
        ];

        $this->assertStore($data, $data + ['name' => 'test', 'is_active' => false, 'deleted_at' => null]);

    }

    public function testUpdate ()
    {
        $this->genre = factory(Genre::class)->create([
            'is_active' => false
        ]);
        $data = [                
            'name' => 'test',
            'is_active'   => true
        ];
        $response = $this->assertUpdate($data, $data + [ 'deleted_at' => null]);

        $response->assertJsonStructure(
            ['created_at', 'updated_at']
        );

        $data = [                
            'name' => 'test'
        ];
        $this->assertUpdate($data, array_merge($data, ['name' => 'test'] ));



    }

    public function testDelete(){
        
        $response = $this->json('DELETE', route('genres.destroy',['genre' => $this->genre->id ]));
        $response->assertStatus(204);
        $this->assertNull(Genre::find($this->genre->id));
        $this->assertNotNull(Genre::withTrashed()->find($this->genre->id));
    }

    protected function routeStore()
    {
        return route('genres.store');
    }

    protected function routeUpdate()
    {
        return route('genres.update', ['genre' => $this->genre->id]);
    }

    protected function model()
    {
        return Genre::class;
    }
}
