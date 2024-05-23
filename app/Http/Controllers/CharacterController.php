<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    protected $baseUri;
    protected $httpClient;

    public function __construct() 
    {
        $this->baseUri = config('api.baseUrl');
        $this->httpClient = new Client(['base_uri' => $this->baseUri]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $page = $request->query('page', 1);
        $data = [];

        try {
            $response = $this->httpClient->request(
                'GET',
                'character',
                ['query' => ['page' => $page]]
            );

            $data = json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 500;
            $errorMessage = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();

            return response()->json([
                'error' => 'Unable to fetch data',
                'message' => $errorMessage
            ], $statusCode);
        }

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
