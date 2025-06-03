<?php

namespace App\Http\Controllers\Broker;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Broker;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrokerController extends Controller
{
    public function index(Request $request)
    {
        $query = Broker::query();

        if ($request->filled('broker_name')) {
            $query->where('broker_name', 'like', '%' . $request->broker_name . '%');
        }

        if ($request->filled('broker_phone')) {
            $query->where('contact_person', 'like', '%' . $request->broker_phone . '%');
        }

        if ($request->filled('broker_email')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->broker_email . '%');
            });
        }

        $brokers = $query->with('user')->get();
        return view('broker.index', compact('brokers'));
    }
    public function create()
    {
        $users = User::where('role', 'broker')->get();
        return view('broker.create', compact('users'));
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'phone' => 'required|string',
                'address' => 'required|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'user_id' => 'required|exists:users,id|unique:brokers,user_id',
            ],
            [
                'user_id.unique' => 'This user already has a broker associated with it',
            ]
        );

        $broker = new Broker();
        $broker->broker_name = $request->name;
        $broker->contact_person = $request->phone;
        $broker->address = $request->address;
        $broker->user_id = $request->user_id;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('brokers'), $filename);
            $broker->logo_path = 'brokers/' . $filename;
        }
        $broker->save();
        return response()->json([
            'success' => 'Broker created successfully',
        ], 201);
    }
    public function show($id)
    {
        $broker = Broker::findOrFail($id);
        return view('broker.show', compact('broker'));
    }
    public function edit($username, $id)
    {
        $broker = Broker::findOrFail($id);
        $users = User::where('role', 'broker')->get();
        return view('broker.edit', compact('broker', 'users', 'username'));
    }
    public function update(Request $request, $username, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'required|exists:users,id',
        ]);

        $broker = Broker::findOrFail($id);
        $broker->broker_name = $request->name;
        $broker->contact_person = $request->phone;
        $broker->address = $request->address;
        $broker->user_id = $request->user_id;

        if ($request->hasFile('logo')) {
            if ($broker->logo_path) {
                unlink(public_path($broker->logo_path));
            }
            $file = $request->file('logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('brokers'), $filename);
            $broker->logo_path = 'brokers/' . $filename;
        }

        $broker->save();
        return response()->json([
            'success' => 'Broker updated successfully',
        ], 200);
    }
    public function destroy($username, $id)
    {
        $broker = Broker::findOrFail($id);
        if ($broker->logo_path) {
            unlink(public_path($broker->logo_path));
        }
        $broker->delete();
        return redirect()->back()->with('success', 'Broker deleted successfully');
    }

    public function report(Request $request)
    {
        $query = Client::query();
        if ($request->filled('client_name')) {
            $query->where('client_name', 'like', "%{$request->client_name}%");
        }
        if ($request->filled('client_email')) {
            $query->where('email', 'like', "%{$request->client_email}%");
        }

        $reports = $query->where('broker_id', Auth::user()->broker->id)
            ->with('assets', 'documents')
            ->get();

        return view('broker.report.report', compact('reports'));
    }

    public function downloadMonthlyReportPdf()
    {
        $monthlyReports = Client::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total_clients')
            ->where('broker_id', Auth::user()->broker->id)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($item) {
                $clientsInMonth = Client::whereYear('created_at', $item->year)
                    ->whereMonth('created_at', $item->month)
                    ->where('broker_id', Auth::user()->broker->id)
                    ->pluck('id');

                $clientsWithAssets = Asset::whereIn('client_id', $clientsInMonth)->distinct('client_id')->count('client_id');
                $clientsWithDocuments = \App\Models\Document::whereIn('client_id', $clientsInMonth)->distinct('client_id')->count('client_id');

                return [
                    'year' => $item->year,
                    'month' => $item->month,
                    'total_clients' => $item->total_clients,
                    'clients_with_assets' => $clientsWithAssets,
                    'clients_with_documents' => $clientsWithDocuments,
                ];
            });
        // return $monthlyReport;
        // return view('broker.report.monthly_pdf', compact('monthlyReports'));
        $pdf = \PDF::loadView('broker.report.monthly_pdf', compact('monthlyReports'));
        $fileName = 'monthly_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        return $pdf->download($fileName);
    }

    public function BrokerProifle()
    {
        $id = Auth::user()->Broker->id;
        $broker = Broker::findOrFail($id);
        $users = User::where('role', 'broker')->get();
        return view('broker.profile', compact('broker', 'users'));
    }

}
