<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Livewire\Admin\Listeners\Form;
use App\Models\ListenerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class ListenerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_listener_cannot_access_admin_routes(): void
    {
        $listener = User::factory()->create(['role' => UserRole::Listener]);

        $this->actingAs($listener)->get('/admin/listeners')->assertForbidden();
    }

    public function test_admin_can_view_listeners_index(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/listeners')->assertOk();
    }

    public function test_admin_can_create_a_listener_with_profile_and_invite_link(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(Form::class)
            ->set('full_name', 'Иванова Айгуль Сериковна')
            ->set('email', 'aigul@example.test')
            ->set('workplace', 'Школа №5')
            ->call('save')
            ->assertRedirect(route('admin.listeners.index'));

        $listener = User::where('email', 'aigul@example.test')->firstOrFail();

        $this->assertTrue($listener->role === UserRole::Listener);
        $this->assertTrue($listener->must_set_password);

        // Regression guard: the listener_profiles.user_id must actually be persisted
        // (previously silently dropped because it was missing from the model's Fillable list).
        $profile = ListenerProfile::where('user_id', $listener->id)->first();
        $this->assertNotNull($profile);
        $this->assertSame('Школа №5', $profile->workplace);
    }

    public function test_listener_can_accept_invite_and_set_password(): void
    {
        $listener = User::factory()->create([
            'role' => UserRole::Listener,
            'must_set_password' => true,
        ]);

        Livewire::test('pages.auth.accept-invite', ['user' => $listener])
            ->set('password', 'a-strong-password')
            ->set('password_confirmation', 'a-strong-password')
            ->call('acceptInvite')
            ->assertRedirect(route('dashboard', absolute: false));

        $listener->refresh();

        $this->assertFalse($listener->must_set_password);
        $this->assertAuthenticatedAs($listener);
    }

    public function test_invite_link_no_longer_works_once_password_is_set(): void
    {
        $listener = User::factory()->create([
            'role' => UserRole::Listener,
            'must_set_password' => false,
        ]);

        $url = URL::temporarySignedRoute('invite.accept', now()->addDays(14), ['user' => $listener->id]);

        $this->get($url)->assertNotFound();
    }
}
