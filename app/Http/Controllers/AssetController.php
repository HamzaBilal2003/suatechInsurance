<?php
namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Asset;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AssetNotification;

class AssetController extends Controller
{
    public function index(Client $client)
    {
        $assets = $client->assets()->get();
        return view('assets.index', compact('client', 'assets'));
    }

    public function create(Client $client)
    {
        return view('assets.create', compact('client'));
    }

    public function store(Request $request, Client $client)
    {
        $data = $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_price' => 'required|numeric',
            'supplier_name' => 'nullable|string|max:255',
            'purchase_date' => 'required|date',
            'attached_files' => 'nullable|array',
            'attached_files.*' => 'file|mimes:jpg,png,pdf',
        ]);

        // Handle file uploads
        if ($request->hasFile('attached_files')) {
            $uploadedFiles = [];
            foreach ($request->file('attached_files') as $file) {
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('clients/assets'), $filename);
            $uploadedFiles[] = $filename;
            }
            $data['attached_files'] = json_encode($uploadedFiles);
        }

        $asset = $client->assets()->create($data);

        // Notify client and broker
        // Mail::to([$client->email, $client->broker->email])->send(new \App\Mail\AssetNotification($asset, 'created'));
        if(Auth::user()->role != 'client'){
        return redirect()->route('assets.index', $client)->with('success', 'Asset created successfully.');
        }else {
            $activity = Activity::create([
                'action'=> 'Added asset',
                'user_id'=> Auth::user()->client->id
            ]);
            return redirect()->route('client.clientsideAssets', Auth::user()->client->broker->broker_name)->with('success', 'Asset created successfully.');
        }
    }

    public function edit(Client $client, Asset $asset)
    {
        return view('assets.edit', compact('client', 'asset'));
    }

    public function update(Request $request, Client $client, Asset $asset)
    {
        $data = $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_price' => 'required|numeric',
            'supplier_name' => 'nullable|string|max:255',
            'purchase_date' => 'required|date',
            'attached_files' => 'nullable|array',
            'attached_files.*' => 'file|mimes:jpg,png,pdf',
        ]);

        // Preserve existing files
        $existingFiles = $asset->attached_files ? json_decode($asset->attached_files, true) : [];

        // Handle new file uploads
        if ($request->hasFile('attached_files')) {
            $newFiles = [];
            foreach ($request->file('attached_files') as $file) {
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('clients/assets'), $filename);
            $newFiles[] = $filename;
            }
            $data['attached_files'] = json_encode(array_merge($existingFiles, $newFiles));
        } else {
            $data['attached_files'] = json_encode($existingFiles); // Keep existing files if no new files are uploaded
        }

        $asset->update($data);

        // Notify client and broker
        // Mail::to([$client->email, $client->broker->email])->send(new \App\Mail\AssetNotification($asset, 'updated'));

        if(Auth::user()->role != 'client'){
        return redirect()->route('assets.index', $client)->with('success', 'Asset Updated successfully.');
        }else {
            $activity = Activity::create([
                'action'=> 'Asset updated',
                'user_id'=> Auth::user()->client->id
            ]);
            return redirect()->route('client.clientsideAssets', Auth::user()->client->broker->broker_name)->with('success', 'Asset created successfully.');
        }
    }

    public function destroy(Client $client, Asset $asset)
    {
        // Remove attached files if they exist
        if ($asset->attached_files) {
            $oldFiles = json_decode($asset->attached_files, true) ?? [];
            foreach ($oldFiles as $oldFile) {
                $filePath = public_path('clients/assets/' . $oldFile);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }
        $asset->delete();

        // Notify client and broker
        // Mail::to([$client->email, $client->broker->email])->send(new \App\Mail\AssetNotification($asset, 'deleted'));

        if(Auth::user()->role != 'client'){
        return redirect()->route('assets.index', $client)->with('success', 'Asset Deleted successfully.');
        }else {
            $activity = Activity::create([
                'action'=> 'Asset Deleted',
                'user_id'=> Auth::user()->client->id
            ]);
            return redirect()->route('client.clientsideAssets', Auth::user()->client->broker->broker_name)->with('success', 'Asset created successfully.');
        }
    }

    public function export(Client $client, $format)
    {
        $assets = $client->assets()->get();

        if ($format === 'pdf') {
            $pdf = \PDF::loadView('assets.export', [
            'assets' => $assets,
            'client' => $client
            ]);
            return $pdf->download('assets.pdf');
        } elseif ($format === 'csv') {
            $csv = \League\Csv\Writer::createFromString('');
            $csv->insertOne(['Client Name', 'Type', 'Description', 'Make', 'Model', 'Serial Number', 'Purchase Price', 'Supplier Name', 'Purchase Date']);
            foreach ($assets as $asset) {
            $csv->insertOne([
                $client->name,
                $asset->type,
                $asset->description,
                $asset->make,
                $asset->model,
                $asset->serial_number,
                $asset->purchase_price,
                $asset->supplier_name,
                $asset->purchase_date,
            ]);
            }
            return response((string) $csv)->header('Content-Type', 'text/csv')->header('Content-Disposition', 'attachment; filename="assets.csv"');
        }

        return redirect()->route('assets.index', $client)->with('error', 'Invalid export format.');
    }

    public function emailRecords(Client $client)
    {
        $assets = $client->assets()->get();
        $pdf = \PDF::loadView('assets.export', compact('assets','client'))->output();

        Mail::to([$client->email, $client->broker->user->email])->send(new \App\Mail\AssetRecordsEmail($pdf));

        return redirect()->route('assets.index', $client)->with('success', 'Records emailed successfully.');
    }

    public function removeFile(Request $request, Client $client, Asset $asset)
    {
        $filePath = $request->file;

        $files = $asset->attached_files ? json_decode($asset->attached_files, true) : [];
        $updatedFiles = array_filter($files, function ($file) use ($filePath) {
            return $file !== $filePath;
        });

        $asset->attached_files = json_encode($updatedFiles);
        $asset->save();

        // Optionally delete the file from storage
        if (file_exists(public_path($filePath))) {
            unlink(public_path($filePath));
        }

        return response()->json(['message' => 'File removed successfully']);
    }
}