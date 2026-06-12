<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Constantes de estado
    public const STATUS_ACTIVO = 1;
    public const STATUS_VERIFICAR = 2;
    public const STATUS_INACTIVO = 0;

    protected $table = 'usuario';
    protected $primaryKey = 'usuario_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'persona_id',
        'password_hash',
        'id_rol',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];


    /**
     * Busca un usuario por username o por la cédula de su persona vinculada.
     */
    public static function findByCredentials($login)
    {
        return self::where('username', $login)
            ->orWhereHas('persona', function($query) use ($login) {
                $query->where('cedula', $login);
            })
            ->first();
    }

    public function getDashboardUrl()
    {
        return match ($this->id_rol) {
            1 => route('admin.index'),
            2 => route('admvo.index'),
            3 => route('medico.index'),
            default => route('admin.index'),
        };
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the column name for the "username" field.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    /**
     * Relación con el rol.
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'rol_id');
    }

    /**
     * Relación con la persona.
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'persona_id');
    }
}