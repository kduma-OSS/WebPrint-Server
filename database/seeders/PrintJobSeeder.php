<?php

namespace Database\Seeders;

use App\Models\Printer;
use App\Models\PrintJob;
use Illuminate\Database\Seeder;

class PrintJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $content = 'To jest test!!! - '.date('Y-m-d H:i:s')."\n";

        /** @var Printer $printer */
        $printer = Printer::query()->firstWhere([
            'name' => 'OKI-ML3320 (RAW)',
        ]);

        $job = new PrintJob;
        $job->name = 'Test Print';
        $job->ppd = false;
        $job->content = $content;
        $job->content_file = null;
        $job->file_name = 'test.txt';
        $job->size = strlen($content);
        $printer->Jobs()->save($job);

        $content = 'To jest test CUPS PPD!!! - '.date('Y-m-d H:i:s')."\n";

        /** @var Printer $printer */
        $printer = Printer::query()->firstWhere([
            'name' => 'OKI-ML3320 (CUPS)',
        ]);

        $job = new PrintJob;
        $job->name = 'Test PPD Print';
        $job->ppd = true;
        $job->content = $content;
        $job->content_file = null;
        $job->file_name = 'test2.txt';
        $job->size = strlen($content);
        $printer->Jobs()->save($job);

        $content = 'To jest test Epson TM-T88V!!! - '.date('Y-m-d H:i:s')."\n";

        /** @var Printer $printer */
        $printer = Printer::query()->firstWhere([
            'name' => 'Epson TM-T88V (CUPS)',
        ]);

        $job = new PrintJob;
        $job->name = 'Test PPD 2 Print';
        $job->ppd = true;
        $job->ppd_options = [
            'TmtSpeed' => '1',
            'PageSize' => 'Custom.71.97x80mm',
            'TmtPaperReduction' => 'Off',
            'TmtPaperSource' => 'DocFeedCut',
        ];
        $job->content = $content;
        $job->content_file = null;
        $job->file_name = 'test3.txt';
        $job->size = strlen($content);
        $printer->Jobs()->save($job);

        PrintJob::query()->update(['printer_id' => 1]);
    }
}
