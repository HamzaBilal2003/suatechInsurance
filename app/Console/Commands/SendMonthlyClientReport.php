<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Broker;
use PDF;
use Mail;

class SendMonthlyClientReport extends Command
{
    protected $signature = 'report:monthly-client-count';
    protected $description = 'Send monthly client count report to admin via email';

    public function handle()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $brokers = Broker::withCount(['clients' => function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        }])->get();

        $data = $brokers->map(function ($broker) {
            return [
                'phone' => $broker->contact_person,
                'broker_name' => $broker->broker_name,
                'current_month_client_count' => $broker->clients_count,
            ];
        })->toArray();

        $pdf = \PDF::loadView('reports.email.brokertem', ['data' => $data]);

        Mail::send([], [], function ($message) use ($pdf) {
            $message->to('www.hamzaranar@gmail.com')
                ->subject('Monthly Client Count Report')
                ->attachData($pdf->output(), 'client_report.pdf');
        });

        $this->info('Monthly client report sent to admin.');
    }
}
