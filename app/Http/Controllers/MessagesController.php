<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MessagesService;

class MessagesController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(MessagesService $service)
    {
        return response()->json( $service->getLastMessages() );
    }

    public function store(MessagesService $service, Request $request)
    {
        return response()->json( $service->storeMessage($request) );
    }
}
