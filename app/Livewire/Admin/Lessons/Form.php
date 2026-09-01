<?php

namespace App\Livewire\Admin\Lessons;

use App\Livewire\Concerns\HasPageHeader;
use App\Models\Lesson;
use App\Models\LessonFile;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use HasPageHeader, WithFileUploads;

    #[Locked]
    public Lesson $lesson;

    public string $title = '';

    public string $content_html = '';

    public string $video_url = '';

    /** @var array<\Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $newFiles = [];

    public function mount(Lesson $lesson): void
    {
        $this->lesson = $lesson;
        $this->title = $lesson->title;
        $this->content_html = (string) $lesson->content_html;
        $this->video_url = (string) $lesson->video_url;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'content_html' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url', 'max:500'],
        ]);

        $this->lesson->update($validated);

        $this->dispatch('lesson-saved');
    }

    public function uploadFiles(): void
    {
        $this->validate([
            'newFiles.*' => ['file', 'max:20480', 'mimes:pdf,doc,docx,ppt,pptx'],
        ]);

        foreach ($this->newFiles as $file) {
            $path = $file->store('lesson-files', 'public');

            $this->lesson->files()->create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);
        }

        $this->newFiles = [];
    }

    public function deleteFile(LessonFile $file): void
    {
        abort_unless($file->lesson_id === $this->lesson->id, 403);

        Storage::disk('public')->delete($file->path);
        $file->delete();
    }

    public function videoEmbedUrl(): ?string
    {
        return (new Lesson(['video_url' => $this->video_url]))->videoEmbedUrl();
    }

    public function render(): View
    {
        return view('livewire.admin.lessons.form', [
            'files' => $this->lesson->files()->latest()->get(),
        ])->layout('layouts.app', ['header' => $this->pageHeader(__('Содержимое урока'))]);
    }
}
