<?php

namespace Tests\Feature;

use App\Enums\UserLocale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_kazakh_locale_translates_known_ui_strings(): void
    {
        app()->setLocale('kk');

        $this->assertSame('Тыңдаушылар', __('Слушатели'));
        $this->assertSame('Курс атауы', __('Название курса'));
    }

    public function test_switching_locale_persists_it_on_the_authenticated_user(): void
    {
        $user = User::factory()->create(['locale' => UserLocale::Ru]);

        $this->actingAs($user)->get(route('locale.update', 'kk'))->assertRedirect();

        $this->assertSame(UserLocale::Kk, $user->fresh()->locale);
    }

    public function test_authenticated_users_locale_is_applied_on_every_request(): void
    {
        $user = User::factory()->create(['locale' => UserLocale::Kk]);

        $this->actingAs($user)->get(route('listener.dashboard'));

        $this->assertSame('kk', app()->getLocale());
    }

    public function test_validation_errors_are_translated_instead_of_raw_keys(): void
    {
        app()->setLocale('ru');

        $this->assertSame(
            'Поле «ФИО» обязательно для заполнения.',
            trans('validation.required', ['attribute' => trans('validation.attributes.full_name')])
        );
    }
}
