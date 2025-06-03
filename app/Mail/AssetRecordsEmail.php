<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssetRecordsEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;

    public function __construct($pdf)
    {
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Asset Records')
            ->attachData($this->pdf, 'assets.pdf', [
                'mime' => 'application/pdf',
            ])
            ->view('emails.asset_records');
    }
}