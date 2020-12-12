<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\PetFinder\Animal;
use App\Traits\PetFinder\GetAuth;

use App\Mail\PetFinder\NewCatsEmail;

use Ixudra\Curl\Facades\Curl;
use Mail;

class FindCats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'petfinder:cats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    protected $newAnimals = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $token = GetAuth::getAuth();
        $page = 1;
        $totalPages = 1;
        $response = Curl::to('https://api.petfinder.com/v2/animals?location=20191&distance=15&type=Cat&age=baby&limit=100&page'. $page)
                    ->withHeaders([
                      'Authorization' => 'Bearer '. $token->access_token
                      ])
                    ->get();

        $response = json_decode($response, true);


        if($response['pagination']['total_pages'] >= 1)
        {
          $totalPages = $response['pagination']['total_pages'];
          while($page <= $totalPages)
          {
            $response = Curl::to('https://api.petfinder.com/v2/animals?location=20191&distance=15&type=Cat&age=baby&limit=100&page'. $page)
                        ->withHeaders([
                          'Authorization' => 'Bearer '. $token->access_token
                          ])
                        ->get();

            $response = json_decode($response, true);

            foreach($response['animals'] as $animal)
            {
              $this->line($animal['name'] .' '. $animal['age'] .' '. $animal['status']);
              $this->addAnimal($animal);
            }

            $page++;
          }

          if(count($this->newAnimals) != 0)
          {
            $this->sendEmail();
          }
        }
    }

    public function addAnimal($animal)
    {
      $a = Animal::where('animal_id', $animal['id'])->first();
      if(!$a)
      {
        $ani = Animal::create([
          'animal_id' => $animal['id'],
          'status' => $animal['status'],
          'name' => $animal['name'],
          'age' => $animal['age'],
          'gender' => $animal['gender'],
          'coat' => $animal['coat'],
          'description' => $animal['description'],
          'breeds' => $animal['breeds'],
          'url' => $animal['url'],
          'photo' => count($animal['photos']) > 0 ? $animal['photos'][0]['full'] : null
        ]);

        array_push($this->newAnimals, $ani);
      }
    }

    public function sendEmail()
    {
      Mail::to('dmtitus5@gmail.com')->send(new NewCatsEmail($this->newAnimals));
      Mail::to('alexandrafrith2018@gmail.com')->send(new NewCatsEmail($this->newAnimals));
      foreach($this->newAnimals as $animal)
      {
        $a = Animal::find($animal->id);
        $a->emailed = true;
        $a->save();
      }
    }
}
