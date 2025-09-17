<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Requirements Platform API",
 *     version="1.0.0",
 *     description="A comprehensive API for the Requirements Gathering Platform with AI Interviews, Project Management, and Vendor Marketplace",
 *     @OA\Contact(
 *         email="api@requirementsplatform.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Local Development Server"
 * )
 * 
 * @OA\Server(
 *     url="https://specfirst-api.netsolutionindia.com",
 *     description="Staging Environment"
 * )
 * 
 * @OA\Server(
 *     url="https://api.yourapp.com",
 *     description="Production API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="passport",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter token in format (Bearer <token>)"
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="OTP-based authentication endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Projects",
 *     description="Project management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="AI Interviews",
 *     description="AI-powered requirement gathering interviews"
 * )
 * 
 * @OA\Tag(
 *     name="Vendors",
 *     description="Vendor marketplace and lead management"
 * )
 * 
 * @OA\Tag(
 *     name="Analytics",
 *     description="Platform analytics and insights"
 * )
 * 
 * @OA\Tag(
 *     name="Templates",
 *     description="Project templates library"
 * )
 * 
 * @OA\Tag(
 *     name="Collaboration",
 *     description="Project collaboration and comments"
 * )
 */
abstract class Controller
{
    //
}