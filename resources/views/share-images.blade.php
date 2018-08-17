@foreach($images as $image)
    <img src="{{ route('image', ['name' => $image->file_name]) }}" alt="">
@endforeach