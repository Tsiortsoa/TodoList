<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    protected Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('tasks.show', $this->task);

        return (new MailMessage)
            ->subject('Nouvelle tâche assignée : ' . $this->task->title)
            ->greeting('Bonjour ' . $notifiable->name . '!')
            ->line('Une nouvelle tâche vous a été assignée par ' . $this->task->creator->name)
            ->line('**Tâche :** ' . $this->task->title)
            ->line('**Date d\'échéance:** ' . $this->task->due_date->format('M d, Y H:i'))
            ->action('Voir la tâche', $url)
            ->line('Merci d\'utiliser notre application !');
    }
}