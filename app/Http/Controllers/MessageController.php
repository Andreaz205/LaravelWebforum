<?php

namespace App\Http\Controllers;

use App\Http\Requests\Complaint\StoreRequest;
use App\Http\Requests\MessageStoreRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Http\Resources\MessageResource;
use App\Models\Image;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MessageStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $answeredForUsersIds = Str::of($data['content'])->matchAll('/@[\d]+/')->unique()->transform(
            fn ($id) => Str::of($id)->replaceMatches('/@/', '')->value()
        )->filter(
            fn ($id) => User::query()->where('id', $id)->exists()
        );

        $imageIds = Str::of($data['content'])->matchAll('/img_id=[\d]+/')->unique()->transform(
            fn ($id) => Str::of($id)->replaceMatches('/img_id=/', '')->value()
        );

        $message = Message::query()->create($data);

        Image::query()->whereIn('id', $imageIds)->update([
            'message_id' => $message->id
        ]);

        Image::query()
            ->where('user_id', auth()->id())
            ->whereNull('message_id')
            ->get()
            ->pluck('path')
            ->each(function ($path) {
                Storage::disk('public')->delete($path);
            });

        Image::query()
            ->where('user_id', auth()->id())
            ->whereNull('message_id')
            ->delete();

        $message->answeredUsers()->attach($answeredForUsersIds);

        $message->loadCount('likedUsers');

        return MessageResource::make($message)->resolve();
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MessageUpdateRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }

    public function toggleLike(Message $message)
    {
        $message->likedUsers()->toggle(auth()->id());
    }

    public function storeComplaint(Message $message, StoreRequest $request)
    {
        $message->complaintedUsers()->attach(auth()->id(), $request->validated());

        return MessageResource::make($message)->resolve();
    }
}
