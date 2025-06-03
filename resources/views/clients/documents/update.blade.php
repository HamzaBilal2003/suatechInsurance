<div class="card shadow">
    <div class="card-header">
        <h3 class="card-title text-center">Edit Document</h3>
    </div>
    <form id="updateDocumentForm" method="POST">
        @csrf
        {{-- <input type="hidden" name="_method" value="PUT"> --}}
        <input type="hidden" name="document_id" id="document_id" value="{{ $document->id }}">
        <input type="hidden" name="client_id" value="{{ $document->client_id }}">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Document Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $document->document_name }}" required>
            </div>
            <div class="form-group">
                <label for="document_file">Document File</label>
                <input type="file" name="document_file" id="document_file" class="form-control-file">
                @if ($document->file_path)
                    <a href="{{ asset($document->file_path) }}" target="_blank" class="btn btn-link mt-2">View Current Document</a>
                @endif
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update Document</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#updateDocumentForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            let documentId = $('#document_id').val();

            $('#updateDocumentForm button[type="submit"]').prop('disabled', true).text('Processing...');

            $.ajax({
                url: `/{{ Auth::user()->username }}/clients/view/documents/update/${documentId}`,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    Swal.fire({
                        title: 'Updating Document...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Document Updated Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.reload();
                },
                error: function (response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Updating Document',
                        text: response.responseJSON.message,
                    });
                },
                complete: function () {
                    $('#updateDocumentForm button[type="submit"]').prop('disabled', false).text('Update Document');
                }
            });
        });
    });
</script>