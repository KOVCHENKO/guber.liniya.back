<?php

namespace App\src\Services\Util;


class TranslationService
{
    /**
     * @param string $status
     * @return string
     * Перевести на русский язык статус обработки заявки
     */
    public static function translateClaimStatus(string $status): string {
        switch ($status) {
            case 'created':
                return 'создана';
                break;
            case 'assigned':
                return 'назначена';
                break;
            case 'executed':
                return 'выполнена';
                break;
            case 'rejected':
                return 'отказано';
                break;
        }
    }
}