<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Listener = 'listener';
    case Curator = 'curator';
}
