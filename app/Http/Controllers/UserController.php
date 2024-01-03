<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class UserController extends Controller
{
    public function personal(): \Inertia\Response|\Inertia\ResponseFactory
    {
        $user = UserResource::make(auth()->user())->resolve();

        return inertia('User/Personal', compact('user'));
    }

    public function update(UpdateRequest $request)
    {
        $manager = new ImageManager(new Driver());

        $file = $request->validated('avatar');

        $imageNameWithExtension = hexdec(uniqid()) . "." . $file->getClientOriginalExtension();

        $imageBasePath = base_path('storage/app/public/avatars/' . $imageNameWithExtension);

        $image = $manager->read($request->validated('avatar'))->resize(100, 100);

        $image->save($imageBasePath);

        if (auth()->user()->avatar) {
            Storage::disk('public')->delete(auth()->user()->avatar);
        }

        auth()->user()->update(['avatar' => 'avatars/' . $imageNameWithExtension]);

        return UserResource::make(auth()->user())->resolve();
    }
}
