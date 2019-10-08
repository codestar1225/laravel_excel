<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Liliom\Inbox\Traits\HasInbox;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class User extends Authenticatable
{
    use Notifiable, HasInbox;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $refkey = strtoupper(str_random(6));

            while(User::where('referralkey', '$refkey')->count() > 0){
                $refkey = strtoupper(str_random(6));
            }

            $user->withdrawkey = strtoupper(str_random(6));
            $user->referralkey = strtoupper(str_random(6));
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'sponsor_id' => 'integer'
    ];

    public function plan()
    {
        return $this->belongsTo('App\Plan');
    }

    public function wallets()
    {
        return $this->hasMany('App\Wallet');
    }

    public function sponsor()
    {
        return $this->belongsTo('App\User', 'sponsor_id', 'id');
    }

    public function scopeActive($query){
        return $query->where('status', 1);
    }

    public function scopeValid($query){
        return $query->whereIn('status', [1,3,4]);
    }

    public function getLevelFor($sponsorID)
    {
        $sponsorID = strval($sponsorID);
        $parents = explode(',', $this->parents);
        $parents = array_filter($parents);
        $level = 1;
        $sponsorIdx = 0;
        foreach ($parents as $p) {
            if ($p == $sponsorID) {
                break;
            } else {
                $sponsorIdx++;
            }
        }
        $level = count($parents) - $sponsorIdx;
        return $level;
    }

    public function scopeWithDownlinesQty($query)
    {
        $raw = '(select count(*) from users u2 where u2.status=1 AND u2.parents like (SELECT concat("%",`users`.id,"%"))) AS downlines_qty';
        return $query->addSelect(\DB::raw($raw));
    }

    public function scopeWithGroupSales($query)
    {
        $raw = '(select sum(price) from users u2 join plans p on u2.plan_id=p.id where u2.status=1 AND u2.parents like (SELECT concat("%",`users`.id,"%"))) AS group_sales';
        return $query->addSelect(\DB::raw($raw));
    }

    public function scopeWithDirectSponsorsQty($query)
    {
        $raw = '(select count(*) from users u2 where u2.status=1 AND u2.sponsor_id=`users`.id) AS direct_sponsors_qty';
        return $query->addSelect(\DB::raw($raw));
    }

    public function rank(){
        return $this->belongsTo('App\Rank');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordNotification($token, $this->name));
    }
}

class CustomPasswordNotification extends ResetPassword
{
    public $name;

    public function __construct($token, $name)
    {
        parent::__construct($token);
        $this->name = $name;
    }

    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        $resetLink = url(config('app.url').route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false));

        return (new MailMessage)
            ->view('emails.resetpassword', ['name' => $this->name, 'link' => $resetLink])
            ->subject('Reset Password Notification');
    }
}