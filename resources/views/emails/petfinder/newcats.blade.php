@component('mail::message')

# New Cats Are Available

@foreach($animals as $animal)
<p>
  <a href="{{ $animal['url'] }}" target="_blank">Name: {{ $animal['name'] }} | Age: {{ $animal['age'] }} | Gender: {{ $animal['gender'] }} | Coat: {{ $animal['coat'] }} | Breed: {{ $animal['breeds']['primary'] }}/{{ $animal['breeds']['secondary'] }}</a>
  <br>
  <img src="{{ $animal['photo'] }}"></img>
  <br>
@endforeach

@endcomponent
