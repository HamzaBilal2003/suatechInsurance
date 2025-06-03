<div class="card shadow">
    <div class="card-header">
        <h3 class="card-title text-center">Edit Broker</h3>
    </div>
    <form id="editBrokerForm" method="POST">
        @csrf
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="broker_id" id="broker_id" value="{{ $broker->id }}">
        <div class="card-body">
            <div class="form-group">
                <label for="logo">Broker Logo</label>
                <input type="file" name="logo" id="logo" class="form-control-file" accept="image/*">
                @if ($broker->logo_path)
                    <img src="{{ asset($broker->logo_path) }}" alt="Broker Logo" style="width: 100px; height: auto; margin-top: 10px;">
                @endif
            </div>
            <div class="form-group">
                <label for="name">Broker Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $broker->broker_name }}" required>
            </div>
            <div class="form-group">
                <label for="phone">Broker Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ $broker->contact_person }}" required>
            </div>
            <div class="form-group">
                <label for="address">Broker Address</label>
                <textarea name="address" id="address" class="form-control">{{ $broker->address }}</textarea>
            </div>
            <div class="form-group">
                <label for="user_id">Assign To</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $broker->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update Broker</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#editBrokerForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            let brokerId = $('#broker_id').val();

            $.ajax({
                url: `/{{Auth::user()->username}}/brokers/update/${brokerId}`,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Broker Updated Successfully',
                        // showConfirmButton: false,
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function (response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Updating Broker',
                        text: response.responseJSON.message,
                    });
                }
            });
        });
    });
</script>