<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'fileInput',
        'role',
        'ic', 
        'BirthDayDate', 
        'gender', 
        'certificate', 
        'fileInput',
        'address', 
        'address2', 
        'address3', 
        'education', 
        'nationality', 
        'otherNationality'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password', 
        'remember_token', 
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }
   public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function isDefaultEmail()
    {
        return str_ends_with($this->email, '@gmail.com'); // Adjust as needed
    }

    public function hasVerifiedEmail()
    {
        return $this->email_verified_at !== null;
    }


    
}