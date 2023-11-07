<?php

namespace SkoreLabs\LaravelAuditable\Tests;

use SkoreLabs\LaravelAuditable\Events\AuditableEvent;
use SkoreLabs\LaravelAuditable\Tests\Fixtures\Post;
use SkoreLabs\LaravelAuditable\Tests\Fixtures\User;

class AuditableTest extends TestCase
{
    /**
     * @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\User
     */
    protected $user;

    /**
     * @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\User
     */
    protected $anotherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->anotherUser = User::create([
            'email' => 'another_user@localhost.com',
            'name' => 'Another',
            'password' => '1234',
        ]);

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
            'title' => 'hello user',
            'content' => 'lorem ipsum',
        ]);

        $post = $post->refresh();

        $this->assertEquals($this->user->id, $post->created_by);
        $this->assertNull($post->updated_by);
        $this->assertNull($post->deleted_by);

        // Relationship & alias
        $this->assertEquals($this->user->id, $post->createdBy->id);
        $this->assertEquals($this->user->id, $post->author->id);
    }

    public function test_created_by_assignation_using_replicate_and_relationship()
    {
        /** @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\Post $post */
        $post = Post::create([
            'title' => 'hello user',
            'content' => 'lorem ipsum',
        ]);

        $duplicatedPost = $post->replicate();

        $duplicatedPost->save();

        $post = $post->refresh();

        $this->assertEquals($this->user->id, $post->created_by);
        $this->assertNull($post->updated_by);
        $this->assertNull($post->deleted_by);

        // Relationship & alias
        $this->assertEquals($this->user->id, $post->createdBy->id);
        $this->assertEquals($this->user->id, $post->author->id);
    }

    public function test_updated_by_assignation_and_relationship()
    {
        /** @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\Post $post */
        $post = Post::create([
            'title' => 'hello user',
            'content' => 'lorem ipsum',
        ]);

        $post->update(['title' => 'new title']);

        $post = $post->refresh();

        $this->assertEquals($this->user->id, $post->created_by);
        $this->assertEquals($this->user->id, $post->updated_by);
        $this->assertNull($post->deleted_by);

        $this->assertEquals($this->user->id, $post->updatedBy->id);
    }

    public function test_deleted_by_assignation_and_relationship()
    {
        /** @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\Post $post */
        $post = Post::create([
            'title' => 'hello user',
            'content' => 'lorem ipsum',
        ]);

        $post->delete();

        $post = $post->refresh();

        $this->assertEquals($this->user->id, $post->created_by);
        $this->assertNull($post->updated_by);
        $this->assertEquals($this->user->id, $post->deleted_by);

        $this->assertEquals($this->user->id, $post->deletedBy->id);
    }

    public function test_created_by_uses_forced_user_even_when_authenticated_exists()
    {
        AuditableEvent::setUser($this->anotherUser);

        /** @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\Post $post */
        $post = Post::create([
            'title' => 'hello user',
            'content' => 'lorem ipsum',
        ]);

        $post = $post->refresh();

        $this->assertEquals($this->anotherUser->id, $post->created_by);
        $this->assertNull($post->updated_by);
        $this->assertNull($post->deleted_by);

        // Relationship & alias
        $this->assertEquals($this->anotherUser->id, $post->createdBy->id);
        $this->assertEquals($this->anotherUser->id, $post->author->id);
    }

    public function test_updated_by_uses_forced_user_only_when_action_sent()
    {
        AuditableEvent::setUser($this->anotherUser, 'updating');

        /** @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\Post $post */
        $post = Post::create([
            'title' => 'hello user',
            'content' => 'lorem ipsum',
        ]);

        $post = $post->refresh();

        $this->assertEquals($this->user->id, $post->created_by);
        $this->assertNull($post->updated_by);
        $this->assertNull($post->deleted_by);

        $post->update(['title' => 'hello another']);

        $post = $post->refresh();

        $this->assertEquals($this->anotherUser->id, $post->updated_by);

        // Relationship & alias
        $this->assertEquals($this->user->id, $post->createdBy->id);
        $this->assertEquals($this->user->id, $post->author->id);
    }
}
