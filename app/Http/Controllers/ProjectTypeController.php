<?php

namespace App\Http\Controllers;

use App\Models\ProjectType;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Project Types",
 *     description="Project type management endpoints"
 * )
 */
class ProjectTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/project-types",
     *     tags={"Project Types"},
     *     summary="Get all project types",
     *     description="Retrieve all available project types for the platform",
     *     @OA\Response(
     *         response=200,
     *         description="Project types retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Web App"),
     *                     @OA\Property(property="slug", type="string", example="web_app"),
     *                     @OA\Property(property="description", type="string", example="Dashboards, SaaS, Portals"),
     *                     @OA\Property(property="icon", type="string", example="🌐"),
     *                     @OA\Property(property="color", type="string", example="#3b82f6"),
     *                     @OA\Property(property="sort_order", type="integer", example=1),
     *                     @OA\Property(property="is_active", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $projectTypes = ProjectType::active()->ordered()->get([
            'id', 'name', 'slug', 'description', 'icon', 'color', 'sort_order', 'is_active'
        ]);

        return response()->json([
            'success' => true,
            'data' => $projectTypes,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/project-types/{slug}",
     *     tags={"Project Types"},
     *     summary="Get project type by slug",
     *     description="Retrieve a specific project type by its slug",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="web_app"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project type retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Web App"),
     *                 @OA\Property(property="slug", type="string", example="web_app"),
     *                 @OA\Property(property="description", type="string", example="Dashboards, SaaS, Portals"),
     *                 @OA\Property(property="icon", type="string", example="🌐"),
     *                 @OA\Property(property="color", type="string", example="#3b82f6"),
     *                 @OA\Property(property="sort_order", type="integer", example=1),
     *                 @OA\Property(property="is_active", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Project type not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Project type not found")
     *         )
     *     )
     * )
     */
    public function show(ProjectType $projectType)
    {
        return response()->json([
            'success' => true,
            'data' => $projectType->only([
                'id', 'name', 'slug', 'description', 'icon', 'color', 'sort_order', 'is_active'
            ]),
        ]);
    }
}