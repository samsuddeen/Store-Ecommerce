<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\NexmoMessage;

class TaskReassignedNotification extends Notification
{
    use Queueable;

    public $task;
    public $reason;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($task , $reason)
    {
        $this->task = $task;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Task Reassigned')
            ->line('The task has been reassigned.')
            ->line('Reason: ' . $this->reason)
            ->line('Title' . $this->task->title)
            ->action('View Task', url('/tasks/' . $this->task->id));
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'task_id' => $this->task->id,
            'reason' => $this->reason,
            'assigned_by' => $this->task->reassignedBy->id,
        ]);
    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
            ->content('Task Reassigned: ' . $this->reason);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
