@extends('management.dashboard')

@section('pageTitle', 'Employees')
@section('content')
    <div class="container">
        {{-- Add, search --}}
        <div class="row align-items-center mb-5">
            <div class="col-auto">
                <form action="{{ route('employee.add') }}" method="get">
                    <button class="btn bg-dark text-white shadow-sm rounded" type="submit">
                        <i class="fa fa-plus me-1"></i> Add
                    </button>
                </form>
            </div>
            <div class="col">
                <form class="d-flex align-items-center gap-2 w-100 shadow-sm rounded"
                      action="{{ route('employee.search') }}" method="get">
                    <div class="input-group w-100">
                        <select class="form-select bg-dark text-white"
                                style="max-width: 120px;"
                                id="by"
                                name="by"
                                onchange="toggleSearch()">
                            @isset ($_GET['by'])
                                @if ($_GET['by'] === 'name')
                                    <option selected value="name">By Name</option>
                                    <option value="email">By Email</option>
                                    <option value="team">By Team</option>
                                @elseif ($_GET['by'] === 'email')
                                    <option value="name">By Name</option>
                                    <option selected value="email">By Email</option>
                                    <option value="team">By Team</option>
                                @elseif ($_GET['by'] === 'team')
                                    <option value="name">By Name</option>
                                    <option value="email">By Email</option>
                                    <option selected value="team">By Team</option>
                                @endif
                            @else
                                <option value="name">By Name</option>
                                <option value="email">By Email</option>
                                <option value="team">By Team</option>
                            @endisset
                        </select>
                        <div class="search-box flex-grow-1">
                            <input
                                type="text"
                                class="form-control rounded-0"
                                name="search"
                                placeholder="John Doe"
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
            const teams = @json($teamName);

            if (searchType === 'team') {
                const select = document.createElement('select');
                select.className = "form-select rounded-0";
                select.name = "search";
                select.id = "searchGroup";

                teams.forEach(team => {
                    const option = document.createElement('option');
                    option.value = team.id;
                    option.textContent = team.name;
                    select.appendChild(option);
                });

                searchBox.innerHTML = '';
                searchBox.appendChild(select);

            } else {
                const placeholder = (searchType === 'email') ? "abc@gmail.com" : "John Doe";
                searchBox.innerHTML = `
                    <input
                        type="text"
                        class="form-control rounded-0"
                        name="search"
                        placeholder="${placeholder}"
                        id="searchInput"
                    >
                `;
            }
        }
    </script>
@endsection
