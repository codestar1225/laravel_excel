<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $appends = ['interest_payments', 'override_payments', 'ranking_payments'];

    protected $guarded = ['id'];

    public function admin(){
        return $this->belongsTo('App\User', 'admin_id', 'id');
    }

    public function getInterestPaymentsAttribute(){
        $res = ['ETH' => 0, 'FELTA' => 0];
        $rows = Transaction::join('wallets', 'wallets.id', 'transactions.wallet_id')
                ->where('transactions.type', 'INT')->where('ref_id', $this->id)
                ->selectRaw("sum(transactions.amount) as total, wallets.type")
                ->groupBy('wallets.type')->get();
        foreach($rows as $r){
            $res[$r->type] = floatval($r->total);
        }
        return $res;
    }

    public function getOverridePaymentsAttribute(){
        $res = ['ETH' => 0, 'FELTA' => 0];
        $rows = Transaction::join('wallets', 'wallets.id', 'transactions.wallet_id')
                ->where('transactions.type', 'OVRINT')->where('ref_id', $this->id)
                ->selectRaw("sum(transactions.amount) as total, wallets.type")
                ->groupBy('wallets.type')->get();
        foreach($rows as $r){
            $res[$r->type] = floatval($r->total);
        }
        return $res;
    }

    public function getRankingPaymentsAttribute(){
        $res = ['ETH' => 0, 'FELTA' => 0];
        $rows = Transaction::join('wallets', 'wallets.id', 'transactions.wallet_id')
                ->where('transactions.type', 'RNKINT')->where('ref_id', $this->id)
                ->selectRaw("sum(transactions.amount) as total, wallets.type")
                ->groupBy('wallets.type')->get();
        foreach($rows as $r){
            $res[$r->type] = floatval($r->total);
        }
        return $res;
    }

    public function transactions(){
        return $this->morphMany('App\Transaction', 'ref');
    }
}
