<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Enums;

enum AnalysisSourceEnum: string
{
    case ThirdPartyApi = 'third_party_api';
    case InternalPythonService = 'internal_python_service';
}
