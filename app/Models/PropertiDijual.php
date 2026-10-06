<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PropertiDijual extends Model
{
    protected $table = 'properti_dijual';
    protected $guarded = ['id'];
    protected $casts = [
        'is_published' => 'boolean',
        'harga'        => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($m) {
            $m->slug = $m->slug ?: static::uniqueSlug($m->judul);
        });
    }

    public static function uniqueSlug(string $judul): string
    {
        $base = Str::slug($judul) ?: 'properti';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function fotos()
    {
        return $this->hasMany(PropertiDijualFoto::class, 'properti_dijual_id')->orderBy('urutan')->orderBy('id');
    }
        public function tipe()
    {
        return $this->hasMany(TipeProperti::class);
    }
    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }

    // dipakai homepage lewat data_get($l, 'jumlah_foto') dan data_get($l, 'url')
    public function getJumlahFotoAttribute(): int
    {
        return ($this->foto ? 1 : 0) + ($this->fotos_count ?? $this->fotos()->count());
    }

    public function getUrlAttribute(): string
    {
        return route('listing.show', $this->slug);
    }
}