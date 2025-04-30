<?php

namespace Modules\Users\User\App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\AcademicYear\App\Models\AcademicYear;
use Modules\Article\App\Models\Article;
use Modules\Contribution\App\Models\Contribution;
use Modules\Contribution\App\Models\ContributionImage;
use Modules\Faculty\App\Models\Faculty;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $appends = ['role'];

    const table = 'users';
    const id = 'id';
    const name = 'name';
    const academic_year_id = 'academic_year_id';
    const faculty_id = 'faculty_id';
    const email = 'email';
    const email_verified_at = 'email_verified_at';
    const password = 'password';
    const is_suspended = 'is_suspended';
    const is_approved = 'is_approved';
    const version = 'version';
    const created_by = 'created_by';
    const updated_by = 'updated_by';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'academic_year_id',
        'faculty_id',
        'email',
        'password',
        'is_suspended',
        'is_approved',
        'confirm_password',
        'email_verified_at',
        'version',
        'created_by',
        'updated_by',
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
            'password' => 'hashed',
        ];
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, self::academic_year_id, AcademicYear::id);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, self::faculty_id, Faculty::id);
    }

    public function saved_articles()
    {
        return $this->belongsToMany(Article::class, 'saved_articles', 'user_id', 'article_id');
    }

    public function saved_contributions()
    {
        return $this->belongsToMany(Contribution::class, 'saved_contributions', 'user_id', 'contribution_id');
    }

    public function contribution_comments()
    {
        return $this->belongsToMany(Contribution::class, 'comments', 'user_id', 'contribution_id');
    }

    public function contribution_votes()
    {
        return $this->belongsToMany(Contribution::class, 'votes', 'user_id', 'contribution_id')->withPivot(['type']);
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class, Contribution::user_id);
    }

    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first();
    }
    public function images()
    {
        return $this->hasMany(ContributionImage::class, ContributionImage::contribution_id, self::id);
    }
}
