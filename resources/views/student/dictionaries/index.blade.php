@extends('layouts.app')

@section('content')

<div class="dictionary-container">

    <!-- CONTENT -->
    

    <div class="dictionary-content">

        <h2>Temukan <span>Istilah</span> Yang Ingin Kamu Tau</h2>
        <div class="dictionary-search">
            <i class="ri-search-line"></i>
            <input type="text" id="dictionarySearch" name="dictionary_search" placeholder="Search terms..." />
        </div>

        @forelse($words as $word)
            <div class="word-item">
                <h4 style="color: var(--orange); margin-bottom: 10px;">{{ $word->term }}</h4>
                <p>{{ Str::limit($word->definition,120) }}</p>
            </div>
        @empty
            <p>No words found.</p>
        @endforelse

        <div class="mt-3">
            {{ $words->links() }}
        </div>
    </div>


    <!-- SIDEBAR HURUF -->
    <div class="alphabet-sidebar">
        @foreach(range('A','Z') as $char)
            <a href="{{ route('student.dictionary.index',['letter'=>$char]) }}"
               class="{{ $letter==$char ? 'active':'' }}">
               {{ $char }}
            </a>
        @endforeach
    </div>

</div>


<style>
/* ===== Dictionary Layout ===== */

.dictionary-container{
    display:grid;
    grid-template-columns: 1fr 70px;
    gap:20px;
    align-items:start;
}

/* content */
.dictionary-content{
    min-width:0;
}

.dictionary-content h2{
    padding-top: 1.5rem;
    font-size: 2rem;
    margin-bottom:20px;
    text-align: center;
}   

.dictionary-content h2 span{
    color: var(--orange);
}

/* alphabet sidebar kanan */
.alphabet-sidebar{
    position:sticky;
    top:100px;
    height:max-content;

    background:#1f2630;
    border-radius:14px;
    padding:12px 8px;

    display:flex;
    flex-direction:column;
    gap:6px;
}

.alphabet-sidebar a{
    text-align:center;
    padding:6px;
    color:#fff;
    text-decoration:none;
    border-radius:6px;
    font-size:13px;
}

.alphabet-sidebar a.active,
.alphabet-sidebar a:hover{
    background:linear-gradient(135deg,#F6973F,#D65A31);
}

/* card kata */
.word-item{
    background: var(--light);
    padding:14px;
    border-radius:12px;
    margin-bottom:12px;
}

/* ===== Responsive ===== */
@media(max-width:768px){
    .dictionary-container{
        grid-template-columns:1fr;
    }

    .alphabet-sidebar{
        flex-direction:row;
        flex-wrap:wrap;
        justify-content:center;
    }
}

</style>
@endsection