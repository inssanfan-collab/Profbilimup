<?php

namespace App\Livewire\Admin\Listeners;

use App\Enums\CuratorPermission;
use App\Enums\UserLocale;
use App\Enums\UserRole;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\ListenerProfile;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    use HasPageHeader;

    #[Locked]
    public ?User $user = null;

    public string $email = '';

    public string $locale = 'ru';

    public string $full_name = '';

    public string $phone = '';

    public string $workplace = '';

    public string $position = '';

    public string $subject = '';

    public string $qualification_category = '';

    public ?int $experience_years = null;

    public function mount(?User $user = null): void
    {
        abort_unless(auth()->user()->hasPermission(CuratorPermission::Listeners), 403);

        if ($user?->exists) {
            $courseIds = auth()->user()->scopedCourseIds();
            if ($courseIds !== null) {
                abort_unless($user->courseAssignments()->whereIn('course_id', $courseIds)->exists(), 403);
            }

            $this->user = $user;
            $this->email = $user->email;
            $this->locale = $user->locale->value;

            $profile = $user->listenerProfile;
            $this->full_name = $profile?->full_name ?? '';
            $this->phone = $profile?->phone ?? '';
            $this->workplace = $profile?->workplace ?? '';
            $this->position = $profile?->position ?? '';
            $this->subject = $profile?->subject ?? '';
            $this->qualification_category = $profile?->qualification_category ?? '';
            $this->experience_years = $profile?->experience_years;
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user?->id)],
            'locale' => ['required', Rule::enum(UserLocale::class)],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'workplace' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'qualification_category' => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:80'],
        ]);

        $profileData = collect($validated)->only([
            'full_name', 'phone', 'workplace', 'position', 'subject', 'qualification_category', 'experience_years',
        ])->all();

        if ($this->user) {
            $this->user->update([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'locale' => $validated['locale'],
            ]);

            $this->user->listenerProfile()->updateOrCreate([], $profileData);

            return redirect()->route('admin.listeners.index');
        }

        $user = DB::transaction(function () use ($validated, $profileData) {
            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'locale' => $validated['locale'],
                'role' => UserRole::Listener,
                'password' => Str::random(40),
                'must_set_password' => true,
            ]);

            ListenerProfile::create(['user_id' => $user->id, ...$profileData]);

            return $user;
        });

        $inviteLink = URL::temporarySignedRoute('invite.accept', now()->addDays(14), ['user' => $user->id]);

        return redirect()->route('admin.listeners.index')->with('inviteLink', $inviteLink);
    }

    public function render(): View
    {
        $title = $this->user ? __('Редактирование слушателя') : __('Новый слушатель');

        return view('livewire.admin.listeners.form')
            ->layout('layouts.app', ['header' => $this->pageHeader($title)]);
    }
}
