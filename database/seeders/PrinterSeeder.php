<?php

namespace Database\Seeders;

use App\Models\Printer;
use App\Models\PrintServer;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PrinterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $server = new PrintServer();
        $server->team_id = Team::where('personal_team', false)->firstOrFail('id')->id;
        $server->name = 'Debug Print Server';
        $server->save();
        $token = $server->tokens()->create([
            'name' => sprintf('%s Access Token', $server->name),
            'token' => hash('sha256', $plainTextToken = 'DEBUG_WEBPRINT_SERVICE_KEY'),
            'abilities' => ['*'],
        ]);
        echo sprintf("\n\n\n%s=\"%s|%s\"\n\n\n", strtoupper(Str::slug(sprintf('%s Access Token', $server->name), '_')), $token->id, $plainTextToken);
        $this->AddDebugPrinters($server);

        $server = new PrintServer();
        $server->team_id = Team::where('personal_team', false)->firstOrFail('id')->id;
        $server->name = 'Local Print Server';
        $server->save();
        $token = $server->createToken(sprintf('%s Access Token', $server->name));
        echo sprintf("\n\n\n%s=\"%s\"\n\n\n", strtoupper(Str::slug($token->accessToken->name, '_')), $token->plainTextToken);
        $this->AddLocalPrinters($server);

        $server = new PrintServer();
        $server->team_id = Team::where('personal_team', false)->firstOrFail('id')->id;
        $server->name = 'LAN Print Server';
        $server->save();
        $token = $server->createToken(sprintf('%s Access Token', $server->name));
        echo sprintf("\n\n\n%s=\"%s\"\n\n\n", strtoupper(Str::slug($token->accessToken->name, '_')), $token->plainTextToken);
        $this->AddLanPrinters($server);
    }

    protected function AddDebugPrinters(PrintServer $server)
    {
        $printer = new Printer();
        $printer->uri = 'debug://debug';
        $printer->name = 'DEBUG';
        $printer->raw_languages_supported = ['*'];
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('Debug/debug');

        $server->Printers()->save($printer);
    }

    protected function AddLocalPrinters(PrintServer $server)
    {
        $printer = new Printer();
        $printer->uri = 'lpd://192.168.9.20/OKI-ML3320?timeout=10&tries=10';
        $printer->name = 'OKI-ML3320 (LPD)';
        $printer->location = 'Serwerownia - Pierwsze Piętro';
        $printer->raw_languages_supported = ['microline'];
        $printer->enabled = true;
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'socket://192.168.9.20:9101?timeout=10';
        $printer->name = 'OKI-ML3320 (RAW)';
        $printer->location = 'Serwerownia - Pierwsze Piętro';
        $printer->raw_languages_supported = ['microline'];
        $printer->enabled = true;
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://OKI_ML3320';
        $printer->name = 'OKI-ML3320 (CUPS)';
        $printer->location = 'Serwerownia - Pierwsze Piętro';
        $printer->raw_languages_supported = ['microline'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('Local/OKI_ML3320');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://TM_T88V';
        $printer->name = 'Epson TM-T88V (CUPS)';
        $printer->location = 'Hol-Serwerownia - Pierwsze Piętro';
        $printer->raw_languages_supported = ['escpos'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('Local/TM_T88V');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://HP_LaserJet_400';
        $printer->name = 'HP LaserJet 400 (CUPS)';
        $printer->location = 'Agnieszka - Pierwsze Piętro';
        $printer->raw_languages_supported = ['pcl', 'postscript'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('Local/HP_LaserJet_400');
        $server->Printers()->save($printer);
    }

    protected function AddLanPrinters(PrintServer $server)
    {
        $printer = new Printer();
        $printer->uri = 'cups://CLJ400';
        $printer->name = 'HP Color LaserJet 400 (M452dn)';
        $printer->location = 'Serwerownia - Pierwsze Piętro';
        $printer->raw_languages_supported = ['pcl', 'postscript'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('LAN/CLJ400');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://LJ400';
        $printer->name = 'HP LaserJet 400 (M401dn)';
        $printer->location = 'Agnieszka - Pierwsze Piętro';
        $printer->raw_languages_supported = ['pcl', 'postscript'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('LAN/LJ400');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://OJP8500';
        $printer->name = 'HP Officejet Pro 8500 (A910)';
        $printer->location = 'Salon - Drugie Piętro';
        $printer->raw_languages_supported = ['pcl', 'postscript'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('LAN/OJP8500');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://ML320T';
        $printer->name = 'OKI MicroLine 320 Turbo';
        $printer->location = 'Serwerownia - Pierwsze Piętro';
        $printer->raw_languages_supported = ['microline'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('LAN/ML320T');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://ML3320';
        $printer->name = 'OKI MicroLine 3320';
        $printer->location = 'Serwerownia - Pierwsze Piętro';
        $printer->raw_languages_supported = ['microline'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('LAN/ML3320');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://ML3321';
        $printer->name = 'OKI MicroLine 3321';
        $printer->location = 'Serwerownia - Pierwsze Piętro';
        $printer->raw_languages_supported = ['microline'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('LAN/ML3321');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://TM-T88V';
        $printer->name = 'Epson TM-T88V';
        $printer->location = 'Hol-Serwerownia - Pierwsze Piętro';
        $printer->raw_languages_supported = ['escpos'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('LAN/TM-T88V');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://ZD410';
        $printer->name = 'Zebra ZD410';
        $printer->location = 'Serwerownia - Pierwsze Piętro';
        $printer->raw_languages_supported = ['zpl'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('LAN/ZD410');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://ZD420C';
        $printer->name = 'Zebra ZD420C (Cartridge)';
        $printer->location = 'Serwerownia - Pierwsze Piętro';
        $printer->raw_languages_supported = ['zpl'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('LAN/ZD420C');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://ZD420T';
        $printer->name = 'Zebra ZD420T (Ribbon)';
        $printer->location = 'Serwerownia - Pierwsze Piętro';
        $printer->raw_languages_supported = ['zpl'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('LAN/ZD420T');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://ZQ520';
        $printer->name = 'Zebra ZQ520 (WiFi)';
        $printer->location = 'Urządzenie Przenośne';
        $printer->raw_languages_supported = ['zpl'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('LAN/ZQ520');
        $server->Printers()->save($printer);

        $printer = new Printer();
        $printer->uri = 'cups://PDF';
        $printer->name = 'Print To PDF';
        $printer->raw_languages_supported = ['pdf'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = $this->readPpdJsonFile('LAN/PDF');
        $server->Printers()->save($printer);
    }

    protected function readPpdJsonFile(string $name): array
    {
        $json = file_get_contents(sprintf('%s/PPD_OPTIONS/%s.json', __DIR__, $name));

        return json_decode($json, true);
    }
}
