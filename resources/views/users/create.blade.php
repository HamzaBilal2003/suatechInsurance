<div class='my-4 card shadow'>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">User Add</h3>
    </div>
    <div class="card-body">
        <form id="addclientForm">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" class="form-control" required>
                    <option value="">Select Role</option>
                    @if (Auth::user()->role != 'broker')
                        <option value="admin">Admin</option>
                        <option value="broker">Broker</option>
                    @endif
                    <option value="client">Client</option>
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
                url: "{{ route('users.store', Auth::user()->username) }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    Swal.fire({
                        title: 'Adding User...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'User Added Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.reload();
                },
                error: function (response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Adding user',
                        text: response.responseJSON.message,
                    });
                }
            });
        });
    });
</script>