<?php

namespace App\Http\Controllers\Swagger\Proxy;

use App\Http\Controllers\Controller;

/**
 * @OA\Post(
 *     path="/api/users/tasks",
 *     summary="Create a new task",
 *     tags={"Task"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *
 *         @OA\JsonContent(
 *             allOf={
 *
 *                 @OA\Schema(
 *
 *                     @OA\Property(
 *                         property="title",
 *                         type="string",
 *                         example="Cook dinner"
 *                     ),
 *                     @OA\Property(
 *                         property="content",
 *                         type="string",
 *                         example="Buy 1 kilogram of chicken and grill it"
 *                     ),
 *                     @OA\Property(
 *                         property="tags",
 *                         type="array",
 *
 *                         @OA\Items(
 *                             type="string",
 *                             description="Tag name"
 *                         ),
 *                         example={"tag1", "tag2", "tag3"},
 *                         nullable=true
 *                     ),
 *                 )
 *             }
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response="504",
 *         description="Gateway Timeout"
 *     ),
 *
 *     @OA\Response(
 *         response="200",
 *         description="Ok",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(
 *                 property="id",
 *                 type="integer",
 *                 example="1"
 *             ),
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 example="Cook dinner"
 *             ),
 *             @OA\Property(
 *                 property="content",
 *                 type="string",
 *                 example="Buy 1 kilogram of chicken and grill it"
 *             ),
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 enum={"CREATED", "PROCESSING", "FINISHED", "CANCELED"},
 *                 example="CREATED"
 *             ),
 *             @OA\Property(
 *                 property="tags",
 *                 type="array",
 *
 *                 @OA\Items(
 *                     type="string",
 *                     description="Tag name"
 *                 ),
 *                 example={"tag1", "tag2", "tag3"},
 *                 nullable=true
 *             ),
 *
 *             @OA\Property(
 *                 property="started_at",
 *                 type="date-time",
 *                 example="2024-09-27 11:23:26",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="finished_at",
 *                 type="date-time",
 *                 example="2024-09-27 11:23:26",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="created_at",
 *                 type="date-time",
 *                 example="2024-09-27 11:23:26",
 *                 nullable=true
 *             ),
 *         )
 *     ),
 * ),
 *
 * @OA\Get(
 *     path="/api/users/tasks",
 *     summary="Get list of tasks",
 *     tags={"Task"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="Parameter number of page",
 *         in="query",
 *         name="page",
 *         required=false,
 *
 *         @OA\Schema(type="integer"),
 *
 *         @OA\Examples(example="int", value="1", summary="Number of page"),
 *     ),
 *
 *     @OA\Response(
 *         response="504",
 *         description="Gateway Timeout"
 *     ),
 *
 *     @OA\Response(
 *         response="200",
 *         description="Ok",
 *
 *         @OA\JsonContent(
 *             type="array",
 *
 *             @OA\Items(
 *
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer",
 *                     example="1"
 *                 ),
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                     example="Cook dinner"
 *                 ),
 *                 @OA\Property(
 *                     property="content",
 *                     type="string",
 *                     example="Buy 1 kilogram of chicken and grill it"
 *                 ),
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                     enum={"CREATED", "PROCESSING", "FINISHED", "CANCELED"},
 *                     example="CREATED"
 *                 ),
 *                 @OA\Property(
 *                     property="tags",
 *                     type="array",
 *
 *                     @OA\Items(
 *                         type="string",
 *                         description="Tag name"
 *                     ),
 *                     example={"tag1", "tag2", "tag3"},
 *                     nullable=true
 *                 ),
 *
 *                 @OA\Property(
 *                     property="started_at",
 *                     type="date-time",
 *                     example="2024-09-27 11:23:26",
 *                     nullable=true
 *                 ),
 *                 @OA\Property(
 *                     property="finished_at",
 *                     type="date-time",
 *                     example="2024-09-27 11:23:26",
 *                     nullable=true
 *                 ),
 *                 @OA\Property(
 *                     property="created_at",
 *                     type="date-time",
 *                     example="2024-09-27 11:23:26",
 *                     nullable=true
 *                 ),
 *             )
 *         )
 *     ),
 * ),
 *
 * @OA\Get(
 *     path="/api/users/tasks/{task}",
 *     summary="Get a task",
 *     tags={"Task"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="Parameter task id",
 *         in="path",
 *         name="task",
 *         required=true,
 *
 *         @OA\Schema(type="integer"),
 *
 *         @OA\Examples(example="int", value="1", summary="Task id"),
 *     ),
 *
 *     @OA\Response(
 *         response="504",
 *         description="Gateway Timeout"
 *     ),
 *
 *     @OA\Response(
 *         response="200",
 *         description="Ok",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(
 *                 property="id",
 *                 type="integer",
 *                 example="1"
 *             ),
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 example="Cook dinner"
 *             ),
 *             @OA\Property(
 *                 property="content",
 *                 type="string",
 *                 example="Buy 1 kilogram of chicken and grill it"
 *             ),
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 enum={"CREATED", "PROCESSING", "FINISHED", "CANCELED"},
 *                 example="CREATED"
 *             ),
 *             @OA\Property(
 *                 property="tags",
 *                 type="array",
 *
 *                 @OA\Items(
 *                     type="string",
 *                     description="Tag name"
 *                 ),
 *                 example={"tag1", "tag2", "tag3"},
 *                 nullable=true
 *             ),
 *
 *             @OA\Property(
 *                 property="started_at",
 *                 type="date-time",
 *                 example="2024-09-27 11:23:26",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="finished_at",
 *                 type="date-time",
 *                 example="2024-09-27 11:23:26",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="created_at",
 *                 type="date-time",
 *                 example="2024-09-27 11:23:26",
 *                 nullable=true
 *             ),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="404",
 *         description="Not found"
 *     )
 * ),
 *
 * @OA\Patch(
 *     path="/api/users/tasks/{task}",
 *     summary="Update a task",
 *     tags={"Task"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="Parameter task id",
 *         in="path",
 *         name="task",
 *         required=true,
 *
 *         @OA\Schema(type="integer"),
 *
 *         @OA\Examples(example="int", value="1", summary="Task id"),
 *     ),
 *
 *     @OA\RequestBody(
 *
 *         @OA\JsonContent(
 *             allOf={
 *
 *                 @OA\Schema(
 *
 *                     @OA\Property(
 *                         property="title",
 *                         type="string",
 *                         example="Cook dinner"
 *                     ),
 *                     @OA\Property(
 *                         property="content",
 *                         type="string",
 *                         example="Buy 1 kilogram of chicken and grill it"
 *                     ),
 *                     @OA\Property(
 *                         property="status",
 *                         type="string",
 *                         enum={"CREATED", "PROCESSING", "FINISHED", "CANCELED"},
 *                         example="CREATED"
 *                     ),
 *                     @OA\Property(
 *                         property="tags",
 *                         type="array",
 *
 *                         @OA\Items(
 *                             type="string",
 *                             description="Tag name"
 *                         ),
 *                         example={"tag1", "tag2", "tag3"},
 *                         nullable=true
 *                     ),
 *
 *                     @OA\Property(
 *                         property="started_at",
 *                         type="date-time",
 *                         example="2024-09-27 11:23:26",
 *                         nullable=true
 *                     ),
 *                     @OA\Property(
 *                         property="finished_at",
 *                         type="date-time",
 *                         example="2024-09-27 11:23:26",
 *                         nullable=true
 *                     ),
 *                     @OA\Property(
 *                         property="created_at",
 *                         type="date-time",
 *                         example="2024-09-27 11:23:26",
 *                         nullable=true
 *                     ),
 *                 )
 *             }
 *         ),
 *     ),
 *
 *     @OA\Response(
 *         response="504",
 *         description="Gateway Timeout"
 *     ),
 *
 *     @OA\Response(
 *         response="200",
 *         description="Ok",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(
 *                 property="id",
 *                 type="integer",
 *                 example="1"
 *             ),
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 example="Cook dinner"
 *             ),
 *             @OA\Property(
 *                 property="content",
 *                 type="string",
 *                 example="Buy 1 kilogram of chicken and grill it"
 *             ),
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 enum={"CREATED", "PROCESSING", "FINISHED", "CANCELED"},
 *                 example="CREATED"
 *             ),
 *             @OA\Property(
 *                 property="tags",
 *                 type="array",
 *
 *                 @OA\Items(
 *                     type="string",
 *                     description="Tag name"
 *                 ),
 *                 example={"tag1", "tag2", "tag3"},
 *                 nullable=true
 *             ),
 *
 *             @OA\Property(
 *                 property="started_at",
 *                 type="date-time",
 *                 example="2024-09-27 11:23:26",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="finished_at",
 *                 type="date-time",
 *                 example="2024-09-27 11:23:26",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="created_at",
 *                 type="date-time",
 *                 example="2024-09-27 11:23:26",
 *                 nullable=true
 *             ),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="404",
 *         description="Not found"
 *     )
 * ),
 *
 * @OA\Delete(
 *     path="/api/users/tasks/{task}",
 *     summary="Destroy a task",
 *     tags={"Task"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="Parameter task id",
 *         in="path",
 *         name="task",
 *         required=true,
 *
 *         @OA\Schema(type="integer"),
 *
 *         @OA\Examples(example="int", value="1", summary="Task id"),
 *     ),
 *
 *     @OA\Response(
 *         response="504",
 *         description="Gateway Timeout"
 *     ),
 *
 *     @OA\Response(
 *         response="200",
 *         description="Ok",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(
 *                 type="bool",
 *                 property="deleted",
 *                 example="true"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="404",
 *         description="Not found"
 *     )
 * ),
 *
 * @OA\Patch(
 *     path="/api/users/tasks/{task}/status/next",
 *     summary="Update status of task to next. ",
 *     tags={"Task"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="Parameter task id",
 *         in="path",
 *         name="task",
 *         required=true,
 *
 *         @OA\Schema(type="integer"),
 *
 *         @OA\Examples(example="int", value="1", summary="Task id"),
 *     ),
 *
 *     @OA\Response(
 *         response="504",
 *         description="Gateway Timeout"
 *     ),
 *
 *     @OA\Response(
 *         response="200",
 *         description="Ok",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(
 *                 property="updated",
 *                 type="bool",
 *                 example="true"
 *             ),
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 enum={"CREATED", "PROCESSING", "FINISHED", "CANCELED"},
 *                 example="PROCESSING"
 *             ),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="404",
 *         description="Not found"
 *     )
 * ),
 *
 * @OA\Patch(
 *     path="/api/users/tasks/{task}/status/back",
 *     summary="Update status of task to back. ",
 *     tags={"Task"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Parameter(
 *         description="Parameter task id",
 *         in="path",
 *         name="task",
 *         required=true,
 *
 *         @OA\Schema(type="integer"),
 *
 *         @OA\Examples(example="int", value="1", summary="Task id"),
 *     ),
 *
 *     @OA\Response(
 *         response="504",
 *         description="Gateway Timeout"
 *     ),
 *
 *     @OA\Response(
 *         response="200",
 *         description="Ok",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(
 *                 property="updated",
 *                 type="bool",
 *                 example="true"
 *             ),
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 enum={"CREATED", "PROCESSING", "FINISHED", "CANCELED"},
 *                 example="PROCESSING"
 *             ),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="404",
 *         description="Not found"
 *     )
 * ),
 */
class TaskController extends Controller
{
    //
}
