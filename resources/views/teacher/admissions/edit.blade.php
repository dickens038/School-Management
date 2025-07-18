@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Student Admission</h3>
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

                    <form action="{{ route('teacher.admissions.update', $student) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Personal Information</h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $student->user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $student->user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="admission_number" class="form-label">Admission Number *</label>
                                    <input type="text" class="form-control @error('admission_number') is-invalid @enderror" 
                                           id="admission_number" name="admission_number" value="{{ old('admission_number', $student->admission_number) }}" required>
                                    @error('admission_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth) }}" required>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender *</label>
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
                            </div>

                            <!-- Academic Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Academic Information</h5>
                                
                                <div class="mb-3">
                                    <label for="class_id" class="form-label">Class *</label>
                                    <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
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
                                        <option value="">Select Combination</option>
                                        @foreach($combinations as $code => $description)
                                            <option value="{{ $code }}" {{ old('combination', $student->combination) == $code ? 'selected' : '' }}>
                                                {{ $code }} - {{ $description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('combination')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="admission_notes" class="form-label">Admission Notes</label>
                                    <textarea class="form-control @error('admission_notes') is-invalid @enderror" 
                                              id="admission_notes" name="admission_notes" rows="4">{{ old('admission_notes', $student->admission_notes) }}</textarea>
                                    @error('admission_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Current Status -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">Current Status:</h6>
                                    <p class="mb-0">
                                        <strong>Admission Status:</strong> 
                                        @if($student->admission_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($student->admission_status == 'admitted')
                                            <span class="badge bg-success">Admitted</span>
                                        @elseif($student->admission_status == 'approved')
                                            <span class="badge bg-primary">Approved</span>
                                        @elseif($student->admission_status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </p>
                                    @if($student->admitted_at)
                                        <p class="mb-0"><strong>Admitted On:</strong> {{ $student->admitted_at->format('M d, Y H:i') }}</p>
                                    @endif
                                    @if($student->admittedBy)
                                        <p class="mb-0"><strong>Admitted By:</strong> {{ $student->admittedBy->name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('teacher.admissions.show', $student) }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update Student</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 