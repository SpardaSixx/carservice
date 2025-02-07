<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Client;
use App\Models\Service;

class AppController extends Controller
{
    /**
     * Show the clients page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Adatbázis ellenőrzése
        $this->checkDatabase();

        //Validáció
        if($request->query()){
            $validateSearch = $this->validateSearch($request);

            switch($validateSearch) {
                case 901:
                    return redirect('/')->with('error', 'Hiba! Nem töltheti ki mindkét mezőt!');
                    break;
                case 902:
                    return redirect('/')->with('error', 'Hiba! Legalább az egyik mező kitöltése kötelező!');
                    break;
            }

            $request->validate([
                'card_number' => 'nullable|alpha_num:ascii',
            ]);
        }

        //Kereső
        $query = Client::orderBy('id', 'asc');

        if($request->filled('name')){
            $name = $request->input('name');
            $query->where('name', 'LIKE', "%{$name}%");
        }

        if($request->filled('card_number')){
            $card_number = $request->input('card_number');
            $query->where('card_number', $card_number);
        }

        $clients = $query->paginate(50);

        $extraFields = false;

        if($request->filled('name') && $clients->total() > 1){
            return redirect('/')->with('error', 'Hiba! Több, mint egy találat! Próbáljon pontosabb adatok alapján keresni!');
        }

        if($clients->total() == 1){
            $extraFields = true;
        }

        return view('index', [
            'clients' => $clients,
            'extraFields' => $extraFields
        ]);
    }

    /**
     * Validate the search.
     */
    public function validateSearch($request){
        if( $request->filled('name') && $request->filled('card_number') ){
            return 901;
        }

        if( empty($request->name) && empty($request->card_number) ){
            return 902;
        }
    }

    /**
     * Return service by client.
     */
    public function services($carId){
        $services = Service::where('car_id', $carId)->get();

        return response()->json($services);
    }

    /**
     * Change null date to valid date.
     */
    public function changedate($carId){
        $car = Car::where('car_id', $carId)->first();

        return $car->registered;
    }

    /**
     * Check database for entities.
     */
    public function checkDatabase(){
        $clients = Client::all();
        $this->checkClients($clients);

        $cars = Car::all();
        $this->checkCars($cars);

        $services = Service::all();
        $this->checkServices($services);
    }

    /**
     * Check if count is zero.
     */
    public function checkClients($clients){
        if($clients->count() == 0){
            $clientJson = file_get_contents('json/clients.json');
            $clientArray = json_decode($clientJson);

            foreach($clientArray as $client){
                $newClient = new Client;
                $newClient->id = $client->id;
                $newClient->name = $client->name;
                $newClient->card_number = $client->idcard;
                $newClient->save();
            }
        }
    }

    /**
     * Check if count is zero.
     */
    public function checkCars($cars){
        if($cars->count() == 0){
            $carJson = file_get_contents('json/cars.json');
            $carArray = json_decode($carJson);

            foreach($carArray as $car){
                $newCar = new Car;
                $newCar->id = $car->id;
                $newCar->client_id = $car->client_id;
                $newCar->car_id = $car->car_id;
                $newCar->type = $car->type;
                $newCar->registered = $car->registered;
                $newCar->ownbrand = $car->ownbrand;
                $newCar->accidents = $car->accident;
                $newCar->save();
            }
        }
    }

    /**
     * Check if count is zero.
     */
    public function checkServices($services){
        if($services->count() == 0){
            $serviceJson = file_get_contents('json/services.json');
            $serviceArray = json_decode($serviceJson);


            foreach($serviceArray as $service){
                $newService = new Service;
                $newService->id = $service->id;
                $newService->client_id = $service->client_id;
                $newService->car_id = $service->car_id;
                $newService->log_number = $service->lognumber;
                $newService->event = $service->event;
                $newService->event_time = $service->eventtime;
                $newService->document_id = $service->document_id;
                $newService->save();
            }
        }
    }
}
