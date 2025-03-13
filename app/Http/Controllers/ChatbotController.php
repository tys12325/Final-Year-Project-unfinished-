<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\uni;

class ChatbotController extends Controller
{
  public function getResponse(Request $request)
{
    $userMessage = $request->input('message');
    $witToken = 'EJMGBD67ZWG44FGK62B7VQB44ODPM3RM';

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $witToken,
    ])->get("https://api.wit.ai/message?v=20230201&q=" . urlencode($userMessage));

    $witResponse = $response->json();
    $intent = $witResponse['intents'][0]['name'] ?? null;
    $university = $witResponse['entities']['university:university'][0]['value'] ?? '';




    if (!$intent) {
        return response()->json(['message' => "I didn't understand. Could you rephrase?"]);
    }

    switch ($intent) {
        case 'find_university':
            $botResponse = "There are many universities in Malaysia offering that course. Would you like to know more about a specific university?";
            break;

        case 'tuition_fee':
            if ($university) {
               $universityData = uni::where('uniName', 'LIKE', '%' . $university . '%')->first();
                
                if ($universityData) {
                    $botResponse = "The tuition fee for {$universityData->uniName} is approximately RM {$universityData->Category}.";
                } else {
                    $botResponse = "Sorry, I couldn't find tuition fees for $university.";
                }
            } else {
                $botResponse = "I couldn't detect the university name. Could you please specify which university you're asking about?";
            }
            break;

        case 'admission_requirements':
            $botResponse = "The admission requirements depend on the university. Could you specify which university you are interested in?";
            break;

        default:
            $botResponse = "I’m not sure about that. Could you rephrase your question?";
            break;
    }

    return response()->json(['message' => $botResponse]);
}
}
