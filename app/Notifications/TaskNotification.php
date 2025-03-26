<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Notification\TaskNotification as TaskNotificationModel;

class TaskNotification extends Notification
{
    use Queueable;

    protected $notification;

    protected $table = "task_notifications";

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TaskNotificationModel $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
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
                    ->from('noreply@celermart.com', 'Glass Pipe')
                    ->subject('New task created')
                    ->line('A new task has been created and assiged to you')
                    ->line('Thank you for using our application!');
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
            'message' => 'A new task has been created',
            
            'task_id' => $this->notification->notifiable_id,
        ];
    }
}
