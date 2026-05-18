<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Dashboard;

use App\Http\Requests\Api\BaseApiRequest;

class JobTitleInsightsRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'country_id' => 'nullable|integer|exists:countries,id',
        ];
    }

    public function filters(): array
    {
        return [
            'country_id' => $this->input('country_id'),
        ];
    }
}
