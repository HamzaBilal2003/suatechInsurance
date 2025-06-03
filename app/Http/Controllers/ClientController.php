<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\adminSetting;
use App\Models\Broker;
use App\Models\Client;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('client_name')) {
            $query->where('client_name', 'like', '%' . $request->client_name . '%');
        }

        if ($request->filled('client_phone')) {
            $query->where('cell_number', 'like', '%' . $request->client_phone . '%');
        }

        if ($request->filled('client_email')) {
            $query->where('email', 'like', '%' . $request->client_email . '%');
        }
        if (Auth::user()->role == 'broker') {
            $query->where('broker_id', Auth::user()->broker->id);
        }
        $clients = $query->with('user')->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $usersquery = User::query();
        if (Auth::user()->role == 'broker') {
            $usersquery->where('created_by', Auth::user()->id);
        }
        $users = $usersquery->where('role', 'client')->get();
        return view('clients.create', compact('users'));
    }

    public function store(Request $request)
    {
        $space = adminSetting::where('name', 'database_space')->first()->value;
        $request->validate([
            'client_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'office_number' => 'nullable|string|max:20',
            'cell_number' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'user_id' => 'required|exists:users,id',
            'broker_id' => 'nullable|exists:brokers,id'
        ]);
        $brokerId = Broker::where('user_id', $request->user_id)->value('id');
        $client = new Client();
        $client->client_name = $request->client_name;
        $client->contact_name = $request->contact_name;
        $client->address = $request->address;
        $client->office_number = $request->office_number;
        $client->cell_number = $request->cell_number;
        $client->email = $request->email;
        $client->space = $space;
        $client->user_id = $request->user_id;
        $client->broker_id = Auth::user()->broker ? Auth::user()->broker->id : null;
        $client->save();
        return response()->json([
            'message' => 'Client created successfully',
            'client' => $client
        ], 201);
    }

    public function edit($username, $id)
    {
        $client = Client::findOrFail($id);
        $usersquery = User::query();
        if (Auth::user()->role == 'broker') {
            $usersquery->where('created_by', Auth::user()->id);
        }
        $users = $usersquery->where('role', 'client')->get();
        return view('clients.update', compact('client', 'users'));
    }

    public function update(Request $request, $id)
    {
        $space = adminSetting::where('name', operator: 'database_space')->first()->value;
        $request->validate([
            'client_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'office_number' => 'nullable|string|max:20',
            'cell_number' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $client = Client::findOrFail($id);
        $client->client_name = $request->client_name;
        $client->contact_name = $request->contact_name;
        $client->address = $request->address;
        $client->office_number = $request->office_number;
        $client->cell_number = $request->cell_number;
        $client->email = $request->email;
        $client->user_id = $request->user_id;
        $client->space = $space;
        $client->save();
        return response()->json([
            'message' => 'Client updated successfully',
            'client' => $client
        ], 200);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        if ($client->documents()->count() > 0 || $client->assets()->count() > 0) {
            return response()->json([
                'message' => 'Client cannot be deleted because it has associated documents or assets.'
            ], 400);
        }
        $client->delete();
        return response()->json([
            'message' => 'Client deleted successfully'
        ], 200);
    }
    public function getclient($username, $clientId)
    {
        $client = Client::with('user')->findOrFail($clientId);
        $activities = Activity::where('user_id', $client->user_id)->orderBy("id",'DESC')->get();
        return view('clients.view', compact('client', 'activities'));
    }

    public function getClientDocuments($username, $id)
    {
        $documents = Document::with('client')->where('client_id', $id)->get();
        $clientName = Client::find($id);
        return view('clients.documents.index', compact('documents', 'clientName'));
    }

    public function createClientDocument($username, $id)
    {
        $client = Client::find($id);
        return view('clients.documents.create', compact('client'));
    }
    public function storeClientDocument(Request $request)
    {
        $request->validate([
            'document_file' => 'required|file',
            'name' => 'required|string',
            'client_id' => 'required|exists:clients,id',
        ]);
        // $client_id = $request->client_id;
        $document = new Document();
        $document->document_name = $request->name;
        $document->client_id = $request->client_id;
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('clients/documents/'), $filename);
            $document->file_path = "clients/documents/{$filename}";
        }
        $document->save();
        if (Auth::user()->role != 'client') {
            return response()->json([
                'success' => 'Document added successfully'
            ]);
        } else {
            $activity = Activity::create([
                'action' => $document->name .' document created',
                'user_id' => Auth::user()->client->id
            ]);
            return response()->json([
                'success' => 'Document added successfully'
            ]);
        }
    }

    public function editDocument($username, $id)
    {
        $document = Document::findOrFail($id);
        return view('clients.documents.update', compact('document'));
    }

    public function updateDocument(Request $request, $username, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $document = Document::findOrFail($id);
        $document->document_name = $request->name;

        if ($request->hasFile('document_file')) {
            if ($document->file_path) {
                unlink(public_path($document->file_path));
            }
            $file = $request->file('document_file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('clients/documents/'), $filename);
            $document->file_path = 'clients/documents/' . $filename;
        }

        $document->save();
        if (Auth::user()->role != 'client') {
            return response()->json(['message' => 'Document updated successfully', 'document' => $document], 200);
        } else {
            $activity = Activity::create([
                'action' => $document->name .' document updated',
                'user_id' => Auth::user()->client->id
            ]);
            return response()->json(['message' => 'Document updated successfully', 'document' => $document], 200);
        }
    }
    public function destory($id)
    {
        $document = Document::findOrFail($id);
        if ($document->file_path) {
            unlink(public_path($document->file_path));
        }
        $document->delete();
        if (Auth::user()->role != 'client') {
            return redirect()->back()->with('success', 'document deleted successfully');
        } else {
            $activity = Activity::create([
                'action' => $document->name .' document deleted',
                'user_id' => Auth::user()->client->id
            ]);
            return redirect()->back()->with('success', 'document deleted successfully');
        }
    }






    // client panel
    public function ClientProfile()
    {
        $id = Auth::user()->client->id;
        // $brokerName = Auth::user()->client->broker;
        // return $brokerName;
        $client = Client::with('user')->findOrFail($id);
        $activities = Activity::where('user_id', $client->id)->orderBy("id",'DESC')->get();
        return view('clients.view', compact('client', 'activities'));
    }

    public function ClientSideDocument()
    {
        $id = Auth::user()->client->id;
        $documents = Document::with('client')->where('client_id', $id)->get();
        $clientName = Client::find($id);
        return view('clients.documents.index', compact('documents', 'clientName'));
    }


    public function clientsideAssets()
    {
        $id = Auth::user()->client->id;
        $client = Client::find($id);
        $assets = $client->assets()->get();
        return view('assets.index', compact('client', 'assets'));
    }





}
