<?php

use App\Http\Controllers\API\APIProductController;
use App\Http\Controllers\API\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/products/price', [APIProductController::class, 'getPrice']);
Route::post('/dialogflow-webhook', [WebhookController::class, 'handleWebhook']);
