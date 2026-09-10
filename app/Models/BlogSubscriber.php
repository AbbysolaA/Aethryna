<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogSubscriber extends Model
{
    protected $fillable = [
        'email',
        'token',
        'unsubscribed_at',
    ];

    protected $casts = [
        'unsubscribed_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->whereNull('unsubscribed_at');
    }

    /**
     * Subscribe an address, or bring a lapsed one back.
     *
     * The token survives resubscription: it identifies the address, not the
     * stint, and links in already-sent emails should keep working.
     */
    public static function enrol(string $email): self
    {
        $subscriber = self::firstOrNew(['email' => $email]);

        $subscriber->token ??= Str::random(48);
        $subscriber->unsubscribed_at = null;
        $subscriber->save();

        return $subscriber;
    }

    public function unsubscribeUrl(): string
    {
        return route('blog.unsubscribe', $this->token);
    }
}
