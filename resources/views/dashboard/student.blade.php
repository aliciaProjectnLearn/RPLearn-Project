@extends('layouts.app')

@section('content')

{{-- Flash success popup --}}
    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif 
<section class="hero">
    <h1>Start <span>Learning.</span> Keep Growing.</h1>
    <p>RPLearn is designed to support vocational students <br>in developing real-world skills through structured and guided learning.
    </p>

    <div class="features-card">
        <div class="feat-card">
            <i class="ri-book-open-line"></i>
            <span>Learning Modules</span>
        </div>

        <div class="feat-card">
            <i class="ri-bookmark-line"></i>
            <span>Dictionary</span>
        </div>

        <div class="feat-card">
            <i class="ri-question-line"></i>
            <span>FAQ</span>
        </div>
    </div>
</section>

<section class="module-section reveal" id="module-section">
    <h1>Cari <span>Modul</span> Belajarmu!</h1>

    <div class="module-search">
    <i class="ri-search-line"></i>
    <input 
        type="text" 
        placeholder="Search modules..."
    >
</div>

    
    <div class="module-cards">
        <div class="module-card">Modul 1</div>
        <div class="module-card">Modul 2</div>
        <div class="module-card">Modul 3</div>
    </div>
</section>

<section class="faq-section reveal" id="faq-section">
    <h2>F <span>A</span> Q</h2>
    <div class="faq-box">Apa itu RPLearn?</div>
    <div class="faq-box">Bagaimana cara belajar?</div>
</section>

<section class="dictionary-section reveal">
    <h2>Dictionary</h2>
    <p class="dictionary-desc">
        Learn common technical terms used in vocational learning.
    </p>

    <div class="dictionary-search">
        <i class="ri-search-line"></i>
        <input type="text" placeholder="Search terms..." />
    </div>

    <div class="dictionary-cards reveal" id="dictionary-cards">
        <div class="dictionary-card">
            <h4>HTML</h4>
            <p>
                HyperText Markup Language used to structure web content.
            </p>
        </div>

        <div class="dictionary-card">
            <h4>CSS</h4>
            <p>
                Cascading Style Sheets used to style and layout web pages.
            </p>
        </div>

        <div class="dictionary-card">
            <h4>JavaScript</h4>
            <p>
                A programming language that adds interactivity to websites.
            </p>
        </div>
    </div>

    <div class="dictionary-more">
        <a href="#">View Full Dictionary</a>
    </div>
</section>


@endsection
