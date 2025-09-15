<?php

namespace App\Filament\Widgets;

use App\Models\Contact;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\Widget;

class BlogStats extends Widget
{
    protected string $view = 'filament.widgets.blog-stats';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'usersCount' => User::query()->count(),
            'postsCount' => Post::query()->count(),
            'contactsCount' => Contact::query()->count(),
        ];
    }
}
