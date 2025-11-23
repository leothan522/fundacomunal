<?php

namespace Database\Seeders;

use App\Models\Estado;
use App\Models\Municipio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $municipiosPorEstado = [
            "AMAZONAS" => [
                "ALTO ORINOCO","ATABAPO","ATURES","AUTANA","MANAPIARE","MAROA","RIO NEGRO"
            ],
            "ANZOATEGUI" => [
                "ANACO","FERNANDO DE PEÑALVER","FRANCISCO DEL CARMEN CARVAJAL","GUANTA","JUAN ANTONIO SOTILLO",
                "JUAN MANUEL CAJIGAL","MANUEL EZEQUIEL BRUZUAL","PEDRO MARIA FREITES","PIRITU","SAN JOSE DE GUANIPA",
                "SAN JUAN DE CAPISTRANO","SANTA ANA","SIMON BOLIVAR","SIR ARTUR MC GREGOR","SUCRE","TURISTICO DIEGO BAUTISTA URBANEJA"
            ],
            "APURE" => [
                "ACHAGUAS","BIRUACA","MUÑOZ","PAEZ","PEDRO CAMEJO","ROMULO GALLEGOS","SAN FERNANDO"
            ],
            "ARAGUA" => [
                "BOLIVAR","CAMATAGUA","FRANCISCO LINARES ALCANTARA","GIRARDOT","JOSE ANGEL LAMAS","JOSE FELIX RIBAS",
                "JOSE RAFAEL REVENGA","LIBERTADOR","MARIO BRICEÑO IRAGORRY","SAN CASIMIRO","SAN SEBASTIAN",
                "SANTIAGO MARIÑO","SANTOS MICHELENA","SUCRE","TOVAR","URDANETA","OCUMARE DE LA COSTA DE ORO"
            ],
            "BARINAS" => [
                "ALBERTO ARVELO TORREALBA","ARISMENDI","BARINAS","BOLIVAR","CRUZ PAREDES","EZEQUIEL ZAMORA",
                "OBISPOS","PEDRAZA","ROSCIO","SOSA"
            ],
            "BOLIVAR" => [
                "BOLIVARIANO ANGOSTURA","CARONI","CEDEÑO","EL CALLAO","GRAN SABANA","HERES","PADRE PEDRO CHIEN",
                "PIAR","SIFONTES","SUCRE"
            ],
            "CARABOBO" => [
                "BEJUMA","CARLOS ARVELO","DIEGO IBARRA","GUACARA","JUAN JOSE MORA","LIBERTADOR","LOS GUAYOS",
                "MONTALBAN","NAGUANAGUA","PUERTO CABELLO","SAN DIEGO","SAN JOAQUIN","VALENCIA"
            ],
            "DELTA AMACURO" => [
                "ANTONIO DIAZ","CASACOIMA","PEDERNALES","TUCUPITA"
            ],
            "DISTRITO CAPITAL" => [
                "LIBERTADOR"
            ],
            "FALCON" => [
                "BUCHIVACOA","CACIQUE MANAURE","CARIRUBANA","COLINA","DABAJURO","FEDERACION","JACURA","LOS TAQUES",
                "MAUROA","MIRANDA","MONSEÑOR ITURRIZA","PALMASOLA","PETIT","PIRITU","SAN FRANCISCO","SILVA",
                "TOCOPERO","URUMACO","ZAMORA"
            ],
            "GUARICO" => [
                "CAMAGUAN",
                "CHAGUARAMAS",
                "EL SOCORRO",
                "FRANCISCO DE MIRANDA",
                "JOSE FELIX RIBAS",
                "JOSE TADEO MONAGAS",
                "JUAN GERMAN ROSCIO",
                "JULIAN MELLADO",
                "LAS MERCEDES",
                "LEONARDO INFANTE",
                "ORTIZ",
                "PEDRO ZARAZA",
                "SAN GERONIMO DE GUAYABAL",
                "SAN JOSE DE GUARIBE",
                "SANTA MARIA DE IPIRE"
            ],
            "LA GUAIRA" => [
                "VARGAS"
            ],
            "LARA" => [
                "CRESPO","IRIBARREN","JIMENEZ","MORAN","PALAVECINO","SIMON PLANAS","TORRES"
            ],
            "MERIDA" => [
                "ALBERTO ADRIANI","ARICAGUA","ARZOBISPO CHACON","CAMPO ELIAS","CARDENAL QUINTERO","CARACCIOLO PARRA OLMEDO",
                "JUSTO BRICEÑO","LIBERTADOR","OBISPO RAMOS DE LORA","PADRE NOGUERA","PUEBLO LLANO","RIVAS DAVILA",
                "SANTOS MARQUINA","SUCRE","TOVAR","TULIO FEBRES CORDERO","ZEA"
            ],
            "MIRANDA" => [
                "ACEVEDO","ANDRES BELLO","BARUTA","BRION","BUROZ","CARRIZAL","CHACAO","CRISTOBAL ROJAS","EL HATILLO",
                "GUAICAIPURO","INDEPENDENCIA","LANDER","LOS SALIAS","PAZ CASTILLO","PLAZA","SIMON BOLIVAR","SUCRE"
            ],
            "MONAGAS" => [
                "ACOSTA","AGUASAY","ARAGUA","CARIPE","CEDEÑO","EZEQUIEL ZAMORA","LIBERTADOR","MATURIN","PIAR",
                "PUNCERES","SANTA BARBARA","SOTILLO","URACOA"
            ],
            "NUEVA ESPARTA" => [
                "ANTOLIN DEL CAMPO","ARISMENDI","DIAZ","GARCIA","MARCANO","MANEIRO","MARIÑO","PENINSULA DE MACANAO",
                "TUBORES","VILLALBA"
            ],
            "PORTUGUESA" => [
                "AGUA BLANCA","ARAURE","ESTELLER","GUANARE","GUANARITO","MONSEÑOR JOSE VICENTE DE UNDA","OSPINO",
                "PAEZ","PAPELON","SAN GENARO DE BOCONOITO","SAN RAFAEL DE ONOTO","SANTA ROSALIA","SUCRE","TUREN"
            ],
            "SUCRE" => [
                "ANDRES ELOY BLANCO","ANDRES MATA","ARISMENDI","BENITEZ","BERMUDEZ","BOLIVAR","CAJIGAL","CRUZ SALMERON ACOSTA",
                "LIBERTADOR","MARIÑO","MEJIA","MONTES","RIBERO","SUCRE","VALDEZ"
            ],
            "TACHIRA" => [
                "ANDRES BELLO","BOLIVAR","CARDENAS","CORDOBA","FERNANDEZ FEO","FRANCISCO DE MIRANDA","GUASIMOS",
                "INDEPENDENCIA","JAUREGUI","JUNIN","LIBERTAD","LIBERTADOR","LOBATERA","MICHELENA","PANAMERICANO",
                "PEDRO MARIA UREÑA","RAFAEL URDANETA","SAMUEL DARIO MALDONADO","SAN CRISTOBAL","SEBORUCO","SIMON RODRIGUEZ",
                "SUCRE","TORBES","URIBANTE"
            ],
            "TRUJILLO" => [
                "BOCONO","BOLIVAR","CANDELARIA","CARACHE","ESCUQUE","JOSE FELIPE MARQUEZ CAGUAO","JUAN VICENTE CAMPO ELIAS",
                "LA CEIBA","MIRANDA","MONTE CARMELO","MOTATAN","PAMPAN","PAMPANITO","RAFAEL RANGEL","SAN RAFAEL DE CARVAJAL",
                "SUCRE","TRUJILLO","URDANETA","VALERA"
            ],
            "YARACUY" => [
                "ARISTIDES BASTIDAS","BOLIVAR","BRUZUAL","COCOROTE","INDEPENDENCIA","JOSE ANTONIO PAEZ","LA TRINIDAD",
                "MANUEL MONGE","NIRGUA","PEÑA","SAN FELIPE","SUCRE","URACHICHE","VEROES"
            ],
            "ZULIA" => [
                "ALMIRANTE PADILLA","BARALT","CABIMAS","CATATUMBO","COLON","FRANCISCO JAVIER PULGAR","JESUS ENRIQUE LOSSADA",
                "JESUS MARIA SEMPRUM","LA CAÑADA DE URDANETA","LAGUNILLAS","MACHIQUES DE PERIJA","MARACAIBO","MIRANDA",
                "PAEZ","ROSARIO DE PERIJA","SAN FRANCISCO","SANTA RITA","SIMON BOLIVAR","SUCRE","VALMORE RODRIGUEZ"
            ]
        ];

        foreach ($municipiosPorEstado as $estado => $municipios){
            $estados_id = Estado::where('nombre', $estado)->first()->id;
            if ($estados_id){
                foreach ($municipios as $municipio){
                    Municipio::create([
                        'nombre' => $municipio,
                        'estados_id' => $estados_id
                    ]);
                }
            }
        }

    }
}
