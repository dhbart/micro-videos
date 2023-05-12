<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;
    private $category;

    protected function SetUp():void
    {
        parent::setUp();
        $this->category = new Category();
    }
    public function testFillable()
    {
        $fillable = ['name', 'description', 'is_active'];
 
        $this->assertEquals($fillable, $this->category->getFillable());
    }

    /*public function testIfUseTraits()
    {
        $traits = [SoftDeletes::class, Uuid::class];
        $categoryTraits = array_keys(class_uses(Category::class));
        //print_r(class_uses(Category::class));
        $this->assertEquals($traits, $this->categoryTraits);
    }*/

    public function testCasts()
    {
        $casts = [
            'id' => 'string'
        ];
 
        $this->assertEquals($casts, $this->category->getCasts());
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->category->incrementing);
    }

    public function testDates()
    {
        $dates = ['deleted_at','created_at','updated_at'];
        foreach ($dates as $date) {
            $this->assertContains($date, $this->category->getDates());
        }
        $this->assertCount(count($dates), $this->category->getDates());
    }
}
