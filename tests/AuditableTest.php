<?php

namespace SkoreLabs\LaravelAuditable\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use SkoreLabs\LaravelAuditable\Tests\Fixtures\Post;
use SkoreLabs\LaravelAuditable\Tests\Fixtures\User;

class AuditableTest extends TestCase
{
    use WithFaker;

    /**
     * @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\User
     */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'email' => 'admin@localhost.com',
            'name' => 'Admin',
            'password' => '1234',
        ]);

        $this->actingAs($this->user);
    }

    public function test_created_by_assignation_and_relationship()
    {
        /** @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\Post $post */
        $post = Post::create([
            'title'   => $this->faker->words(3, true),
            'content' => $this->faker->paragraph(),
        ]);

        $this->assertTrue($post->created_by === $this->user->id);
        $this->assertNull($post->updated_by);
        $this->assertNull($post->deleted_by);
        
        // Relationship & alias
        $this->assertTrue($post->createdBy->id === $this->user->id);
        $this->assertTrue($post->author->id === $this->user->id);
    }
    
    public function test_created_by_assignation_using_replicate_and_relationship()
    {
        /** @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\Post $post */
        $post = Post::create([
            'title'   => $this->faker->words(3, true),
            'content' => $this->faker->paragraph(),
        ]);

        $duplicatedPost = $post->replicate();

        $duplicatedPost->save();

        $this->assertTrue($post->created_by === $this->user->id);
        $this->assertNull($post->updated_by);
        $this->assertNull($post->deleted_by);
        
        // Relationship & alias
        $this->assertTrue($post->createdBy->id === $this->user->id);
        $this->assertTrue($post->author->id === $this->user->id);
    }

    public function test_updated_by_assignation_and_relationship()
    {
        /** @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\Post $post */
        $post = Post::create([
            'title'   => $this->faker->words(3, true),
            'content' => $this->faker->paragraph(),
        ]);

        $post->update(['title' => 'new title']);

        $this->assertTrue($post->created_by === $this->user->id);
        $this->assertTrue($post->updated_by === $this->user->id);
        $this->assertNull($post->deleted_by);
        
        $this->assertTrue($post->updatedBy->id === $this->user->id);
    }

    public function test_deleted_by_assignation_and_relationship()
    {
        /** @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\Post $post */
        $post = Post::create([
            'title'   => $this->faker->words(3, true),
            'content' => $this->faker->paragraph(),
        ]);

        $post->delete();

        $this->assertTrue($post->created_by === $this->user->id);
        $this->assertNull($post->updated_by);
        $this->assertTrue($post->deleted_by === $this->user->id);
        
        $this->assertTrue($post->deletedBy->id === $this->user->id);
    }
}
