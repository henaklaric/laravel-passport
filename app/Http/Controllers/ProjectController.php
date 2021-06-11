<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        Log::info("Get projects request received from user: " . $request->user()->name . " (ID: " . $request->user()->id . ")");

        $projects = Project::get();
        return response()->json([
            'projects' => $projects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProjectRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProjectRequest $request)
    {
        $input = $request->input();
        $input['user_id'] = Auth::user()->id;

        $project = Project::create($input);

        return response()->json([
            "message" => "Project stored successfully." ,
            "project" => $project,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $project = Project::find($id);

        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        $project = Project::find($id);

        $project->update($request->input());

        return response()->json([
            "message" => "Project updated successfully." ,
            "project" => $project
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (!Auth::user()->is_admin && Auth::user()->id != $project->id)
            return response()->json(['message' => 'Action forbidden'], 400);

        $project->delete();

        return response()->json([
           "message" => "Project deleted successfully."
        ]);
    }
}
