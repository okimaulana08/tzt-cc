<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions required by PostPolicy
        Permission::firstOrCreate(['name' => 'create posts']);
        Permission::firstOrCreate(['name' => 'edit posts']);
        Permission::firstOrCreate(['name' => 'delete posts']);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(['create posts', 'edit posts', 'delete posts']);

        $editor = Role::firstOrCreate(['name' => 'editor']);
        $editor->syncPermissions(['create posts', 'edit posts']);

        Role::firstOrCreate(['name' => 'viewer']);
    }

    // -------------------------------------------------------------------------
    // index
    // -------------------------------------------------------------------------

    public function test_guest_can_view_posts_index(): void
    {
        Post::factory()->count(3)->create();

        $this->get(route('posts.index'))->assertOk();
    }

    public function test_authenticated_user_can_view_posts_index(): void
    {
        $user = User::factory()->create()->assignRole('viewer');
        Post::factory()->count(2)->create();

        $this->actingAs($user)->get(route('posts.index'))->assertOk();
    }

    // -------------------------------------------------------------------------
    // show
    // -------------------------------------------------------------------------

    public function test_anyone_can_view_a_post(): void
    {
        $post = Post::factory()->published()->create();

        $this->get(route('posts.show', $post))->assertOk()->assertSee($post->title);
    }

    // -------------------------------------------------------------------------
    // create
    // -------------------------------------------------------------------------

    public function test_editor_can_view_create_form(): void
    {
        $user = User::factory()->create()->assignRole('editor');

        $this->actingAs($user)->get(route('posts.create'))->assertOk();
    }

    public function test_viewer_cannot_view_create_form(): void
    {
        $user = User::factory()->create()->assignRole('viewer');

        $this->actingAs($user)->get(route('posts.create'))->assertForbidden();
    }

    public function test_guest_is_redirected_from_create_form(): void
    {
        $this->get(route('posts.create'))->assertRedirect(route('login'));
    }

    // -------------------------------------------------------------------------
    // store
    // -------------------------------------------------------------------------

    public function test_editor_can_create_post(): void
    {
        $user = User::factory()->create()->assignRole('editor');

        $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => 'My New Post',
                'content' => 'Some content here.',
                'status' => 'draft',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('posts', ['title' => 'My New Post', 'user_id' => $user->id]);
    }

    public function test_store_sets_published_at_when_status_is_published(): void
    {
        $user = User::factory()->create()->assignRole('editor');

        $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'Published Post',
            'content' => 'Content.',
            'status' => 'published',
        ]);

        $this->assertNotNull(Post::where('title', 'Published Post')->first()?->published_at);
    }

    public function test_viewer_cannot_create_post(): void
    {
        $user = User::factory()->create()->assignRole('viewer');

        $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => 'Nope',
                'content' => 'Nope.',
                'status' => 'draft',
            ])
            ->assertForbidden();
    }

    public function test_store_validates_required_fields(): void
    {
        $user = User::factory()->create()->assignRole('editor');

        $this->actingAs($user)
            ->post(route('posts.store'), [])
            ->assertSessionHasErrors(['title', 'content', 'status']);
    }

    // -------------------------------------------------------------------------
    // edit
    // -------------------------------------------------------------------------

    public function test_editor_can_view_edit_form_for_own_post(): void
    {
        $user = User::factory()->create()->assignRole('editor');
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get(route('posts.edit', $post))->assertOk();
    }

    public function test_viewer_cannot_view_edit_form(): void
    {
        $user = User::factory()->create()->assignRole('viewer');
        $post = Post::factory()->create();

        $this->actingAs($user)->get(route('posts.edit', $post))->assertForbidden();
    }

    // -------------------------------------------------------------------------
    // update
    // -------------------------------------------------------------------------

    public function test_editor_can_update_own_post(): void
    {
        $user = User::factory()->create()->assignRole('editor');
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->patch(route('posts.update', $post), [
                'title' => 'Updated Title',
                'content' => 'Updated content.',
                'status' => 'draft',
            ])
            ->assertRedirect(route('posts.show', $post));

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated Title']);
    }

    public function test_update_sets_published_at_when_first_published(): void
    {
        $user = User::factory()->create()->assignRole('editor');
        $post = Post::factory()->create(['user_id' => $user->id, 'status' => 'draft', 'published_at' => null]);

        $this->actingAs($user)->patch(route('posts.update', $post), [
            'title' => $post->title,
            'content' => $post->content,
            'status' => 'published',
        ]);

        $this->assertNotNull($post->fresh()->published_at);
    }

    public function test_viewer_cannot_update_others_post(): void
    {
        $viewer = User::factory()->create()->assignRole('viewer');
        $post = Post::factory()->create();

        $this->actingAs($viewer)
            ->patch(route('posts.update', $post), [
                'title' => 'Hacked',
                'content' => 'X',
                'status' => 'draft',
            ])
            ->assertForbidden();
    }

    // -------------------------------------------------------------------------
    // destroy
    // -------------------------------------------------------------------------

    public function test_admin_can_delete_any_post(): void
    {
        $admin = User::factory()->create()->assignRole('admin');
        $post = Post::factory()->create();

        $this->actingAs($admin)
            ->delete(route('posts.destroy', $post))
            ->assertRedirect(route('posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_editor_can_delete_own_post(): void
    {
        $user = User::factory()->create()->assignRole('editor');
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('posts.destroy', $post))
            ->assertRedirect(route('posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_editor_cannot_delete_others_post(): void
    {
        $editor = User::factory()->create()->assignRole('editor');
        $post = Post::factory()->create();

        $this->actingAs($editor)
            ->delete(route('posts.destroy', $post))
            ->assertForbidden();
    }

    public function test_viewer_cannot_delete_post(): void
    {
        $viewer = User::factory()->create()->assignRole('viewer');
        $post = Post::factory()->create();

        $this->actingAs($viewer)
            ->delete(route('posts.destroy', $post))
            ->assertForbidden();
    }
}
