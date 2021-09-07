<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Jiannius\Scaffold\Notifications\ActivateAccountNotification;
use Jiannius\Scaffold\Traits\Model as ModelTrait;
use Jiannius\Scaffold\Traits\HasRole;
use Jiannius\Scaffold\Traits\HasTeam;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
	use ModelTrait;
    use HasRole;
    use HasTeam;

    protected $fillable = [
		'name',
		'email',
		'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role_id' => 'integer',
    ];

    /**
     * Scope for fussy search
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q
                    ->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }
    }

    /**
     * Get is user pending invitation attribute
     * 
     * @return boolean
     */
    public function getIsPendingActivateAttribute()
    {
        return $this->password ? false : true;
    }

    /**
     * Invite user to activate account
     * 
     * @return void
     */
    public function invite()
    {
        if ($this->is_pending_activate) {
            $this->notify(new ActivateAccountNotification());
        }
    }

    /**
     * Get the activate account mail message
     * 
     * @param string $activateUrl
     * @return MailMessage
     */
    public function getActivateAccountMailMessage($activateUrl)
    {
		return (new MailMessage)
			->subject('[' . config('app.name') . '] Activate Your Account')
			->greeting('Hello!')
            ->line('Please click the button below to activate your account.')
			->action('Activate Account', $activateUrl);
    }
}
