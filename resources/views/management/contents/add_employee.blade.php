@extends('management.layouts.employee')

@section('inner-content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-4">Create New Employee</h5>

            <form id="createForm" action="{{ route('employee.add_confirm') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Team --}}
                <div class="mb-3">
                    <label for="team" class="form-label">Team</label>
                    <select name="team_id" id="team" class="form-select @error('team') is-invalid @enderror" required>
                        <option value="">-- Select Team --</option>
                        @foreach ($teamName as $team)
                            <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('team')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="abc@gmail.com"
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- First Name --}}
                <div class="mb-3">
                    <label for="first-name" class="form-label">First Name</label>
                    <input type="text"
                           name="first_name"
                           id="first-name"
                           class="form-control @error('first_name') is-invalid @enderror"
                           placeholder="John"
                           value="{{ old('first_name') }}"
                           required>
                    @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Last Name --}}
                <div class="mb-3">
                    <label for="last-name" class="form-label">Last Name</label>
                    <input type="text"
                           name="last_name"
                           id="last-name"
                           class="form-control @error('last_name') is-invalid @enderror"
                           placeholder="Doe"
                           value="{{ old('last_name') }}"
                           required>
                    @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Gender --}}
                <div class="mb-3">
                    <label class="form-label d-block">Gender</label>
                    <div class="form-check form-check-inline">
                        <input type="radio"
                               name="gender"
                               id="male"
                               value="1"
                               class="form-check-input @error('gender') is-invalid @enderror"
                               {{ old('gender', '1') == '1' ? 'checked' : '' }}
                               required>
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio"
                               name="gender"
                               id="female"
                               value="2"
                               class="form-check-input @error('gender') is-invalid @enderror"
                               {{ old('gender') == '2' ? 'checked' : '' }}
                               required>
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                    @error('gender')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Birthday --}}
                <div class="mb-3">
                    <label for="birthday" class="form-label">Birthday</label>
                    <input type="date"
                           name="birthday"
                           id="birthday"
                           class="form-control @error('birthday') is-invalid @enderror"
                           value="{{ old('birthday') }}"
                           required>
                    @error('birthday')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text"
                           name="address"
                           id="address"
                           class="form-control @error('address') is-invalid @enderror"
                           value="{{ old('address') }}"
                           required>
                    @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Avatar --}}
                <div class="mb-3">
                    <label for="avatar" class="form-label">Avatar</label>
                    <input type="file"
                           name="avatar"
                           id="avatar"
                           class="form-control @error('avatar') is-invalid @enderror"
                           required>
                    @error('avatar')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Salary --}}
                <div class="mb-3">
                    <label for="salary" class="form-label">Salary</label>
                    <input type="text"
                           name="salary"
                           id="salary"
                           class="form-control @error('salary') is-invalid @enderror"
                           placeholder="10000"
                           value="{{ old('salary') }}"
                           required>
                    @error('salary')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Position --}}
                <div class="mb-3">
                    <label for="position" class="form-label">Position</label>
                    <select name="position"
                            id="position"
                            class="form-select @error('position') is-invalid @enderror"
                            required>
                        <option disabled selected value="">--Select--</option>
                        <option value="1" {{ old('position') == '1' ? 'selected' : '' }}>Manager</option>
                        <option value="2" {{ old('position') == '2' ? 'selected' : '' }}>Team Leader</option>
                        <option value="3" {{ old('position') == '3' ? 'selected' : '' }}>BSE</option>
                        <option value="4" {{ old('position') == '4' ? 'selected' : '' }}>Dev</option>
                        <option value="5" {{ old('position') == '5' ? 'selected' : '' }}>Intern</option>
                    </select>
                    @error('position')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>On Working</option>
                        <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Retired</option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Work Type --}}
                <div class="mb-3">
                    <label for="work_type" class="form-label">Work Type</label>
                    <select name="type_of_work"
                            id="work_type"
                            class="form-select @error('type_of_work') is-invalid @enderror"
                            required>
                        <option value="1" {{ old('type_of_work', '1') == '1' ? 'selected' : '' }}>Full-time</option>
                        <option value="2" {{ old('type_of_work') == '2' ? 'selected' : '' }}>Part-time</option>
                        <option value="3" {{ old('type_of_work') == '3' ? 'selected' : '' }}>Probationary Staff</option>
                        <option value="4" {{ old('type_of_work') == '4' ? 'selected' : '' }}>Intern</option>
                    </select>
                    @error('type_of_work')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 text-end">
                    <button type="button" class="btn btn-outline-dark" onclick="validateAndShowModal()">
                        Create
                    </button>
                </div>
            </form>
        </div>

        {{-- Confirmation Modal --}}
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">Are you sure you want to create this employee?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                        <button type="button" class="btn btn-dark" onclick="document.getElementById('createForm').submit();">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Validation Script --}}
    <script>
        function validateAndShowModal() {
            const form = document.getElementById('createForm');
            const inputs = form.querySelectorAll('input, select, textarea');
            let isValid = true;
            let firstInvalid = null;
            const grouped = new Set();

            inputs.forEach(input => {
                const name = input.name;
                const required = input.hasAttribute('required');
                const type = input.type;

                if (type === 'radio') {
                    if (grouped.has(name)) return;
                    grouped.add(name);
                    const checked = form.querySelector(`input[name="${name}"]:checked`);
                    if (!checked) {
                        isValid = false;
                        if (!firstInvalid) firstInvalid = input;
                        input.classList.add('is-invalid');
                    }
                } else {
                    if (required && input.value.trim() === '') {
                        isValid = false;
                        if (!firstInvalid) firstInvalid = input;
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                    }
                }
            });

            if (!isValid) {
                if (firstInvalid) firstInvalid.focus();
                return;
            }

            new bootstrap.Modal(document.getElementById('confirmModal')).show();
        }

        document.getElementById('createForm').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                validateAndShowModal();
            }
        });
    </script>
@endsection
