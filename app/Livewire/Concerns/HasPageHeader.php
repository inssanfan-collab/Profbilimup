<?php

namespace App\Livewire\Concerns;

use Illuminate\Support\HtmlString;

trait HasPageHeader
{
    protected function pageHeader(string $title): HtmlString
    {
        return new HtmlString(
            '<h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">'.e($title).'</h2>'
        );
    }
}
