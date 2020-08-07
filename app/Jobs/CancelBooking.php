<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $bookingId;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($bookingId)
    {
        $this->bookingId = $bookingId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        print_r($this->bookingId);
        // Todo: 
        /**
         * Get All Room Availabilities of this booking id whose status is "booked"
         * Change all these status to "canceled"
         * Change avaibiality to 0 (It should be available after cancelling)
         */
    }
}
