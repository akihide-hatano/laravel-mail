<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\OutboxStatus;
use App\Models\MailOutbox;

class UpdateOutboxRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $row = $this->route('outbox');

    dump([
        'exists' => (bool) $row,
        'id'     => $row?->id,
        'status' => $row?->status?->value, // Enumキャストしてるなら value
    ]);

        return $row && in_array($row->status,[Outboxstatus::Draft],true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'to_email' => ['required','email'],
            'subject'  => ['nullable','string','max:255'],
            'body'     => ['nullable','string'],
        ];
    }

    public function attributes(): array
    {
        return['to_email'=>'宛先メール','subject'=>'件名','body'=>'本文'];
    }
}
