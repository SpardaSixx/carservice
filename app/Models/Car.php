<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Car extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'car_id',
        'type',
        'registered',
        'ownbrand',
        'accidents'
    ];

    /**
     * Return the Client relation.
     *
    */
    public function getClient()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    /**
     * Return the Service for this Car.
     *
    */
    public function getService()
    {
        return Service::where('car_id', $this->car_id)->orderBy('log_number', 'desc')->first();
    }

    /**
     * Return all Services for this car.
     *
    */
    public function getServices()
    {
        return Service::where('car_id', $this->car_id)->get();
    }
}
