<?php

namespace App\Enums\ApiResponse;

enum ApiResponseMessagesEnum: string
{
    case SUCCESS_MESSAGE = 'Solicitação realizada com sucesso.';
    case ERROR_MESSAGE = 'Ocorreu um erro durante a sua solicitaçao. Por favor tente novamente em alguns minutos.';
}
