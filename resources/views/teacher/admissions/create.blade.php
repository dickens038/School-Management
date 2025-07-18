@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Admit New Student (Form 1-6)</h3>
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

                    <form action="{{ route('teacher.admissions.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Personal Information</h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="admission_number" class="form-label">Admission Number *</label>
                                    <input type="text" class="form-control @error('admission_number') is-invalid @enderror" 
                                           id="admission_number" name="admission_number" value="{{ old('admission_number') }}" required>
                                    @error('admission_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender *</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Academic Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Academic Information</h5>
                                
                                <div class="mb-3">
                                    <label for="class_id" class="form-label">Class *</label>
                                    <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="combination" class="form-label">Subject Combination *</label>
                                    <select class="form-select @error('combination') is-invalid @enderror" id="combination" name="combination" required>
                                        <option value="">Select Class First</option>
                                    </select>
                                    @error('combination')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="admission_notes" class="form-label">Admission Notes</label>
                                    <textarea class="form-control @error('admission_notes') is-invalid @enderror" 
                                              id="admission_notes" name="admission_notes" rows="4">{{ old('admission_notes') }}</textarea>
                                    @error('admission_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Combination Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">Available Combinations:</h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Ordinary Level (Form 1-4):</h6>
                                            <ul>
                                                <li><strong>SCIENCE:</strong> Physics, Chemistry, Biology, Mathematics</li>
                                                <li><strong>ARTS:</strong> History, Geography, Literature, Kiswahili</li>
                                                <li><strong>COMMERCE:</strong> Commerce, Accounts, Economics, Mathematics</li>
                                                <li><strong>GENERAL:</strong> General Studies (No specific combination)</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Advanced Level (Form 5-6):</h6>
                                            <ul>
                                                <li><strong>CBG:</strong> Chemistry, Biology, Geography</li>
                                                <li><strong>PCM:</strong> Physics, Chemistry, Mathematics</li>
                                                <li><strong>PCB:</strong> Physics, Chemistry, Biology</li>
                                                <li><strong>PGM:</strong> Physics, Geography, Mathematics</li>
                                                <li><strong>HKL:</strong> History, Kiswahili, Literature</li>
                                                <li><strong>HGK:</strong> History, Geography, Kiswahili</li>
                                                <li><strong>HGE:</strong> History, Geography, Economics</li>
                                                <li><strong>ECA:</strong> Economics, Commerce, Accounts</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('teacher.admissions.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Admit Student</button>
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
    
    const combinations = {
        'Form 1': {
            'SCIENCE': 'Physics, Chemistry, Biology, Mathematics',
            'ARTS': 'History, Geography, Literature, Kiswahili',
            'COMMERCE': 'Commerce, Accounts, Economics, Mathematics',
            'GENERAL': 'General Studies (No specific combination)'
        },
        'Form 2': {
            'SCIENCE': 'Physics, Chemistry, Biology, Mathematics',
            'ARTS': 'History, Geography, Literature, Kiswahili',
            'COMMERCE': 'Commerce, Accounts, Economics, Mathematics',
            'GENERAL': 'General Studies (No specific combination)'
        },
        'Form 3': {
            'SCIENCE': 'Physics, Chemistry, Biology, Mathematics',
            'ARTS': 'History, Geography, Literature, Kiswahili',
            'COMMERCE': 'Commerce, Accounts, Economics, Mathematics',
            'GENERAL': 'General Studies (No specific combination)'
        },
        'Form 4': {
            'SCIENCE': 'Physics, Chemistry, Biology, Mathematics',
            'ARTS': 'History, Geography, Literature, Kiswahili',
            'COMMERCE': 'Commerce, Accounts, Economics, Mathematics',
            'GENERAL': 'General Studies (No specific combination)'
        },
        'Form 5': {
            'CBG': 'Chemistry, Biology, Geography',
            'PCM': 'Physics, Chemistry, Mathematics',
            'PCB': 'Physics, Chemistry, Biology',
            'PGM': 'Physics, Geography, Mathematics',
            'HKL': 'History, Kiswahili, Literature',
            'HGK': 'History, Geography, Kiswahili',
            'HGE': 'History, Geography, Economics',
            'ECA': 'Economics, Commerce, Accounts'
        },
        'Form 6': {
            'CBG': 'Chemistry, Biology, Geography',
            'PCM': 'Physics, Chemistry, Mathematics',
            'PCB': 'Physics, Chemistry, Biology',
            'PGM': 'Physics, Geography, Mathematics',
            'HKL': 'History, Kiswahili, Literature',
            'HGK': 'History, Geography, Kiswahili',
            'HGE': 'History, Geography, Economics',
            'ECA': 'Economics, Commerce, Accounts'
        }
    };

    function updateCombinations() {
        const selectedClass = classSelect.options[classSelect.selectedIndex].text;
        combinationSelect.innerHTML = '<option value="">Select Combination</option>';
        
        if (combinations[selectedClass]) {
            Object.keys(combinations[selectedClass]).forEach(key => {
                const option = document.createElement('option');
                option.value = key;
                option.textContent = `${key} - ${combinations[selectedClass][key]}`;
                combinationSelect.appendChild(option);
            });
        }
    }

    classSelect.addEventListener('change', updateCombinations);
});
</script>
@endsection 