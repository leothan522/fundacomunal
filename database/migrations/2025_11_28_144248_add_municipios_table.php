<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('municipios', function (Blueprint $table) {
            $table->string('nombre_real')->nullable()->after('nombre');
            $table->string('nombre_cne')->nullable()->after('nombre_real');
        });

        $municipios = [
            "CAMAGUAN" => "CAMAGUÁN",
            "CHAGUARAMAS" => "CHAGUARAMAS",
            "EL SOCORRO" => "EL SOCORRO",
            "FRANCISCO DE MIRANDA" => "FRANCISCO DE MIRANDA",
            "JOSE FELIX RIBAS" => "JOSÉ FÉLIX RIBAS",
            "JOSE TADEO MONAGAS" => "JOSÉ TADEO MONAGAS",
            "JUAN GERMAN ROSCIO" => "JUAN GERMÁN ROSCIO",
            "JULIAN MELLADO" => "JULIÁN MELLADO",
            "LAS MERCEDES" => "JUAN JOSE RONDON",
            "LEONARDO INFANTE" => "LEONARDO INFANTE",
            "ORTIZ" => "ORTIZ",
            "PEDRO ZARAZA" => "PEDRO ZARAZA",
            "SAN GERONIMO DE GUAYABAL" => "SAN GERÓNIMO DE GUAYABAL",
            "SAN JOSE DE GUARIBE" => "SAN JOSÉ DE GUARIBE",
            "SANTA MARIA DE IPIRE" => "SANTA MARÍA DE IPIRE",
        ];

        $municipiosCNE = [
            "CAMAGUAN" => "MP. CAMAGUAN",
            "CHAGUARAMAS" => "MP. CHAGUARAMAS",
            "EL SOCORRO" => "MP. EL SOCORRO",
            "FRANCISCO DE MIRANDA" => "MP. MIRANDA",
            "JOSE FELIX RIBAS" => "MP. RIBAS",
            "JOSE TADEO MONAGAS" => "MP. MONAGAS",
            "JUAN GERMAN ROSCIO" => "MP. JUAN GERMAN ROSCIO N.",
            "JULIAN MELLADO" => "MP. MELLADO",
            "LAS MERCEDES" => "MP. JUAN JOSE RONDON",
            "LEONARDO INFANTE" => "MP. INFANTE",
            "ORTIZ" => "MP. ORTIZ",
            "PEDRO ZARAZA" => "MP. ZARAZA",
            "SAN GERONIMO DE GUAYABAL" => "MP.SAN GERONIMO DE G",
            "SAN JOSE DE GUARIBE" => "MP.S JOSE DE GUARIBE",
            "SANTA MARIA DE IPIRE" => "MP. S MARIA DE IPIRE",
        ];

        $data = \App\Models\Municipio::get();
        foreach ($data as $municipio){
            $nombre = $municipio->nombre;
            $municipio->nombre_real = $municipios[$nombre] ?? $nombre;
            $municipio->nombre_cne = $municipiosCNE[$nombre] ?? $nombre;
            $municipio->save();
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('municipios', function (Blueprint $table) {
            //
        });
    }
};
