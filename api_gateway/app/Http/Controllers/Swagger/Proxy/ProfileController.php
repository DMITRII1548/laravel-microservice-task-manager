<?php

namespace App\Http\Controllers\Swagger\Proxy;

use App\Http\Controllers\Controller;

/**
 * @OA\Post(
 *     path="/api/users/profile",
 *     summary="Create a new profile",
 *     tags={"Profile"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *
 *             @OA\Schema(
 *
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     example="John",
 *                 ),
 *                 @OA\Property(
 *                     property="surname",
 *                     type="string",
 *                     example="Michael"
 *                 ),
 *                 @OA\Property(
 *                     property="patronymic",
 *                     type="string",
 *                     example="Doe"
 *                 ),
 *                 @OA\Property(
 *                     property="age",
 *                     type="integer",
 *                     example="30"
 *                 ),
 *                 @OA\Property(
 *                     property="image",
 *                     type="string",
 *                     format="binary",
 *                     nullable=true,
 *                     description="Upload an image file (e.g., JPG, PNG)"
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="201",
 *         description="Created",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer",
 *                     example="1"
 *                 ),
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     example="John"
 *                 ),
 *                 @OA\Property(
 *                     property="surname",
 *                     type="string",
 *                     example="Michael"
 *                 ),
 *                 @OA\Property(
 *                     property="patronymic",
 *                     type="string",
 *                     example="Doe"
 *                 ),
 *                 @OA\Property(
 *                     property="image",
 *                     type="string",
 *                     nullable=true,
 *                     example="http://127.0.0.1:8000/image/image.png"
 *                  ),
 *                 @OA\Property(
 *                     property="age",
 *                     type="integer",
 *                     example="30"
 *                 ),
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     @OA\Property(
 *                         property="id",
 *                         type="integer",
 *                         example="1"
 *                     ),
 *                     @OA\Property(
 *                         property="email",
 *                         type="string",
 *                         example="test@gmail.com"
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="404",
 *         description="Not found"
 *     ),
 *
  *     @OA\Response(
 *         response="504",
 *         description="Gateway Timeout"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Server error"
 *     )
 * ),
 *
 * @OA\Patch(
 *     path="/api/users/profile",
 *     summary="Edit a profile",
 *     tags={"Profile"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\RequestBody(
 *
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *
 *             @OA\Schema(
 *
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     example="John",
 *                 ),
 *                 @OA\Property(
 *                     property="surname",
 *                     type="string",
 *                     example="Michael"
 *                 ),
 *                 @OA\Property(
 *                     property="patronymic",
 *                     type="string",
 *                     example="Doe"
 *                 ),
 *                 @OA\Property(
 *                     property="age",
 *                     type="integer",
 *                     example="30"
 *                 ),
 *                 @OA\Property(
 *                     property="image",
 *                     type="string",
 *                     format="binary",
 *                     nullable=true,
 *                     description="Upload an image file (e.g., JPG, PNG)"
 *                 )
 *             )
 *         )
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
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="404",
 *         description="Not found"
 *     ),
 *
  *     @OA\Response(
 *         response="504",
 *         description="Gateway Timeout"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Server error",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(
 *                 property="error",
 *                 type="string",
 *                 example="Server error"
 *             )
 *         )
 *     )
 * ),
 *
 * @OA\Get(
 *     path="/api/users/profile",
 *     summary="Get a profile",
 *     tags={"Profile"},
 *     security={{ "bearerAuth": {} }},
 *
 *     @OA\Response(
 *         response="200",
 *         description="Ok",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer",
 *                     example="1"
 *                 ),
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     example="John"
 *                 ),
 *                 @OA\Property(
 *                     property="surname",
 *                     type="string",
 *                     example="Michael"
 *                 ),
 *                 @OA\Property(
 *                     property="patronymic",
 *                     type="string",
 *                     example="Doe"
 *                 ),
 *                 @OA\Property(
 *                     property="image",
 *                     type="string",
 *                     nullable=true,
 *                     example="http://127.0.0.1:8000/image/image.png"
 *                  ),
 *                 @OA\Property(
 *                     property="age",
 *                     type="integer",
 *                     example="30"
 *                 ),
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     @OA\Property(
 *                         property="id",
 *                         type="integer",
 *                         example="1"
 *                     ),
 *                     @OA\Property(
 *                         property="email",
 *                         type="string",
 *                         example="test@gmail.com"
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="404",
 *         description="Not found"
 *     ),
 *
  *     @OA\Response(
 *         response="504",
 *         description="Gateway Timeout"
 *     ),
 * ),
 *
 * @OA\Delete(
 *     path="/api/users/profile",
 *     summary="Delete a profile",
 *     tags={"Profile"},
 *     security={{ "bearerAuth": {} }},
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
 *     ),
 *
 *     @OA\Response(
 *         response="504",
 *         description="Gateway Timeout"
 *     ),
 * )
 */
class ProfileController extends Controller
{
    //
}
