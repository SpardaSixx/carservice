@extends('layouts.default')

@section('content')
<h2 class="text-center my-2">Ügyfelek</h2>

<section class="search text-center py-3">
    <form  action="/" method="get" class="search-form">
        <div class="form-group d-inline-block me-2">
            <input type="text" class="form-control mb-1" name="name" placeholder="Név" value="{{ isset($_GET['name']) ? $_GET['name'] : '' }}">
        </div>

        <div class="form-group d-inline-block me-2">
            <input type="text" class="form-control mb-1" name="card_number" placeholder="Okmányazonosító" value="{{ isset($_GET['card_number']) ? $_GET['card_number'] : '' }}">
        </div>

        <button type="submit" class="btn btn-custom d-inline-block me-2 px-4">Keresés</button>
    </form>
</section>

<section class="clients py-3">
    <span>Összesen {{$clients->total()}} ügyfél</span>

    <div class="list-header py-3">
        <div class="row">
            <div class="{{$extraFields ? 'col-2' : 'col-4'}} text-center">Ügyfélazonosító</div>
    
            <div class="{{$extraFields ? 'col-2' : 'col-4'}} text-center">Név</div>
    
            <div class="{{$extraFields ? 'col-2' : 'col-4'}} text-center">Okmányazonosító</div>

            @if($extraFields)
            <div class="col-2 text-center">Autók</div>

            <div class="col-2 text-center">Szervíznaplók</div>
            @endif
        </div>
    </div>

    <div class="accordion" id="accordionClients">
        @foreach($clients as $client)
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{$client->id}}">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$client->id}}" aria-expanded="false" aria-controls="collapse{{$client->id}}">
                    <div class="row">
                        <div class="{{$extraFields ? 'col-2' : 'col-4'}} text-center">{{$client->id}}</div>

                        <div class="{{$extraFields ? 'col-2' : 'col-4'}} text-center">{{$client->name}}</div>

                        <div class="{{$extraFields ? 'col-2' : 'col-4'}} text-center">{{$client->card_number}}</div>

                        @if($extraFields)
                        <div class="col-2 text-center">{{$client->countCars()}}</div>

                        <div class="col-2 text-center">{{$client->countServices()}}</div>
                        @endif
                    </div>
                </button>
            </h2>

            <div id="collapse{{$client->id}}" class="accordion-collapse collapse" aria-labelledby="heading{{$client->id}}" data-bs-parent="#accordionClients">
                <div class="accordion-body">
                    @if($client->getCars()->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sorszám</th>
                                <th scope="col">Típus</th>
                                <th scope="col">Regisztrálva</th>
                                <th scope="col">Saját márkás</th>
                                <th scope="col">Balesetek</th>
                                <th scope="col">Esemény neve</th>
                                <th scope="col">Esemény időpontja</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($client->getCars() as $car)
                            <tr class="style-pointer" onclick="callServices('{{route('services', $car->car_id)}}')">
                                <td>{{$car->car_id}}</td>
                                <td>{{$car->type}}</td>
                                <td>{{$car->registered}}</td>
                                <td>{{$car->ownbrand ? 'Igen' : 'Nem'}}</td>
                                <td>{{$car->accidents}}</td>
                                <td>{{$car->getService()->event}}</td>
                                <td>{{$car->getService()->event_time}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-center">Az ügyfélhez nem tartozik autó!</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($clients->total() > 50)
    <div class="pagination text-center d-block mb-4">
        <a href="{{ $clients->withQueryString()->previousPageUrl() }}" class="d-inline-block {{ $clients->onFirstPage() ? 'disabled' : '' }}">
            <span class="material-symbols-outlined">
            chevron_left
            </span>
        </a>
        @php
         $totalPages = ceil( $clients->total() / $clients->perPage() );
         if($totalPages == 0){
            $totalPages = 1;
         }
        @endphp
        <span>{{ $clients->currentPage().'/'.$totalPages }}</span>
        <a href="{{ $clients->withQueryString()->nextPageUrl() }}" class="d-inline-block {{ $clients->hasMorePages() ? '' : 'disabled' }}">
            <span class="material-symbols-outlined">
            chevron_right
            </span>
        </a>
    </div>
    @endif
</section>
  
<div class="modal fade" id="servicesModal" tabindex="-1" aria-labelledby="servicesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">Alkalom sorszáma</th>
                        <th scope="col">Esemény neve</th>
                        <th scope="col">Esemény időpontja</th>
                        <th scope="col">Munkalap azonosító</th>
                      </tr>
                    </thead>

                    <tbody id="servicesModalBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
