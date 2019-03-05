<?php

namespace Core\Components\Models;

use Core\Components\Filters\AdminUserFilter;
use Core\Components\Tools\StringTool;
use EloquentFilter\Filterable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property mixed password
 * @property mixed id
 * @property mixed account
 * @property mixed username
 * @property mixed last_login_time
 * @property mixed last_login_ip
 * @property mixed administer
 */
class AdminUser extends User implements
    AuthenticatableContract,
    AuthorizableContract
{
    use Authenticatable, Authorizable;
    use Notifiable, HasApiTokens, StringTool, Filterable;
    use HasRoles;

//    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'username', 'account', 'password', 'last_login_time', 'last_login_ip', 'administer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    //转换id为string
    protected $casts = ['id' => 'string'];

    const ADMIN  = 1;
    const NORMAL = 0;

    public function getModelFilterClass()
    {
        return AdminUserFilter::class;
    }


    public function checkPassword($password)
    {
        return Hash::check($password, $this->password);
    }

    public function createData($data, $administer = 0)
    {
        $data['administer'] = $administer;
        $data['password']   = Hash::make($data['password']);
        return parent::create($data);
    }

    public function ref()
    {
        $this->last_login_ip   = request()->ip();
        $this->last_login_time = now()->format('Y-m-d');
        $this->save();
    }

    public function isAdminister()
    {
        return boolval($this->administer);
    }

}