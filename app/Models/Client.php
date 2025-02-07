<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Client extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'card_number'
    ];

    /**
     * Return all Cars for this Client.
     *
    */
    public function getCars()
    {
        return Car::where('client_id', $this->id)->get();
    }

    /**
     * Count all Cars for this Client.
     *
    */
    public function countCars()
    {
        return Car::where('client_id', $this->id)->count();
    }

    /**
     * Return all Services for this Client.
     *
    */
    public function countServices()
    {
        return Service::where('client_id', $this->id)->count();
    }
}
