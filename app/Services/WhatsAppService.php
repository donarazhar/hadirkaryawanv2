<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send WhatsApp Message (Mock Version)
     * 
     * Saat ini bertindak sebagai mock/stub yang mencatat ke Log.
     * Nantinya logika curl/Http::post() ke vendor WA diletakkan di sini.
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public static function send($phone, $message)
    {
        try {
            // TODO: Integrasikan dengan API WhatsApp Gateway (contoh Fonnte/Wablas)
            // Http::withHeaders(['Authorization' => 'TOKEN'])->post('url', [
            //    'target' => $phone,
            //    'message' => $message
            // ]);

            // Mock: Mencatat pesan WA ke sistem log
            Log::info("WHATSAPP SENDED", [
                'to' => $phone,
                'message' => $message
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp message: " . $e->getMessage());
            return false;
        }
    }
}
