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
            'email'    => 'another_user@localhost.com',
            'name'     => 'Another',
            'password' => '1234',
        ]);

        $this->user = User::create([
            'email'    => 'admin@localhost.com',
            'name'     => 'Admin',
            'password' => '1234',
        ]);

        $this->actingAs($this->user);
    }

    public function test_created_by_assignation_and_relationship()
    {
        /** @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\Post $post */
        $post = Post::create([
            'title'   => 'hello user',
            'content' => 'lorem ipsum',
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
            'title'   => 'hello user',
            'content' => 'lorem ipsum',
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
            'title'   => 'hello user',
            'content' => 'lorem ipsum',
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
            'title'   => 'hello user',
            'content' => 'lorem ipsum',
        ]);

        $post->delete();

        $this->assertTrue($post->created_by === $this->user->id);
        $this->assertNull($post->updated_by);
        $this->assertTrue($post->deleted_by === $this->user->id);

        $this->assertTrue($post->deletedBy->id === $this->user->id);
    }

    public function test_created_by_uses_forced_user_even_when_authenticated_exists()
    {
        AuditableEvent::setUser($this->anotherUser);

        /** @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\Post $post */
        $post = Post::create([
            'title'   => 'hello user',
            'content' => 'lorem ipsum',
        ]);

        $this->assertTrue($post->created_by === $this->anotherUser->id);
        $this->assertNull($post->updated_by);
        $this->assertNull($post->deleted_by);

        // Relationship & alias
        $this->assertTrue($post->createdBy->id === $this->anotherUser->id);
        $this->assertTrue($post->author->id === $this->anotherUser->id);
    }

    public function test_updated_by_uses_forced_user_only_when_action_sent()
    {
        AuditableEvent::setUser($this->anotherUser, 'updating');

        /** @var \SkoreLabs\LaravelAuditable\Tests\Fixtures\Post $post */
        $post = Post::create([
            'title'   => 'hello user',
            'content' => 'lorem ipsum',
        ]);

        $this->assertTrue($post->created_by === $this->user->id);
        $this->assertNull($post->updated_by);
        $this->assertNull($post->deleted_by);

        $post->update(['title' => 'hello another']);

        $this->assertTrue($post->updated_by === $this->anotherUser->id);

        // Relationship & alias
        $this->assertTrue($post->createdBy->id === $this->user->id);
        $this->assertTrue($post->author->id === $this->user->id);
    }
}
