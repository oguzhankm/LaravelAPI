
<h1>{{$name}}</h1>
Product ID: {{$id}}, Type: {{$type}}

<div>
    @if($id == 1)
        1 No'lu ürün gösterilmektedir.
    @elseif($id == 2)
        2 no'lu ürün gösterilmektedir.
    @else
        Diğer ürün gösterilmektedir.
    @endif
</div>

<div>
    @for($i = 0; $i<10; $i++)
       Döngü değeri {{$i}}<br>
    @endfor
</div>
<div>
    @foreach($categories as $categorie)
        {{ $categorie }}<br>
    @endforeach
</div>
