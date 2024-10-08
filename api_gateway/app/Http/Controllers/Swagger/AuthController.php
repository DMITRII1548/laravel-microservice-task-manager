<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Post(
 *     path="/api/login",
 *     summary="Login",
 *     tags={"Auth"},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                      @OA\Property(
 *                          property="email",
 *                          type="email",
 *                          example="user@gmail.com"
 *                      ),
 *
 *                      @OA\Property(
 *                          property="password",
 *                          type="password",
 *                          example="12345678"
 *                      )
 *                 )
 *             }
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="200",
 *         description="Ok",
 *
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="response_code",
 *                 type="string",
 *                 example="200"
 *             ),
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 example="success"
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="success Login"
 *             ),
 *             @OA\Property(
 *                 property="token",
 *                 type="string",
 *                 example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNGJlMTkxNzBjOWI4ZmQyZGQ5YTZiNjhiOWY0MmMzZjhiZTk1NjQ0ZThiZDEzYTQ0YjU4MjhiZWJhNGQwZDAyN2UzZDI2OWJlMmVhM2IyZmEiLCJpYXQiOjE3Mjg0MDIxNTEuMzEyMjY1LCJuYmYiOjE3Mjg0MDIxNTEuMzEyMjczLCJleHAiOjE3NTk5MzgxNTAuOTMzMzg1LCJzdWIiOiI5Iiwic2NvcGVzIjpbXX0.KpPcP51-kuaermf18uvzNXgohD_Xyo1Q24c421mBHo0HwN3qqDTf6CBB86MKLOKsly2OA3MJCMAOKVBXUoyiIMlcQOHBbneDUe1VRrYzhn6rvZLmxf5cGQYKMfgQTai1bQAAHK1-UNxWlY5AP05pmr00kWC5W8uyUdAQYQA0ZS7DftS_cNOuuAIdonsiEe5EihqA3Zo3KtVqd4PDU1S7ioIFJoABBKU62Z5L239qI5McIFkCoSRzuOzvLCwehE3ZieKh-juLGvlXMemlOzig1dZ1Z8h-pqlBP6dEqRvj8w7t3tkUjCMgLIuazoGdS3-FdfmAFLgJwnBsOkUsxaPHASOhgEScBiCRn51-n2PCtEKgDF_jTOJ_WKsXeXIUWgSbM8KC5DLdzT4htkRGbqXH85LX6WBc381_ovDqyI4Os84Sbwhu7CfA_34BL4XY_sgf1rgkFSaaM-BjwUxy6yjYLIrzKf8dPsCDIELAC75KbvALbP44SoU9zCNYA8kY5U-iivMz3o04ASb6AvddS01eu5-NUnRtXoSZDdCIWX6VjQ7FdTxQ92F0DxUND9CZoQIWGwkXV1HfzbjOlOF9T7_Wul4BFz3hWiDlFddBHcJYfxEDCH9i2oQkoaVQMctN2Y67CeFLcAat6KdFKVkonaUOiGtpfsUCpmOeDIRM-rerVfA"
 *             ),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorised",
 *
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="response_code",
 *                 type="string",
 *                 example="401"
 *             ),
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 example="error"
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Unauthorised"
 *             ),
 *         )
 *     ),
 * )
 */
class AuthController extends Controller
{
    //
}
