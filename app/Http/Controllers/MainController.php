<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuggestionsRequest;
use GuzzleHttp\Client;

class MainController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @OA\Post(
     *     path="/api/suggestion",
     *     summary="Check on spell check",
     *     description="Check on spell check",
     *     operationId="authRegister",
     *     tags={"Api"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass a text and language.",
     *         @OA\JsonContent(
     *             required={"text","language"},
     *             @OA\Property(property="text", type="string", example="Welcome to the world"),
     *             @OA\Property(property="language", type="string", example="en"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Wrong credentials response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="false"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="text", type="array",
     *                     @OA\Items(type="string", example="The text is required.")
     *                 ),
     *                 @OA\Property(property="language", type="array",
     *                     @OA\Items(type="string", example="The language is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfull registration.",
     *         @OA\JsonContent(
     *             required={"text","language"},
     *             @OA\Property(property="success", type="string", example="true"),
     *             @OA\Property(property="data", type="object",
     *                  @OA\Property(property="original", type="array",
     *                     @OA\Items(type="string", example="Welcame")
     *                 ),
     *                 @OA\Property(property="suggestions", type="array",
     *                     @OA\Items(type="string", example="['Welcome', 'Camel']")
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * @param SuggestionsRequest $request
     * @return mixed
     */

    public function getSuggestions(SuggestionsRequest $request) {
        try {
            $response = $this->client->post(env('API_URL_SUGGESTIONS'),[
                'form_params' => [
                    'text' => $request->text,
                    'lang' => $request->language
                ]
            ]);
            return response()->json([
                'success' => true,
                'data' => json_decode($response->getBody()->getContents(),true)
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'data' => $exception->getMessage()
            ],400);
        }
    }
}
