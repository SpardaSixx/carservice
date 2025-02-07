<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Service extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'car_id',
        'log_number',
        'event',
        'event_time',
        'document_id'
    ];

    /**
     * Return Car register date.
     *
    */
    public function getCarRegisterDate()
    {
        $car = Car::where('car_id', $this->car_id)->first();

        return $car->registered;
    }
}
