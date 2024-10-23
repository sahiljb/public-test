<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Models\Role;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'ip_addr',
        'profile'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function getUserRoleList($roleName, $length = 10, $start = 0, $searchValue = null)
    {
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            return collect();
        }

        $query = $role->users();

        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('users.name', 'like', "%{$searchValue}%")
                    ->orWhere('users.phone', 'like', "%{$searchValue}%");
            });
        }

        $total = $query->count();

        $query->skip($start)->take($length);

        $data = $query->orderBy('users.id', 'desc')->get();

        return [
            'data' => $data,
            'total' => $total
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
 
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getUsersDetails($userId){ 
        $userData = User::find($userId);

        $baseUrl = url('/');

        $profileUrl = $baseUrl . '/storage/' . $userData->profile;


        $roleName = $userData->getRoleNames()->first();
        $response = [
            'id' => $userData->id,
            'role' => $roleName,
            'name' => $userData->name,
            'email' => $userData->email,
            'status' => $userData->status,
            'ip_addr' => $userData->ip_addr,
            'phone' => $userData->phone,
            'profile' => ($userData->profile != '') ? $profileUrl : '',
            'created_at' => $userData->created_at,
            'updated_at' => $userData->updated_at
        ];

        return $response;
    } 
    
}
