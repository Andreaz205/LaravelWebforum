<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThemeStoreRequest;
use App\Http\Requests\ThemeUpdateRequest;
use App\Http\Resources\ThemeResource;
use App\Http\Resources\ThemeWithMessagesResource;
use App\Models\Theme;

class ThemeController extends Controller
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
    public function store(ThemeStoreRequest $request)
    {
        Theme::query()->create($request->validated());

        return redirect()->route('branches.show', $request->validated('branch_id'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Theme $theme)
    {
        $theme = ThemeWithMessagesResource::make($theme)->resolve();

        return inertia('Theme/Show', compact('theme'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Theme $theme): \Inertia\Response|\Inertia\ResponseFactory
    {
        $theme = ThemeResource::make($theme)->resolve();

        return inertia('Theme/Edit', compact('theme'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ThemeUpdateRequest $request, Theme $theme)
    {
        $theme->update($request->validated());

        return redirect()->route('branches.show', $theme->branch_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme)
    {
        $theme->delete();

        return redirect()->route('branches.show', $theme->branch_id);
    }
}
