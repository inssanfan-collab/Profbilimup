<?php

namespace App\Livewire\Admin\Curators;

use App\Enums\CuratorPermission;
use App\Enums\UserRole;
use App\Livewire\Concerns\HasPageHeader;
use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    use HasPageHeader;

    #[Locked]
    public ?User $user = null;

    public string $full_name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /** @var array<int, string> */
    public array $permissions = [];

    public string $courseScope = 'selected';

    /** @var array<int, int> */
    public array $courseIds = [];

    public function mount(?User $user = null): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        if ($user?->exists) {
            abort_unless($user->isCurator(), 404);

            $this->user = $user;
            $this->full_name = $user->name;
            $this->email = $user->email;
            $this->permissions = $user->permissions ?? [];
            $this->courseScope = $user->has_all_courses_access ? 'all' : 'selected';
            $this->courseIds = $user->curatorCourses()->pluck('courses.id')->all();
        } else {
            $this->permissions = [CuratorPermission::VideoMeetings->value];
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user?->id)],
            'password' => [$this->user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'permissions' => ['array'],
            'permissions.*' => [Rule::enum(CuratorPermission::class)],
            'courseScope' => ['required', 'in:all,selected'],
            'courseIds' => ['array'],
            'courseIds.*' => ['integer', 'exists:courses,id'],
        ]);

        $hasAllCoursesAccess = $validated['courseScope'] === 'all';

        DB::transaction(function () use ($validated, $hasAllCoursesAccess) {
            $data = [
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'permissions' => array_values($validated['permissions']),
                'has_all_courses_access' => $hasAllCoursesAccess,
            ];

            if ($validated['password'] ?? null) {
                $data['password'] = $validated['password'];
            }

            if ($this->user) {
                $this->user->update($data);
                $curator = $this->user;
            } else {
                $curator = User::create([
                    ...$data,
                    'role' => UserRole::Curator,
                    'must_set_password' => false,
                    'email_verified_at' => now(),
                ]);
            }

            $curator->curatorCourses()->sync($hasAllCoursesAccess ? [] : $validated['courseIds']);
        });

        return redirect()->route('admin.curators.index');
    }

    public function render(): View
    {
        $title = $this->user ? __('Редактирование куратора') : __('Новый куратор');

        return view('livewire.admin.curators.form', [
            'allPermissions' => CuratorPermission::cases(),
            'courses' => Course::orderBy('title')->get(),
        ])->layout('layouts.app', ['header' => $this->pageHeader($title)]);
    }
}
