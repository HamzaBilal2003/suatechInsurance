<div class='my-4 card shadow'>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Clients Add</h3>
    </div>
    <div class="card-body">
        <form id="addclientForm">
            @csrf
            <div class="form-group">
                <label for="client_name">Client Name</label>
                <input type="text" name="client_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="contact_name">Contact Name</label>
                <input type="text" name="contact_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="office_number">Office Number</label>
                <input type="text" name="office_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="cell_number">Cell Number</label>
                <input type="text" name="cell_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="user_id">Assign To</label>
                <select name="user_id" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#addclientForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('clients.store', Auth::user()->username) }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    Swal.fire({
                        title: 'Adding Broker...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Broker Added Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.reload();
                },
                error: function (response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Adding Broker',
                        text: response.responseJSON.message,
                    });
                }
            });
        });
    });
</script>