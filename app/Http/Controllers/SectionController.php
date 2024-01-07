<?php

namespace App\Http\Controllers;

use App\Http\Requests\Section\StoreRequest;
use App\Http\Requests\Section\UpdateRequest;
use App\Http\Resources\Branch\BranchResource;
use App\Http\Resources\Section\SectionResource;
use App\Http\Resources\Section\SectionWithBranchesResource;
use App\Models\Branch;
use App\Models\Section;
use Illuminate\Auth\Access\AuthorizationException;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = SectionWithBranchesResource::collection(
            Section::query()->with('branches')->get()
        )->resolve();

        return inertia('Section/Index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Section::class);

        return inertia('Section/Create');
    }

    /**
     * Store a newly created resource in storage.
     * @throws AuthorizationException
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', Section::class);

        $data = $request->validated();

        Section::query()->firstOrCreate($data);

        return redirect()->route('sections.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        $section = SectionResource::make($section)->resolve();

        return inertia('Section/Edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Section $section)
    {
        $data = $request->validated();

        $section->update($data);

        return redirect()->route('sections.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->back();
    }

    public function branchIndex(Section $section)
    {
        return BranchResource::collection($section->branches)->resolve();
    }

    public function branchIndexExcept(Section $section, Branch $branch)
    {
        return BranchResource::collection(
            $section->branches()->whereNot('id', $branch->id)->get()
        )->resolve();
    }
}
