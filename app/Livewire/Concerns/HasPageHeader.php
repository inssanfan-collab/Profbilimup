<?php

namespace App\Livewire\Concerns;

use Illuminate\Support\HtmlString;

trait HasPageHeader
{
    protected function pageHeader(string $title): HtmlString
    {
        return new HtmlString(
            '<h2 class="font-semibold text-xl text-gray-800 leading-tight">'.e($title).'</h2>'
        );
    }
}
