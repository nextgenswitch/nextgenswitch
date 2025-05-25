@extends('layouts.app')

@section('title', __('Profile') )

@section('content')

<div class="row">
    <div class="col-12">
        
        @include('partials.message')

        @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
        @endif

        <form action="{{ route('user.profile.update') }}" method="post">
            
            @csrf
            @method('put')

            <div class="card">
                <div class="card-header">
                    <b>Personal Information</b>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="user[name]" class="form-control" placeholder="John Doe" value="{{ old('user.name', optional($user)->name) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="text" name="user[email]" class="form-control" placeholder="John Doe" value="{{ old('user.email', optional($user)->email) }}">
                    </div>
                </div>

                <div class="card-header">
                    <b>Organization Information</b>
                </div>

                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" readonly class="form-control" value="{{ optional($user)->name }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="domain">Domain</label>
                        <input type="text" readonly name="organization[domain]" class="form-control" placeholder="example.com" value="{{ old('organization.domain', optional($user)->organization->domain) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="text" readonly name="organization[email]" class="form-control" placeholder="John Doe" value="{{ old('organization.email', optional($user)->organization->email) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="contact_no">Contact Number</label>
                        <input type="text" readonly name="organization[contact_no]" class="form-control" placeholder="88015xx-xxxxxx" value="{{ old('organization.contact_no', optional($user)->organization->contact_no) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <textarea  name="organization[address]" readonly class="form-control" placeholder="House # 38 Road-1, Sector-5, Dhaka 1230" rows="2">{{ old('organization.address', optional($user)->organization->address) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>


            </div>
        </form>
    </div>
</div>

@endsection


