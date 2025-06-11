<?php
 
// fungsi untuk mengembalikan format rupiah dari suatu nominal tertentu
// dengan pemisah ribuan 
function rupiah($nominal) {
    return "Rp ".number_format($nominal);
}

function dolar($nominal) {
    return "USD ".number_format($nominal);
}

function deskripsimakanan(){
    $apiKey = env('GEMINI_API_KEY');
        $model = 'gemini-2.0-flash'; // atau model lain yang tersedia
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
        $question = "Buatkan deskripsi promosi singkat dalam satu paragraf untuk produk rice bowl. Gunakan bahasa yang menggugah selera, tanpa menggunakan emoji, hashtag, atau format markdown. Deskripsi ini akan digunakan dalam HTML sebagai konten promosi makanan, jadi tulisannya harus bersih, profesional, dan menarik. Fokus pada kepraktisan, kelezatan, dan cocok disantap kapan saja.";
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $question],
                    ],
                ],
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'Curl error: ' . curl_error($ch);
        } else {
            $result = json_decode($response, true);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $answer = $result['candidates'][0]['content']['parts'][0]['text'];
            } else {
                $answer = 'Respons tidak valid: ' . $response;
            }
            return $answer;
        }
}

function deskripsiminuman(){
    $apiKey = env('GEMINI_API_KEY');
        $model = 'gemini-2.0-flash'; // atau model lain yang tersedia
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
        $question = "Buatkan deskripsi promosi singkat dalam satu paragraf untuk produk minuman es teh. Gunakan bahasa yang menggugah selera, tidak menggunakan emoji, hashtag, atau format markdown. Tulisan harus cocok untuk dimasukkan ke dalam HTML sebagai konten promosi. Fokus pada kesegaran, rasa manis yang pas, dan cocok diminum kapan saja.";
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $question],
                    ],
                ],
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'Curl error: ' . curl_error($ch);
        } else {
            $result = json_decode($response, true);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $answer = $result['candidates'][0]['content']['parts'][0]['text'];
            } else {
                $answer = 'Respons tidak valid: ' . $response;
            }
            return $answer;
        }
}

function deskripsisambal(){
    $apiKey = env('GEMINI_API_KEY');
        $model = 'gemini-2.0-flash'; // atau model lain yang tersedia
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
        $question = "Buatkan deskripsi promosi singkat dalam satu paragraf untuk produk sambal. Gunakan bahasa yang menggugah selera dan menggambarkan rasa pedasnya. Jangan gunakan emoji, hashtag, atau format markdown. Deskripsi ini akan digunakan dalam elemen HTML, jadi tulisannya harus bersih dan profesional.";
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $question],
                    ],
                ],
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'Curl error: ' . curl_error($ch);
        } else {
            $result = json_decode($response, true);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $answer = $result['candidates'][0]['content']['parts'][0]['text'];
            } else {
                $answer = 'Respons tidak valid: ' . $response;
            }
            return $answer;
        }
}

function rekomendasi(){
    $apiKey = env('GEMINI_API_KEY');
        $model = 'gemini-2.0-flash'; // atau model lain yang tersedia
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
        $question = "Buat 1 rekomendasi menu yang terdiri dari 1 rice bowl, 1 minuman, 1 topping, dan 1 sambal. Tampilkan kombinasi itemnya dalam format: [Rice Bowl] + [Minuman] + [Topping] + [Sambal]. Di bawahnya, tambahkan deskripsi singkat yang menarik dan menggugah selera dalam satu kalimat.

                    Pilihan menu:
                    Rice Bowl: Dori Fish, Cumi Bakar, Empal Gepuk, Ayam Betutu, Paru Balado
                    Minuman: Air Mineral 1.5L, Es Teh Manis
                    Topping: Chicken Skin, Sate Lilit 3pcs
                    Sambal: Sambal Matah, Mushroom Sauce

                    Contoh hasil:

                    Cumi Bakar + Es Teh Manis + Chicken Skin + Sambal Matah. Perpaduan rasa laut yang smoky, renyahnya topping, dan pedas segar sambal matah siap memanjakan lidahmu.
                    jangan tambahkan yang tidak perlu, pisahkan rekomendasi menu dengan deskripsi dengan tanda .";
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $question],
                    ],
                ],
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'Curl error: ' . curl_error($ch);
        } else {
            $result = json_decode($response, true);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $answer = $result['candidates'][0]['content']['parts'][0]['text'];
            } else {
                $answer = 'Respons tidak valid: ' . $response;
            }
            return $answer;
        }
}