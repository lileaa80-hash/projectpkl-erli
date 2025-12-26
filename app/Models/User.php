<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'google_id',
        'phone',
        'address',
    ];

    /**
     * Kolom tersembunyi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting otomatis
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==================================================
    // RELATIONSHIPS
    // ==================================================

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ==================================================
    // WISHLIST
    // ==================================================

    // Pivot wishlist
    public function wishlists()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
                    ->withTimestamps();
    }

    // Alias
    public function wishlist()
    {
        return $this->wishlists();
    }

    // Alias legacy
    public function wishlistProducts()
    {
        return $this->wishlists();
    }

    // Cek produk ada di wishlist
    public function hasInWishlist(Product $product): bool
    {
        return $this->wishlists()
                    ->where('product_id', $product->id)
                    ->exists();
    }

    // ==================================================
    // HELPERS
    // ==================================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ==================================================
    // ACCESSORS
    // ==================================================

    public function getAvatarUrlAttribute(): string
    {
        // Avatar upload lokal
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        // Avatar dari Google / URL eksternal
        if (str_starts_with($this->avatar ?? '', 'http')) {
            return $this->avatar;
        }

        // Fallback Gravatar
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return substr($initials, 0, 2);
    }
}