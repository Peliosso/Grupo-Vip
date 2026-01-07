<?php

// ===================== CONFIGURAÇÕES =====================
$token = "8571023798:AAHTH3rnVCqtNstU8ihGhI9OFAs9QKl7vvs";
$website = "https://api.telegram.org/bot$token";

// ===================== RECEBENDO UPDATE ==================
$update = json_decode(file_get_contents("php://input"), true);

$message = $update["message"] ?? null;

if (!$message) {
    exit;
}

$chat_id = $message["chat"]["id"];
$user_name = $message["from"]["first_name"] ?? "Usuário";

// impedir o bot de responder a ele mesmo
if (isset($message["from"]["is_bot"]) && $message["from"]["is_bot"] === true) {
    exit;
}



// ===================== 1) ENVIA A MENSAGEM DE "AGUARDE..." =====================
$aguarde = "⏳ *Aguarde, $user_name…*\nEnviando um recado importante...";

$sent = sendMessage($chat_id, $aguarde);

$sent_msg_id = json_decode($sent, true)["result"]["message_id"] ?? null;


// ===================== 2) MENSAGEM FINAL =====================

$texto_final = "⚠️ *Atenção, $user_name!*\n\n" .
               "📌 • *Bot de consultas agora apenas no nosso grupo VIP.*\n\n" .
               "💵 • *Preço:* `R$10,00` vitalício.\n\n" .
               "💠 • *Chave Pix:* `512027eb-f6fe-44da-867a-810d208e80c0`\n\n" .
               "📄 *Comprovante:* @silenciante";


// ===================== 3) BOTÃO INLINE =====================
$inline_keyboard = json_encode([
    "inline_keyboard" => [
        [
            [
                "text" => "🔎 Consultar Agora",
                "url"  => "https://t.me/silenciante"
            ]
        ]
    ]
]);


// ===================== 4) EDITA A MENSAGEM =====================
if ($sent_msg_id) {
    editMessage($chat_id, $sent_msg_id, $texto_final, $inline_keyboard);
}



// ===================== FUNÇÕES =====================

function sendMessage($chat_id, $text) {
    global $website;

    $url = $website . "/sendMessage";
    $post = [
        "chat_id" => $chat_id,
        "text" => $text,
        "parse_mode" => "Markdown"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function editMessage($chat_id, $msg_id, $text, $reply_markup = null) {
    global $website;

    $post = [
        "chat_id" => $chat_id,
        "message_id" => $msg_id,
        "text" => $text,
        "parse_mode" => "Markdown"
    ];

    if ($reply_markup) {
        $post["reply_markup"] = $reply_markup;
    }

    $url = $website . "/editMessageText";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_exec($ch);
    curl_close($ch);
}