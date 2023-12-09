<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;
    private $genre;

    protected function SetUp():void
    {
        parent::setUp();
        $this->genre = new Genre();
    }
    public function testFillable()
    {
        $fillable = ['name', 'is_active'];
 
        $this->assertEquals($fillable, $this->genre->getFillable());
    }


    public function testCasts()
    {
        $casts = [
            'id' => 'string',
            'is_active' => 'boolean'
        ];
 
        $this->assertEquals($casts, $this->genre->getCasts());
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->genre->incrementing);
    }

    public function testDates()
    {
        $dates = ['deleted_at','created_at','updated_at'];
        foreach ($dates as $date) {
            $this->assertContains($date, $this->genre->getDates());
        }
        $this->assertCount(count($dates), $this->genre->getDates());
    }
}
