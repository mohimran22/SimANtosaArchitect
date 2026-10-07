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
        'fasilitas'    => 'array',
        'cicilan'        => 'integer',
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

    public function province()    { return $this->belongsTo(Province::class); }
    public function city()        { return $this->belongsTo(City::class); }
    public function district()    { return $this->belongsTo(District::class); }
    public function subDistrict() { return $this->belongsTo(SubDistrict::class); }
    public function postalCode()  { return $this->belongsTo(PostalCode::class, 'postal_code_id'); }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }

    private function namaWilayah(string $relasi): ?string
    {
        $nama = $this->{$relasi}?->name;
        return $nama ? Str::title(Str::lower($nama)) : null;
    }

    // contoh: "Pontianak Selatan, Kota Pontianak, Kalimantan Barat" (dipakai di kartu homepage)
    public function getLokasiLengkapAttribute(): string
    {
        $bagian = array_filter([
            $this->namaWilayah('district'),
            $this->namaWilayah('city'),
            $this->namaWilayah('province'),
        ]);

        if ($bagian) {
            return implode(', ', $bagian);
        }

        // data lama (sebelum ada wilayah): pakai teks kota + alamat
        return trim(($this->kota ?? '') . ($this->lokasi ? ', ' . $this->lokasi : ''), ', ');
    }

    // alamat lengkap sampai kelurahan & kode pos (dipakai di halaman detail)
    public function getAlamatLengkapAttribute(): string
    {
        $bagian = array_filter([
            $this->lokasi,
            $this->namaWilayah('subDistrict'),
            $this->namaWilayah('district'),
            $this->namaWilayah('city'),
            $this->namaWilayah('province'),
            $this->postalCode?->postal_code,
        ]);

        return $bagian ? implode(', ', $bagian) : $this->lokasi_lengkap;
    }

    // ---- data agen diambil dari karyawan (homepage membacanya lewat data_get($l, 'agen_*')) ----
    public function getAgenNamaAttribute(): string
    {
        return $this->employee?->user?->short_name ?: 'Antosa Architect';
    }

    public function getAgenPeranAttribute(): string
    {
        $user = $this->employee?->user;
        if (! $user) {
            return 'Pemilik Properti';
        }

        // Employee::position bertipe array; ambil jabatan pertama yang berupa teks
        $jabatan = collect($this->employee->position ?? [])
            ->first(fn ($p) => is_string($p) && ! is_numeric($p) && trim($p) !== '');

        return $jabatan ?: 'Tim Antosa Architect';
    }

    public function getAgenFotoAttribute(): ?string
    {
        return $this->employee?->user?->photo;      // path di disk public
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