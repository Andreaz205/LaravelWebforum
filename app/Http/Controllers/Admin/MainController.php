<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Message;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $data = [
            'branches' => [
                'title' => 'Ветки',
                'count' => Branch::query()->count(),
            ],
            'themes' => [
                'title' => 'Темы',
                'count' => Theme::query()->count(),
            ],
            'messages' => [
                'title' => 'Сообщения',
                'count' => Message::query()->count(),
            ],
            'users' => [
                'title' => 'Пользователи',
                'count' => User::query()->count(),
            ]
        ];

        return inertia('Admin/Main/Index', [
            'stats' => $data
        ]);
    }
}
