@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Edit Student Admission</h3>
                    <a href="{{ route('admin.admissions.show', $student) }}" class="btn btn-secondary">Back to Details</a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.admissions.update', $student) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Student Information</h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $student->user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $student->user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="admission_number" class="form-label">Admission Number</label>
                                    <input type="text" class="form-control @error('admission_number') is-invalid @enderror" 
                                           id="admission_number" name="admission_number" value="{{ old('admission_number', $student->admission_number) }}" required>
                                    @error('admission_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth) }}" required>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>Academic Information</h5>
                                
                                <div class="mb-3">
                                    <label for="class_id" class="form-label">Class</label>
                                    <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        <optgroup label="Ordinary Level">
                                            @foreach($classes->whereIn('name', ['Form 1', 'Form 2', 'Form 3', 'Form 4']) as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                                    {{ $class->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Advanced Level">
                                            @foreach($classes->whereIn('name', ['Form 5', 'Form 6']) as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                                    {{ $class->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="combination" class="form-label">Combination</label>
                                    <select class="form-select @error('combination') is-invalid @enderror" id="combination" name="combination" required>
                                        <option value="">Select Combination</option>
                                    </select>
                                    @error('combination')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="admission_notes" class="form-label">Admission Notes</label>
                                    <textarea class="form-control @error('admission_notes') is-invalid @enderror" 
                                              id="admission_notes" name="admission_notes" rows="3">{{ old('admission_notes', $student->admission_notes) }}</textarea>
                                    @error('admission_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.admissions.show', $student) }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update Admission</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('class_id');
    const combinationSelect = document.getElementById('combination');
    
    const combinations = @json($combinations);
    
    function updateCombinations() {
        const selectedClass = classSelect.value;
        combinationSelect.innerHTML = '<option value="">Select Combination</option>';
        
        if (selectedClass) {
            const classOption = classSelect.options[classSelect.selectedIndex];
            const className = classOption.text.trim();
            
            if (combinations[className]) {
                combinations[className].forEach(combination => {
                    const option = document.createElement('option');
                    option.value = combination;
                    option.textContent = combination;
                    if (combination === '{{ $student->combination }}') {
                        option.selected = true;
                    }
                    combinationSelect.appendChild(option);
                });
            }
        }
    }
    
    classSelect.addEventListener('change', updateCombinations);
    updateCombinations(); // Initialize on page load
});
</script>
@endsection 