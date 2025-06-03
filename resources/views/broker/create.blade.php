<div class="card shadow">
    <div class="card-header">
        <h3 class="card-title text-center">Add Broker</h3>
    </div>
    <form id="addBrokerForm" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="logo">Broker Logo</label>
                <input type="file" name="logo" id="logo" class="form-control-file" accept="image/*">
            </div>
            <div class="form-group">
                <label for="name">Broker Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">Broker Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="address">Broker Address</label>
                <textarea name="address" id="address" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="user_id">Assign To</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add Broker</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#addBrokerForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('broker.store', Auth::user()->username) }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
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