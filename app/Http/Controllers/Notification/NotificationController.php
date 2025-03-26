<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Notification\TaskNotification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    //
    public function show(Notification $notification)
    {
        DB::beginTransaction();
        try {
            $notification->update(['is_read' => true]);
            DB::commit();
            return redirect($notification->url);
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'You could visit this notification');
            return back();
        }
    }

    public function showTask(TaskNotification $notification)
    {
        DB::beginTransaction();
        try {
            $notification->update(['read_at' => Carbon::now()->format('Y-m-d H:i:s')]);
            DB::commit();
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'You could visit this notification');
            return back();
        }
    }
}
