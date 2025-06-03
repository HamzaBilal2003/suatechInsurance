<div class="card shadow">
    <div class="card-header">
        <h3 class="card-title text-center">Add Documents</h3>
    </div>
    <form id="addBrokerForm" method="POST">
        @csrf
        <div class="card-body">
            <input type="hidden" name="client_id" value="{{$client->id}}">
            <div class="form-group">
                <label for="name">Documents Name</label>
                <input type="text" name="name" id="name" class="form-control">
            </div>
            <div class="form-group">
                <label for="document_file">Documents</label>
                <input type="file" name="document_file" id="document_file" class="form-control-file">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add Documents</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#addBrokerForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "/{{Auth::user()->username}}/clients/view/documents/store/{client->id}",
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