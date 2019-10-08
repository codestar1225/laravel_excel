<?php

namespace App\Jobs;

use App\Rank;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class Ranking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ranks = Rank::all();
        $users = User::select('*')->active()->with('rank')->with('plan')->where('plan_id', '>', 0)
            ->withDirectSponsorsQty()->withGroupSales()->orderBy('id', 'desc')->get();

        $ranked_users = [];
        foreach ($users as $user) {
            if (intval($user->direct_sponsors_qty) == 0) {
                continue;
            }

            $sales = $user->group_sales + $user->plan->price;

            $dl = DB::table('users')
                ->select('rank_id', DB::raw('count(*) as total'))
                ->where('sponsor_id', $user->id)
                ->groupBy('rank_id')
                ->orderBy('rank_id')
                ->get();

            $rankGroups = [];
            foreach ($dl as $d) {
                $rankGroups[$d->rank_id] = $d->total;
            }

            $rankID = 1;
            $oldRank = $user->rank_id;
            foreach ($ranks as $rank) {
                if (isset($rankGroups[$rank->target_id]) &&
                    $rankGroups[$rank->target_id] >= $rank->target_count &&
                    $sales >= $rank->sales &&
                    $user->plan->price >= $rank->invest
                ) {
                    $rankID = $rank->id;
                }
            }
            
            if ($rankID != $user->rank_id) {
                $user->rank_id = $rankID;
                $user->save();
            }
        }
    }
}
