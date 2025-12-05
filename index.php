<?php

// ===================== CONFIGURAÇÕES =====================
$token = "8571023798:AAHTH3rnVCqtNstU8ihGhI9OFAs9QKl7vvs";
$website = "https://api.telegram.org/bot$token";

// ===================== RECEBENDO UPDATE ==================
$update = json_decode(file_get_contents("php://input"), true);

$message = $update["message"] ?? null;

if (!$message) {
    exit; // nada para processar
}

$chat_id = $message["chat"]["id"];

// 📌 impede o bot de responder as próprias mensagens
if (isset($message["from"]["is_bot"]) && $message["from"]["is_bot"] === true) {
    exit;
}

// ===================== MENSAGEM BONITA =====================

$text = "⚠️ *Atenção!*\n\n" .
        "📌 • *Bot de consultas agora apenas no nosso grupo VIP.*\n\n" .
        "💵 • *Preço:* `R$20,00` vitalício.\n\n" .
        "💠 • *Chave Pix:* `1aebb1bd-10b7-435e-bd17-03adf4451088`\n\n" .
        "📄 *Comprovante:* @silenciante";

// ===================== ENVIA A MENSAGEM =====================

sendMessage($chat_id, $text);


// ===================== FUNÇÃO DE ENVIO =====================
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
    curl_exec($ch);
    curl_close($ch);
}
