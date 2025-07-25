@extends('management.dashboard')

@section('pageTitle', 'Teams')
@section('content')
    <div class="container">
        {{-- Add, search --}}
        <div class="row align-items-center mb-5">
            <div class="col-auto">
                <form action="{{ route('team.add') }}" method="get">
                    <button class="btn bg-dark text-white shadow-sm rounded" type="submit">
                        <i class="fa fa-plus me-1"></i> Add
                    </button>
                </form>
            </div>
            <div class="col">
                <form class="d-flex align-items-center gap-2 w-100 shadow-sm rounded"
                      action="{{ route('team.search') }}" method="get">
                    <div class="input-group w-100">
                        <select class="form-select bg-dark text-white"
                                style="max-width: 120px;"
                                id="by"
                                name="by"
                                onchange="toggleSearch()">
                            @isset ($_GET['by'])
                                @if ($_GET['by'] === 'name')
                                    <option selected value="name">By Name</option>
                                    <option value="group">By Group</option>
                                @elseif ($_GET['by'] === 'group')
                                    <option value="name">By Name</option>
                                    <option selected value="group">By Group</option>
                                @endif
                            @else
                                <option value="name">By Name</option>
                                <option value="group">By Group</option>
                            @endisset
                        </select>
                        <div class="search-box flex-grow-1">
                            <input
                                type="text"
                                class="form-control rounded-0"
                                name="search"
                                placeholder="Team Black"
                                id="searchInput"
                            >
                        </div>
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mb-3">
            @yield('inner-content')
        </div>

    </div>
    <script>
        function toggleSearch() {
            const searchType = document.getElementById('by').value;
            const searchBox = document.querySelector('.search-box');

            if (searchType === 'group') {
                searchBox.innerHTML = `
                        <select class="form-select rounded-0" name="search" id="searchGroup">
                            <option value="active">Active Teams</option>
                            <option value="deactivated">Deactivated Teams</option>
                        </select>
                        `;
            } else {
                searchBox.innerHTML = `
            <input
                type="text"
                class="form-control rounded-0"
                name="search"
                placeholder="Team Black"
                id="searchInput"
            >
            `;
            }
        }
    </script>
@endsection
