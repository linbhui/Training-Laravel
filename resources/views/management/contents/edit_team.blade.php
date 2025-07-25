@extends('management.layouts.team')

@section('inner-content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-4">Edit</h5>
            <form id="createForm" action="{{ route('team.edit_confirm') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        placeholder="Team Black"
                        value="{{ $teamName }}"
                    >
                    <input type="hidden" name="id" value="{{ $teamId }}">
                </div>
                <div id="error-message" class="mb-3 danger text-danger">
                    @error('name')
                        <small>{{ $message }}</small>
                    @enderror
                </div>
                <button type="button" class="btn btn-outline-dark" onclick="showModal()">
                    Save
                </button>
            </form>
        </div>

        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to save this?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                        <button type="button" class="btn btn-dark" onclick="document.getElementById('createForm').submit();">
                            Confirm
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        function showModal() {
            new bootstrap.Modal(document.getElementById('confirmModal')).show();
        }

        document.getElementById('createForm').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                showModal();
            }
        });
    </script>
@endsection

