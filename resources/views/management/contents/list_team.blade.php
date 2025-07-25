@extends('management.layouts.team')

@section('inner-content')
    @if (session('notification'))
        <div class="alert alert-dark text-center mb-3">
            {{ session('notification') }}
        </div>
    @endif

    @isset ($result)
        <div class="text-muted small fst-italic mb-3">
            {{ $result }}
        </div>
    @endisset

    {{-- Team Table --}}
    <div class="table-responsive rounded">
        <table class="table table-bordered table-hover align-middle w-100 shadow-sm rounded z-10">
            <thead class="table-dark">
            <tr>
                <th class="text-center">ID</th>
                <th class="text-start">
                    <div class="d-flex justify-content-between position-relative gap-2">
                        <span class="start-0">Name</span>
                        <form action="{{ route('team.index') }}" method="get" class="m-0">
                            <select name="sort" class="position-absolute end-0 me-1" onchange="this.form.submit()" style="width: auto;">
                                <option value="" selected>--</option>
                                <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>A-Z</option>
                                <option value="desc" {{ request('sort') === 'desc' ? 'selected' : '' }}>Z-A</option>
                            </select>
                        </form>
                    </div>
                </th>
                <th class="text-center">Created By</th>
                <th class="text-center">Last Update</th>
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($teams as $team)
                <tr>
                    <td class="text-center">{{ $team->id }}</td>
                    <td class="text-start">{{ $team->name }}</td>
                    <td class="text-center">{{ $team->creator->first_name . " " . $team->creator->last_name }}</td>
                    <td class="text-center">{{ $team->upd_datetime ? $team->upd_datetime->format('Y-m-d H:i') : '-' }}</td>
                    <td class="text-center">
                        @if ($team->del_flag == 0)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Deactivated</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                        @if ($team->del_flag == 0)

                            <a href="{{ route('team.edit', $team->id) }}"
                               class="btn btn-sm btn-outline-dark">
                                Edit
                            </a>
                            <form id="confirm-delete"
                                  action="{{ route('team.delete', $team->id) }}"
                                  method="get"
                            >
                                <button type="button"
                                        onclick="confirmSingleAction(this)"
                                        value="delete"
                                        class="btn btn-sm btn-outline-danger">
                                    Delete
                                </button>
                            </form>
                        @else
                            <form id="confirm-recover"
                                  action="{{ route('team.recover', $team->id) }}"
                                  method="get"
                            >
                                <button type="button"
                                        onclick="confirmSingleAction(this)"
                                        value="recover"
                                        class="btn btn-sm btn-outline-warning">
                                    Recover
                                </button>
                            </form>
                        @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div id="confirm-question" class="modal-body">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                        <button id="confirm-button" type="submit" class="btn btn-dark">
                            Confirm
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="pagination-wrapper d-flex flex-column align-items-center justify-content-center my-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            @if (!$teams->onFirstPage())
                <a href="{{ $teams->url(1) }}"
                   class="page-link text-decorator-none text-dark font-weight-bold"
                >
                    |<
                </a>
                <a href="{{ $teams->previousPageUrl() }}"
                   class="page-link text-decorator-none text-dark font-weight-bold"
                >
                    <
                </a>
            @else
                <a class="page-link text-decorator-none text-dark font-weight-bold">|<</a>
                <a class="page-link text-decorator-none text-dark font-weight-bold"><</a>
            @endif

            @for ($i = 1; $i <= $teams->lastPage(); $i++)
                @if ($i == $teams->currentPage())
                    <a href="{{ $teams->url($i) }}" class="btn btn-dark">{{ $i }}</a>
                @else
                    @if ($i == $teams->currentPage() - 1)
                        <a href="{{ $teams->url($i) }}" class="btn btn-outline-dark">{{ $i }}</a>
                    @elseif ($i == $teams->currentPage() + 1)
                        <a href="{{ $teams->url($i) }}" class="btn btn-outline-dark">{{ $i }}</a>
                    @endif
                @endif
            @endfor

            @if (!$teams->onLastPage())
                <a href="{{ $teams->nextPageUrl() }}"
                   class="page-link text-decorator-none text-dark font-weight-bold"
                >
                    >
                </a>
                <a href="{{ $teams->url($teams->lastPage()) }}"
                   class="page-link text-decorator-none text-dark font-weight-bold"
                >
                    >|
                </a>
            @else
                <a class="page-link text-decorator-none text-dark font-weight-bold">></a>
                <a class="page-link text-decorator-none text-dark font-weight-bold">>|</a>
            @endif
        </div>

        <div class="text-muted small text-center">
            {{ $teams->firstItem() }}–{{ $teams->lastItem() }} of {{ $teams->total() }} items
        </div>
    </div>

    <script>
        function confirmSingleAction(selectedBtn) {
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmModal.show();
            const questionBox = document.getElementById('confirm-question');
            const redirect = document.getElementById('confirm-button');
            const action = selectedBtn.value === 'delete' ? 'delete' : 'recover';
            const form = selectedBtn.value === 'delete' ? "confirm-delete" : "confirm-recover";
            questionBox.innerHTML = `Are you sure you want to ${action} this team?`;
            redirect.setAttribute('form', form);
        }
    </script>
@endsection



