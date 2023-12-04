<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;

class TestEmailTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envío email de prueba, para ver que jale';

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
     * @return mixed
     */
    public function handle()
    {
        $hola = 'Hola';
        $data_for_email = [
                'id_seguimiento2'   => 'Hola',
            ];

        Mail::send('emails.prueba', $data_for_email, function ($m) use ($hola) {
          
            $m->from('leonardoalonso.galicia.contractor@bbva.com', 'MESA JC');
            $m->to('marioalberto.rangel.contractor@bbva.com', 'Beto Rangel')->subject("Hola soy una prueba automatizada en laravel");
            //$m->to('marcoantonio.negrete.contractor@bbva.com', 'Beto Rangel')->subject("Hola soy una prueba automatizada en laravel");
                            
        });
            
    }
}
