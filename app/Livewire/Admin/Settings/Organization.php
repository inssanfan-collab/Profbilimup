<?php

namespace App\Livewire\Admin\Settings;

use App\Livewire\Concerns\HasPageHeader;
use App\Models\OrganizationSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Organization extends Component
{
    use HasPageHeader, WithFileUploads;

    public string $name_ru = '';

    public string $name_kk = '';

    public string $director_full_name = '';

    public $logo = null;

    public function mount(): void
    {
        $settings = OrganizationSettings::current();

        $this->name_ru = (string) $settings->name_ru;
        $this->name_kk = (string) $settings->name_kk;
        $this->director_full_name = (string) $settings->director_full_name;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name_ru' => ['required', 'string', 'max:255'],
            'name_kk' => ['nullable', 'string', 'max:255'],
            'director_full_name' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $settings = OrganizationSettings::current();

        if ($this->logo) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }

            $validated['logo_path'] = $this->logo->store('organization', 'public');
        }

        unset($validated['logo']);

        $settings->update([...$validated, 'updated_by' => auth()->id()]);

        $this->logo = null;
        $this->dispatch('settings-saved');
    }

    public function render(): View
    {
        return view('livewire.admin.settings.organization', ['settings' => OrganizationSettings::current()])
            ->layout('layouts.app', ['header' => $this->pageHeader(__('Настройки организации'))]);
    }
}
