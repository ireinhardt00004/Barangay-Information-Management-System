@extends('layouts.appres')

@section('content')
<div id="newsfeed-header" class="newsfeed-header">
    <h3>News and announcements</h3>
</div>
@foreach($announcements as $announcement)
<div class="tulongPost">
    <div class="tulongtext">
        <div class="tulongpara example">
            <h1>{{ $announcement->decoded_title }}</h1>
            <h6>{{ \Carbon\Carbon::parse($announcement->created_at)->format('F d, Y h:i A') }}</h6>
            <p>{{ $announcement->decoded_content }}</p>
        </div>        
        <div class="tulongButton">
            <div class="tulongButton">
                @livewire('heart-react', ['announcementId' => $announcement->id])
                @livewire('comment-section', ['announcementId' => $announcement->id])
                {{-- <button>
                    <svg width="800px" height="800px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M13.47 4.13998C12.74 4.35998 12.28 5.96 12.09 7.91C6.77997 7.91 2 13.4802 2 20.0802C4.19 14.0802 8.99995 12.45 12.14 12.45C12.34 14.21 12.79 15.6202 13.47 15.8202C15.57 16.4302 22 12.4401 22 9.98006C22 7.52006 15.57 3.52998 13.47 4.13998Z"
                                stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                    </svg>
                </button> --}}
            </div>
        </div>
    </div>
    <div class="tulongimgs" title="Click to enlarge">
        <img src="{{ asset('announcement_img/' . $announcement->decoded_cover) }}" alt="" data-image="{{ asset('announcement_img/' . $announcement->decoded_cover) }}" class="enlarge-image">
    </div>
</div>
@endforeach
<style>
 .newsfeed-header {
    /*position: sticky;*/
    top: 15px;
    width: 100%;
    background-color: white; 
    z-index: 1000; 
    padding: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
} 
.sweetalert2-container {
    padding: 0;
}
.enlarge-image {
    cursor: pointer;
}
</style>

<script>
   document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.enlarge-image').forEach(image => {
            image.addEventListener('click', function() {
                const imageUrl = this.getAttribute('data-image');

                Swal.fire({
                    imageUrl: imageUrl,
                    imageWidth: 800,  
                    imageHeight: 600, 
                    imageAlt: 'Image',
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: '80%',
                    height: '80%',
                    customClass: {
                        container: 'sweetalert2-container'
                    }
                });
            });
        });
    });
</script>

@endsection
