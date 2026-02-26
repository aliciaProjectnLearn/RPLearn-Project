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
            <div class="word-item" 
                data-term="{{ $word->term }}"
                data-desc="{{ $word->definition }}">
                <h4 class="word-click" style="color: var(--dark); font-size: 0.8rem; font-weight: 600; align-items: center; margin-top: 5px;">{{ $word->term }}</h4>
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

<!-- MODAL detail term -->
<div id="dictionaryModal" class="dict-modal">
    <div class="dict-box">
        <span class="dict-close">&times;</span>
        <h2 id="modalTerm"></h2>
        <p id="modalDesc" style="font-size: 0.8rem;"></p>
    </div>
</div>

</div>

<div id="noResultMessage"
     style="display:none; margin-top:10px; color:#e74c3c; font-weight:600;">
    Tidak ada istilah yang kamu cari di huruf {{ $letter ?? 'ini' }}. Coba klik huruf lain.
</div>



<style>
/* ===== Dictionary Layout ===== */

.dictionary-container{
    display:grid;
    grid-template-columns: 1fr 70px;
    gap:20px;
    align-items:start;
    padding: 0 20px;
}

.dictionary-content{
    position: relative;
}


#noResultMessage{
    position: absolute;
    top: 285px;
    left: 15rem;
    right: 0;

    background: var(--light);
    color: #e74c3c;
    padding: 10px 12px;
    border-radius: 8px;
    font-size: 14px;


    text-align: center;
    justify-content: center;

    animation: fadeIn .2s ease;

    width: max-content;
}

@keyframes fadeIn{
    from{opacity:0; transform:translateY(-5px);}
    to{opacity:1; transform:translateY(0);}
}



.dictionary-content h2{
    padding-top: 1.5rem;
    font-size: 1.7rem;
    margin-bottom:20px;
    text-align: center;
    font-weight: 700;
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
    padding: 12px 10px;

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
    font-size:10px;
}

.alphabet-sidebar a.active,
.alphabet-sidebar a:hover{
    background:linear-gradient(135deg,#F6973F,#D65A31);
}

/* card kata */
.word-item{
    background: var(--light);
    padding: 5px 10px;
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

.dict-modal{
    display:none;
    position:fixed;
    z-index:9999;
    left:0;
    top:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,.4);
    align-items:center;
    justify-content:center;
}

.dict-box{
    background:#fff;
    width:420px;
    padding:25px 30px;
    border-radius:20px;
    position:relative;
    box-shadow:0 10px 30px rgba(0,0,0,.2);
    animation:pop .2s ease;
}

.dict-box h2{
    color:var(--orange);
    margin-bottom:10px;
    font-size: 1rem;
}

.dict-close{
    position:absolute;
    top:15px;
    right:18px;
    font-size:22px;
    cursor:pointer;
}

.word-click{
    cursor:pointer;
}

@keyframes pop{
    from{transform:scale(.8);opacity:0;}
    to{transform:scale(1);opacity:1;}
}

</style>

{{-- ================= SCRIPT ================= --}}

   @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('dictionarySearch');
    const wordItems = document.querySelectorAll('.word-item');
    const noResult = document.getElementById('noResultMessage');

    if (!searchInput || !noResult) {
        console.log("Search atau message tidak ditemukan");
        return;
    }

    searchInput.addEventListener('input', function () {

        const keyword = this.value.trim().toLowerCase();
        let found = false;

        wordItems.forEach(item => {

            const term = item.querySelector('h4')?.textContent.toLowerCase() || '';
            const definition = item.querySelector('p')?.textContent.toLowerCase() || '';

            if (term.includes(keyword) || definition.includes(keyword)) {
                item.style.display = '';
                found = true;
            } else {
                item.style.display = 'none';
            }
        });

        if (!found && keyword.length > 0) {
            noResult.style.display = 'block';
        } else {
            noResult.style.display = 'none';
        }
    });

    const modal = document.getElementById("dictionaryModal");
const closeBtn = document.querySelector(".dict-close");
const modalTerm = document.getElementById("modalTerm");
const modalDesc = document.getElementById("modalDesc");

document.querySelectorAll(".word-item").forEach(item=>{
    item.addEventListener("click", function(){

        modalTerm.innerText = this.dataset.term;
        modalDesc.innerText = this.dataset.desc;

        modal.style.display = "flex";
    });
});

closeBtn.onclick = () => modal.style.display = "none";

window.onclick = e=>{
    if(e.target == modal){
        modal.style.display = "none";
    }
};

});
</script>
@endpush




@endsection