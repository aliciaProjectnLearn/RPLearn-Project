@extends('layouts.app')

@section('content')
<section class="profile-dashboard">

    <div class="profile-grid">

        <!-- LEFT : ACCOUNT INFO -->
        <div class="profile-box">
            <h3>Account Information</h3>

            <div class="form-group">
                <label>Username</label>
                <input type="text" value="{{ auth()->user()->username }}" readonly>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" value="************" readonly>
            </div>

            <div class="form-group">
                <label>Joined At</label>
                <input type="text" value="{{ auth()->user()->created_at->format('d M Y') }}" readonly>
            </div>
        </div>

        <!-- RIGHT : USER QUESTIONS -->
        <div class="profile-box">
            <h3>Your Questions</h3>

            <div class="question-list">

                <!-- dummy -->
                <div class="question-item">
                    <h4>What is MVC in Laravel?</h4>
                    <span>Asked on 12 Jan 2026</span>
                </div>

                <div class="question-item">
                    <h4>How to use migration properly?</h4>
                    <span>Asked on 15 Jan 2026</span>
                </div>

                <div class="question-item">
                    <h4>Difference between GET and POST?</h4>
                    <span>Asked on 18 Jan 2026</span>
                </div>

            </div>
        </div>

    </div>

</section>
@endsection
