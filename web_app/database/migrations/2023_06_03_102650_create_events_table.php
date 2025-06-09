<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("sources", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("logo")->nullable();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("short_description")->nullable();
            $table->text("long_description");
            $table->datetime("datetime_start")->nullable();
            $table->datetime("datetime_end")->nullable();
            $table->integer("capacity")->nullable();
            $table->boolean("is_time_set")->default(false);
            $table->string("location")->nullable();
            $table->float("min_price")->nullable();
            $table->float("max_price")->nullable();
            $table->boolean("show_location_map")->default(false);
            $table->text("url")->nullable();
            $table->datetime("datetime_start_featured")->nullable();
            $table->datetime("datetime_end_featured")->nullable();
            $table->string("media_filename")->nullable();
            $table->string("media_alt")->nullable();
            $table->unsignedInteger("views")->default(0);
            $table->unsignedBigInteger("user")->nullable();
            $table->unsignedBigInteger("source")->nullable();
            $table->timestamps();

            $table->foreign("user")->references("id")->on("users")->nullOnDelete()->onUpdate("cascade");
            $table->foreign("source")->references("id")->on("sources")->nullOnDelete()->onUpdate("cascade");
        });

        Schema::create("categories", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description")->nullable();
            $table->boolean('show_on_homepage')->nullable();
            $table->timestamps();
        });

        Schema::create("category_event", function (Blueprint $table) {
            $table->unsignedBigInteger("event_id");
            $table->unsignedBigInteger("category_id");
            $table->timestamps();

            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("event_id")->references("id")->on("events")->onDelete("cascade")->onUpdate("cascade");
        });

        DB::table('users')->insert(
            array(
                'username' => 'admin',
                'name' => 'Administrador',
                'password' => Hash::make('admin'),
                'email' => 'i82@uco.es',
                'type' => 'Admin',
            )
        );

        DB::table('categories')->insert(
            array(
                'name' => 'Cine',
                'description' => 'Categoría de cine',
                'show_on_homepage' => true,
            )
        );
        DB::table('categories')->insert(
            array(
                'name' => 'Categoría oculta',
                'description' => 'Category Desc. 2',
                'show_on_homepage' => false,
            )
        );
        DB::table('categories')->insert(
            array(
                'name' => 'Conciertos',
                'description' => 'Category Desc. 3',
                'show_on_homepage' => true,
            )
        );
        DB::table('categories')->insert(
            array(
                'name' => 'Cultura',
                'description' => 'Category Desc. 4',
                'show_on_homepage' => true,
            )
        );
        DB::table('categories')->insert(
            array(
                'name' => 'Espectáculos',
                'description' => 'Category Desc. 5',
                'show_on_homepage' => true,
            )
        );
        DB::table('categories')->insert(
            array(
                'name' => 'Exposiciones',
                'description' => 'Category Desc. 6',
                'show_on_homepage' => true,
            )
        );

        DB::table('events')->insert(
            array(
                'id' => 1,
                'title' => 'GALA FEST. Córdoba Chamber Music Festival',
                'short_description' => 'Entradas a la venta en www.gala-fest.com',
                'long_description' => '
GALA 1.
Miércoles 4 de junio de 2025 – 20:30 h.
CARTAS ÍNTIMAS
CUARTETO OCAL
Michael Thomas & Salvador Esteve (violines)
Clara García (viola) • Azahara Escobar (violonchelo)
Jankek – Dvorák 
CUARTETO OCAL. Gala Fest Córdoba 2025
GALA 2.
Jueves 5 de junio de 2025 – 20:30 h.
EL AMOR ES UN PÁJARO REBELDE
Dúo de violonchelos ALMACLARA
Luiza Nancu & Beatriz González Calderón (violonchelos)
Isabel Bonilla (actriz)
150° aniversario de la ópera Carmen, de Georges Bizet.
DÚO ALMACLARA. Gala Fest Córdoba 2025
GALA 3.
Viernes 6 de junio de 2025 – 20:30 h.
DANZA BOLERA Y GUITARRA ESPAÑOLA
ÁLVARO TOSCANO & CRISTINA CAZORLA
Cristina Cazorla (danza)
Álvaro Toscano (guitarra)
– Escuela Bolera y abanico.
– Ritmos populares de la Escuela Bolera.
– Escuela Bolera en la copla y el flamenco.
ÁLVARO TOSCANO & CRISTINA CAZORLA. Gala Fest Córdoba 2025
GALA 4.
Sábado 7 de junio de 2025 – 20:30 h.
ENSOÑACIONES LÍRICAS
TRÍO VITELIA
María Ogueta (mezzosoprano)
Francisco Cantó (clarinete)
Ángela Moraza (piano)
Brahms • Mahler • Schubert • Mozart • Yuste • Alegría
TRÍO VITELIA. Gala Fest Córdoba 2025
GALA 5.
Domingo 8 de junio de 2025 – 20:30 h.
CIACCONA
CAMERATA GALA
Ana María Valderrama (violín)
Alejandro Muñoz (director)
Bach • Monteagudo • Mendelssohn • Bartók
',
                'datetime_start' => '2025-06-04 18:00',
                'datetime_end' => '2025-06-08 23:59',
                'is_time_set' => false,
                'location' => 'Fundación Antonio Gala. C. Ambrosio de Morales, 20',
                'show_location_map' => true,
                'url' => 'https://www.gala-fest.com',
                'media_filename' => 'GALA-FEST.-Cordoba-Camber-Music-Festival.-4-al-8-de-junio-de-2025.jpg',
            )
        );
        DB::table('events')->insert(
            array(
                'id' => 2,
                'title' => 'CHOQUE CULTURAL. Fundación Caja Rural del Sur',
                'long_description' => 'Nacidos en Ucrania y afincados en Kiev hasta que empezó la guerra, estos artistas llevan un año y medio viviendo en Córdoba. Este período se ha convertido en un desafío personal para ellos y un impulso creativo a la vez.
Esta serie de obras representa una reflexión artística sobre la experiencia de cambio de una cultura a otra. El choque cultural aparece aquí no sólo como confusión o nostalgia, sino como un proceso de reconfiguración de la identidad, de caos interior y de búsqueda de una nueva integridad.
Fragmentos de un paisaje emocional son retratados en el momento en que los viejos significados se van y los nuevos aún están cogiendo forma. En ellas hay sensibilidad ucraniana, dolor, simbolismo, pero también amplitud, color y ritmo españoles. Todos estos elementos se mezclan formando un lenguaje con el cual se intenta dialogar con el mundo cuando las palabras ya no son suficientes.
Este proyecto está dedicado a la gente que ha tenido que emigrar y adaptarse a una nueva cultura, siendo así un puente, un espacio de encuentro, de comprensión y aceptación.
“Choque cultural» reflexiona sobre el dolor, la adaptación y el poder de transformación. No es un final, es un comienzo; el comienzo de una nueva realidad, un nuevo lenguaje y un nuevo “nosotros”.',
                'datetime_start' => '2025-06-04 19:00',
                'datetime_end' => '2025-06-20 21:00',
                'is_time_set' => true,
                'location' => 'Fundación Caja Rural del Sur (sede Córdoba). C/ Radio, 1',
                'show_location_map' => true,
                'url' => 'https://www.fundacioncajaruraldelsur.com',
                'media_filename' => 'CHOQUE-CULTURAL.-Fundacion-Caja-Rural-del-Sur.-Cordoba.-Hasta-el-20-Junio-2025.jpg',
            )
        );
        DB::table('events')->insert(
            array(
                'id' => 3,
                'title' => '‘DANZA Y ESENCIA. UN VIAJE DE OZ A ANDALUCÍA’. Gran Teatro de Córdoba',
                'long_description' => 'El Gran Teatro de Córdoba se viste de gala para acoger Danza y Esencia: Un Viaje de Oz a Nuestra Tierra, un festival único que combina la fantasía del ballet clásico con la pasión del folklore andaluz, en una experiencia inolvidable para toda la familia.

En la primera parte, las alumnas de Tararea Laboratorio Musical nos sumergirán en el maravilloso mundo de El Mago de Oz, la célebre historia de L. Frank Baum.',
                'media_filename' => 'DANZA-Y-ESENCIA.-UN-VIAJE-DE-OZ-A-ANDALUCIA.-Gran-Teatro-de-Cordoba.-20-Junio-2025.jpg',
                'datetime_start' => '2025-06-20 18:30',
                'is_time_set' => true,
                'min_price' => 8,
                'max_price' => 10,
                'url' => 'https://teatrocordoba.es/venta-de-localidades/',
                'location' => 'Gran Teatro de Córdoba',
                'show_location_map' => true,
            )
        );
        DB::table('events')->insert(
            array(
                'id' => 4,
                'title' => 'ANDRÉS SUÁREZ. Palacio de Viana. Córdoba.',
                'short_description' => 'CICLO ‘CONCIERTOS SENTIDOS’',
                'long_description' => 'Andrés Suárez continúa cosechando éxitos a través de sus canciones inmensas de talento y emoción esta vez más cerca que nunca. desprovisto de toda su banda, en un formato íntimo, una de las voces más importantes del panorama actual español, hará un recorrido por toda su discografía para disfrutar de su música en una ocasión única.',
                'min_price' => 10,
                'datetime_start' => '2025-06-13 22:00',
                'is_time_set' => true,
                'media_filename' => 'FLAMENCO-EN-PATIOS.-Iglesia-de-la-Magdalena.-Cordoba.-Viernes-16-Mayo-2025.jpg',
                'location' => 'PALACIO DE VIANA',
                'url' => 'https://entradas.palaciodeviana.com/janto/main.php',
                'show_location_map' => true,
            )
        );
        DB::table('events')->insert(
            array(
                'id' => 5,
                'title' => 'DÍA MUNDIAL DEL MEDIO AMBIENTE',
                'long_description' => '5 DE JUNIO: DIA MUNDIAL DEL MEDIO AMBIENTE «PONER FIN A LA CONTAMINACIÓN PLÁSTICA»
TALLERES MEDIOAMBIENTALES EN CENTROS EDUCATIVOS

Organiza: Dpto Educación e Infancia Ayuntamiento Córdoba
Destinatarios: Alumnos/as centros educativos Información/inscripción: programaseducativos.cordoba.es/programa_educativo/73
ESCAPE CITY «ESCOLARES CONTRA EL DR. PLÁSTICO»

Organiza: Dpto Educación e Infancia Ayuntamiento Córdoba
Destinatarios: Alumnos/as centros educativos Información/inscripción: programaseducativos.cordoba.es/programa_educativo/73
CHARLA. DEL CLORO A LA BIODIVERSIDAD

Organiza: IMGEMA
Destinatarios: Grupo cerrado
Información/inscripción: educacion@jardinbotanicodecordoba.com
PLANTACIÓN ESPECIES ACUÁTICAS (Parque del Canal)
Organiza: IMGEMA
Destinatarios: Grupo cerrado
Información/inscripción: educacion@jardinbotanicodecordoba.com
ELABORACIÓN COMEDEROS Y BEBEDEROS. HOTEL INSECTOS (Ciudad de los Niños/as)
Organiza: IMGEMA
Destinatarios: Grupo cerrado
Información/inscripción: educacion@jardinbotanicodecordoba.com
TALLER MACETOHUERTOS (Centro Educación Ambiental)
Organiza: Dpto. Medio ambiente Ayuntamiento Córdoba
Destinatarios: Público en general
Información/inscripción: cordoba.es/medio-ambiente
TALLER DESAYUNOS Y MERIENDAS SOSTENIBLES (Centro Educación Ambiental)
Organiza: Dpto. Medio ambiente Ayuntamiento Córdoba
Destinatarios: Público en general
Información/inscripción: cordoba.es/medio-ambiente
VISITA INSTALACIONES POTABILIZADORAS Y DEPURADORAS
Organiza: EMACSA
Destinatarios: Público en general
Información/inscripción: visitas@rnesto.bio https://forms.office.com/e/NHhvbi5Xud
TALLERES EN CENTROS EDUCATIVOS SOBRE CICLO DEL AGUA Organiza: EMACSA
Destinatarios: Grupo Cerrado
Información/inscripción: emacsa.es
TALLERES DE EDUCACIÓN AMBIENTAL CENTRO DE CONSERVACIÓN ZOO CÓRDOBA
Organiza: Dpto. Medio ambiente Ayuntamiento Córdoba
Destinatarios: Grupo cerrado
Información/inscripción: cordoba.es/medio-ambiente
CHARLA: «LA IMPORTANCIA DE LAS AVES INSECTÍVORAS EN LA CIUDAD DE CÓRDOBA»
Organiza: Dpto. Medio ambiente Ayuntamiento Córdoba
Destinatarios: Grupo cerrado
Información/inscripción: cordoba.es/medio-ambiente
VIERNES 6 DE JUNIO «Parque del Cola- Cao» (Parque Cruz Conde)
Organiza: SADECO.
HORARIO DE MAÑANA (9:00h a 13:30h)
Actividades:
– Exposición de vehículos.
– Cuentacuentos.
– Escape Box
– Teatro de Marionetas
– Separación de residuos y cuidado del entorno.
Destinatarios: grupo completo.
HORARIO DE TARDE (a partir de las 17:30h)
Actividades:
– Exposición de vehículos.
– Talleres de Reutilización
– Teatro de Puestos de artesanía sostenible y de 2′ mano
– Concierto.
Destinatarios: Público en general
Información/inscripción: sadeco.es',
                'media_filename' => 'DIA-MUNDIAL-DEL-MEDIO-AMBIENTE.-Cordoba.-5-de-junio-de-2025.jpg',
                'datetime_start' => '2025-06-05 00:00',
                'is_time_set' => false,
            )
        );
        DB::table('events')->insert(
            array(
                'id' => 6,
                'title' => 'Concierto de LEIVA',
                'long_description' => 'Concierto de LEIVA',
                'media_filename' => 'LEIVA.-Plaza-de-Toros-de-Cordoba.-27-Septiembre-2025.jpg',
                'url' => 'https://www.leivaweb.es/conciertos/',
                'datetime_start' => '2025-09-27 22:00',
                'is_time_set' => true,
                'location' => 'Plaza de Toros de Cordoba',
                'show_location_map' => true,
                'capacity' => 1000,
            )
        );
        DB::table('events')->insert(
            array(
                'id' => 7,
                'title' => 'Filmoteca de Andalucía en Córdoba. PROGRAMACIÓN',
                'long_description' => '',
                'media_filename' => 'TECA-ICON.jpg',
                'location' => 'Filmoteca de Andalucía en Córdoba',
                'show_location_map' => true,
                'is_time_set' => false,
                'url' => 'https://www.filmotecadeandalucia.es',
                'start_highlight' => '2023-09-27 22:00',
                'end_highlight' => '2027-09-27 22:00',
            )
        );
        DB::table('events')->insert(
            array(
                'id' => 8,
                'title' => '‘HOY BAILO POR TI XI’. Teatro Góngora.',
                'long_description' => 'Gala fin de curso
Festival Benéfico Flamenco.
Artista invitada: Carmen Rey
',
                'media_filename' => 'HOY-BAILO-POR-TI-XI.-Teatro-Gongora.-Cordoba.-15-Junio-2025.jpg',
                'url' => 'https://teatrocordoba.es/venta-de-localidades/',
                'datetime_start' => '2025-06-15 19:00',
                'is_time_set' => true,
                'location' => 'Teatro Góngora',
                'show_location_map' => true,
                'min_price' => 10,
            )
        );
        DB::table('events')->insert(
            array(
                'id' => 9,
                'title' => 'AGUILERA Y MENÍ. MISIÓN IMPRO-SIBLE',
                'long_description' => 'El nuevo espectáculo de Aguilera y Mení, dos auténticos maestros de la improvisación, el ingenio y la conexión con el público.
Localidades: 30 € (sillas, gradas general y lateral); 28 € (grada alta)',
                'media_filename' => 'AGUILERA-Y-MENI.-MISION-IMPRO-SIBLE.-Teatro-de-la-Axerquia.-Cordoba.-18-Septiembre-2025.jpg',
                'url' => 'https://teatrocordoba.es/venta-de-localidades/',
                'datetime_start' => '2025-09-18 21:00',
                'is_time_set' => true,
                'location' => 'Teatro de la Axerquía',
                'show_location_map' => true,
                'min_price' => 28,
                'max_price' => 30,
            )
        );
        DB::table('events')->insert(
            array(
                'id' => 10,
                'title' => '‘MÚSICA PARA EL ALMA’. Teatro Gógora.',
                'long_description' => '
La Fundación Funlabor organiza este concierto benéfico con el objetivo de recaudar fondos para apoyar a personas con discapacidad intelectual. 
 
Presentado por Alberto de Paz
 
Participan:
Emin Kiourktchian
Coro de Ópera de Córdoba
Estudio de baile “Estefanía Cuevas”
Conservatorio Profesional de Córdoba
Músicos de la Orquesta de Córdoba
Marta Aloisio Vera
Mario Peña Contador
',
                'media_filename' => 'MUSICA-PARA-EL-ALMA.-Teatro-Gogora.-Cordoba.-Sabado-21-Junio-2025.jpg',
                'url' => 'https://teatrocordoba.es/venta-de-localidades/',
                'datetime_start' => '2025-06-21 20:00',
                'is_time_set' => true,
                'location' => 'Teatro Góngora',
                'show_location_map' => true,
                'min_price' => 10,
                'max_price' => 15,
            )
        );
        DB::table('events')->insert(
            array(
                'id' => 11,
                'title' => 'V CICLO DE CINE IGUALDAD Y CULTURA. Palacio de la Merced',
                'long_description' => 'La Diputación de Córdoba presenta el V Ciclo de Cine en la Merced, bajo el lema “Igualdad y Cultura”, se proyectarán cuatro películas imprescindibles que abordan la igualdad, la memoria, la identidad y los derechos humanos. Todos los miércoles de junio en el Patio Blanco del Palacio de la Merced.
PROYECCIÓN DE LA PELÍCULA
SOY NEVENKA
Dir. Icíar Bollaín.
España, 2024. 110 min.
PRÓXIMAS PROYECCIONES:
▪ Miércoles 11 junio – Siempre nos quedará mañana, de Paola Cortellesi
▪ Miércoles 18 junio – Emilia Pérez, de Jacques Audiard
▪ Miércoles 25 junio – Un hombre libre, de Laura Hojman (con la presencia de la directora)',
                'media_filename' => 'V-CICLO-DE-CINE-IGUALDAD-Y-CULTURA.-Palacio-de-la-Merced.-Cordoba.-Junio-2025.jpg',
                'datetime_start' => '2025-06-04 00:00',
                'datetime_end' => '2025-06-25 23:59',
                'is_time_set' => false,
                'location' => 'Palacio de la Merced. Plaza de Colón, 15',
                'show_location_map' => true,
                'min_price' => 0,
            )
        );
        DB::table('events')->insert(
            array(
                'id' => 12,
                'title' => 'Actividades Bibliotecas de Córdoba',
                'long_description' => '',
                'media_filename' => 'logo-bibliotecas-cordoba-320x77.png',
                'is_time_set' => false,
                'min_price' => 0,
                'url' => 'https://calendar.google.com/calendar/embed?title=Calendario+Actividades+Bibliotecas+C%C3%B3rdoba&showPrint=0&height=600&wkst=1&bgcolor=%23ffffff&src=9ge4l1stujh08ebtnhmm16fh2g@group.calendar.google.com&color=%23182C57&src=uplcqkb54d174n8h4doo9svqmk@group.calendar.google.com&color=%23B1440E&src=bibliocor@gmail.com&color=%230F4B38&src=hr9mau7d6sbe8ed2q636rqkna4@group.calendar.google.com&color=%23691426&src=h1f60g1li4t10acl1o5uhfvd5k@group.calendar.google.com&color=%230F4B38&src=6b05iqt72p615935k40dlge1h0@group.calendar.google.com&color=%23125A12&src=hf51sebr7dj3j06d81kqcp5ii8@group.calendar.google.com&color=%23182C57&src=8e0h3n6r2huaanm5ijpcujnarg@group.calendar.google.com&color=%23853104&src=9h7mo9r2u3bg90epip88ar9kes@group.calendar.google.com&color=%23875509&src=fcoclfo4kt9tijmptpgajv43es@group.calendar.google.com&color=%23AB8B00&src=qe596pq103k8csik8d5afgoo70@group.calendar.google.com&color=%23711616&src=cibmsa4aa3o51imt0o1amqvsmg@group.calendar.google.com&color=%236B3304&ctz=Europe/Madrid',
                'start_highlight' => '2023-09-27 22:00',
                'end_highlight' => '2027-09-27 22:00',
            )
        );
        DB::table('events')->insert(
            array(
                'id' => 13,
                'title' => 'Test',
                'long_description' => '',
                'media_filename' => '800x1800.png',
                'is_time_set' => false,
                'datetime_start' => '2026-06-04 00:00',
                'min_price' => 0,
                'url' => 'https://calendar.google.com/calendar/embed?title=Calendario+Actividades+Bibliotecas+C%C3%B3rdoba&showPrint=0&height=600&wkst=1&bgcolor=%23ffffff&src=9ge4l1stujh08ebtnhmm16fh2g@group.calendar.google.com&color=%23182C57&src=uplcqkb54d174n8h4doo9svqmk@group.calendar.google.com&color=%23B1440E&src=bibliocor@gmail.com&color=%230F4B38&src=hr9mau7d6sbe8ed2q636rqkna4@group.calendar.google.com&color=%23691426&src=h1f60g1li4t10acl1o5uhfvd5k@group.calendar.google.com&color=%230F4B38&src=6b05iqt72p615935k40dlge1h0@group.calendar.google.com&color=%23125A12&src=hf51sebr7dj3j06d81kqcp5ii8@group.calendar.google.com&color=%23182C57&src=8e0h3n6r2huaanm5ijpcujnarg@group.calendar.google.com&color=%23853104&src=9h7mo9r2u3bg90epip88ar9kes@group.calendar.google.com&color=%23875509&src=fcoclfo4kt9tijmptpgajv43es@group.calendar.google.com&color=%23AB8B00&src=qe596pq103k8csik8d5afgoo70@group.calendar.google.com&color=%23711616&src=cibmsa4aa3o51imt0o1amqvsmg@group.calendar.google.com&color=%236B3304&ctz=Europe/Madrid',
            )
        );
        DB::table('category_event')->insert(
            array([
                'event_id' => 1,
                'category_id' => 3,
            ], 
            [
                'event_id' => 2,
                'category_id' => 6,
            ],
            [
                'event_id' => 3,
                'category_id' => 6,
            ],
            [
                'event_id' => 4,
                'category_id' => 3,
            ],
            [
                'event_id' => 5,
                'category_id' => 4,
            ],
            [
                'event_id' => 6,
                'category_id' => 3,
            ],
            [
                'event_id' => 7,
                'category_id' => 1,
            ],
            [
                'event_id' => 8,
                'category_id' => 5,
            ],
            [
                'event_id' => 9,
                'category_id' => 5,
            ],
            [
                'event_id' => 10,
                'category_id' => 3,
            ],
            [
                'event_id' => 11,
                'category_id' => 1,
            ],
            [
                'event_id' => 12,
                'category_id' => 4,
            ])
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events_categories');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('events');
        Schema::dropIfExists('users');
    }
};
