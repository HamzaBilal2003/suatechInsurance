<div class='my-4 card shadow'>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Edit User</h3>
        @include('layout.backbtn')
    </div>
    <div class="card-body">
        <form id="editUserForm">
            @csrf
            {{-- <input type="hidden" name="_method" value="PUT"> --}}
            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}"
                    required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control">
                <small class="text-muted">Leave blank to keep the current password.</small>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="">Select Role</option>
                    @if (Auth::user()->role != 'broker')
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="broker" {{ $user->role == 'broker' ? 'selected' : '' }}>Broker</option>
                    @endif
                    <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Client</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#editUserForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            let userId = $('#user_id').val();

            $.ajax({
                url: `/{{ Auth::user()->username }}/users/update/${userId}`,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    Swal.fire({
                        title: 'Updating User...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'User Updated Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.href = "{{ route('users.index', Auth::user()->username) }}";
                },
                error: function (response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Updating User',
                        text: response.responseJSON.message,
                    });
                }
            });
        });
    });
</script>