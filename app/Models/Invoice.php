<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use HasFactory;

    public const array STATUSES = [
        'draft' => 'Borrador',
        'sent' => 'Enviada',
        'paid' => 'Pagada',
    ];

    protected $fillable = [
        'client_id',
        'number',
        'amount',
        'status',
        'issued_at',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'issued_at' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return Attribute<string, never>
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn (): string => self::STATUSES[$this->status]);
    }

    public function attachFile(UploadedFile $file, ?string $name = null): void
    {
        $this->deleteAttachmentFile();

        $this->attachment_path = $file->store('attachments', 'local');
        $this->attachment_name = $name ?? $file->getClientOriginalName();
    }

    public function deleteAttachmentFile(): void
    {
        if ($this->attachment_path !== null) {
            Storage::disk('local')->delete($this->attachment_path);
        }
    }
}
