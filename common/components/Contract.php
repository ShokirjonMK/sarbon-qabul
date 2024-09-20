<?php

namespace common\components;

class Contract
{
    public function contract($student)
    {
        $file = __DIR__ . '/file/pdf_uz.php';
        if (file_exists($file)) {
            // Bu bufferga natijani yozish uchun
            ob_start();

            // $student o'zgaruvchisi global o'zgaruvchi sifatida faylda foydalanish uchun
            include $file;

            // Bufferdan olingan natijani olish
            $content = ob_get_clean();
            return $content;
        }
    }
}
