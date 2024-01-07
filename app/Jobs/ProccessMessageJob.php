<?php

namespace App\Jobs;

use App\Events\StoreMessageEvent;
use App\Models\Image;
use App\Models\User;
use App\Service\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProccessMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $message;
    private $data;

    /**
     * Create a new job instance.
     */
    public function __construct($message, $data)
    {
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $answeredForUsersIds = User::getCleanedIds($this->data);

        $imageIds = getIdsWithPregMatch($this->data, '/img_id=[\d]+/', '/img_id=/');

        broadcast(new StoreMessageEvent($this->message))->toOthers();

        Image::updateMessageId($imageIds, $this->message);

        Image::cleanFromStorage();

        Image::cleanFromTable();

        $this->message->answeredUsers()->attach($answeredForUsersIds);

        $answeredForUsersIds->each(function ($id) {
            NotificationService::store($this->message, 'Вам ответили', $id);
        });
    }
}
