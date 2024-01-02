<?php

namespace App\Services;

use GuzzleHttp\Client;

class GPTService
{
    public function askAi($request)
    {
       
       
        $prompt = $this->instructions($request);
        try {
            $response = $this->sendGPTRequest($prompt);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if ($this->isValidResponse($responseData)) {
                return $this->extractTextFromResponse($responseData);
            } else {
                return 1;
            }
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return 2;
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    private function instructions($request){
        $prompt = "This is the main content ".$request['prompt'];
        $prompt .= "but it should use this tone:".$request['tone'];
        $prompt .= "It should a keyword density of". $request['keyword_density'];
        $prompt .= " the write up must contain only 2 random valid image link that should be inside a parenthesis
                    and it must take this structure all in small letter 
                    (image_src:)
                at the end of the write up from pixabay.com or any website";
        return $prompt;
    }
    private function sendGPTRequest($prompt)
    {
        $client = new Client(['verify' => false]);

        return $client->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=AIzaSyCIZWRMkRP7aemDtKVLieA9j7-kR-WmCoU', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'contents' => [
                    'parts' => [
                        [
                            'text' => $prompt,
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function isValidResponse($responseData)
    {
        return isset($responseData['candidates']) && !empty($responseData['candidates']);
    }

    private function extractTextFromResponse($responseData)
    {
        $firstCandidate = $responseData['candidates'][0] ?? null;

        if ($firstCandidate && isset($firstCandidate['content']['parts'])) {
            return $firstCandidate['content']['parts'][0]['text'];
        } else {
            return "Error: No 'parts' found in the response.";
        }
    }
}
