<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CastMemberTest extends TestCase
{
    use DatabaseMigrations;
    private $cast_member;

    protected function SetUp():void
    {
        parent::setUp();
        $this->cast_member = new CastMember();
    }
    public function testFillable()
    {
        $fillable = ['name', 'type'];
 
        $this->assertEquals($fillable, $this->cast_member->getFillable());
    }

    
    public function testIncrementing()
    {
        $this->assertFalse($this->cast_member->incrementing);
    }

    public function testCasts()
    {
        $casts = [
            'id' => 'string',
            'type' => 'integer'
        ];
 
        $this->assertEquals($casts, $this->cast_member->getCasts());
    }

    public function testDates()
    {
        $dates = ['deleted_at','created_at','updated_at'];
        foreach ($dates as $date) {
            $this->assertContains($date, $this->cast_member->getDates());
        }
        $this->assertCount(count($dates), $this->cast_member->getDates());
    }
}
