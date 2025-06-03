<div class='my-4 card shadow'>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Edit Client</h3>
    </div>
    <div class="card-body">
        <form id="updateClientForm">
            @csrf
            <input type="hidden" name="client_id" id="client_id" value="{{ $client->id }}">
            <div class="form-group">
                <label for="client_name">Client Name</label>
                <input type="text" name="client_name" class="form-control" value="{{ $client->client_name }}" required>
            </div>
            <div class="form-group">
                <label for="contact_name">Contact Name</label>
                <input type="text" name="contact_name" class="form-control" value="{{ $client->contact_name }}" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" value="{{ $client->address }}" required>
            </div>
            <div class="form-group">
                <label for="office_number">Office Number</label>
                <input type="text" name="office_number" class="form-control" value="{{ $client->office_number }}" required>
            </div>
            <div class="form-group">
                <label for="cell_number">Cell Number</label>
                <input type="text" name="cell_number" class="form-control" value="{{ $client->cell_number }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $client->email }}" required>
            </div>
            <div class="form-group">
                <label for="user_id">Assign To</label>
                <select name="user_id" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $client->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#updateClientForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            let clientId = $('#client_id').val();

            $.ajax({
                url: `/clients/${clientId}/update`,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    Swal.fire({
                        title: 'Updating Client...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Client Updated Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.href = "{{ route('clients.index', Auth::user()->username) }}";
                },
                error: function (response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Updating Client',
                        text: response.responseJSON.message,
                    });
                }
            });
        });
    });
</script>