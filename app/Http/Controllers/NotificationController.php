<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notification\UpdateCollectionRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function updateCollection(UpdateCollectionRequest $request)
    {
        Notification::query()
            ->whereIn('id', $request->validated('ids'))
            ->update(['is_read' => true]);

        return response()->json([
            'count' => auth()->user()->notifications()->count()
        ]);
    }
}
