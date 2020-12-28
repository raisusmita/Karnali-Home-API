<?php

namespace App\Console\Commands;

use App\Model\Booking;
use App\Model\RoomAvailability;
use Illuminate\Console\Command;
use Carbon\Carbon;


class BookingCancellation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:booking';
    const CANCEL = 'cancelled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will cancel the booking which exceeds 12 hours';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Todo: Improve where caluse to get Booking. where (current Date - created date ->diffInMinutes >=720)
        $bookings = Booking::where('status', 'active')->get();
        foreach ($bookings as $booking) {
            $bookingDate = Carbon::parse($booking->created_at);
            $currentDate = Carbon::now();
            if ($currentDate->diffInMinutes($bookingDate) >= 1) {
                $ra = RoomAvailability::where('booking_id', $booking->id)->first();
                $ra->status = self::CANCEL;
                $ra->availability = '0';
                $ra->save();
                $booking->status = self::CANCEL;
                $booking->save();
            }
        }
    }
}
