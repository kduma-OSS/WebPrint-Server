<?php

namespace Database\Seeders;

use App\Models\Printer;
use App\Models\PrintServer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PrinterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $server = new PrintServer;
        $server->name = 'Debug Print Server';
        $server->save();
        $token = $server->tokens()->create([
            'name' => sprintf("%s Access Token", $server->name),
            'token' => hash('sha256', $plainTextToken = 'DEBUG_WEBPRINT_SERVICE_KEY'),
            'abilities' => ['*'],
        ]);
        echo sprintf("\n\n\n%s=\"%s|%s\"\n\n\n", strtoupper(Str::slug(sprintf("%s Access Token", $server->name), '_')), $token->id, $plainTextToken);
        $this->AddDebugPrinters($server);



        $server = new PrintServer;
        $server->name = 'Local Print Server';
        $server->save();
        $token = $server->createToken(sprintf("%s Access Token", $server->name));
        echo sprintf("\n\n\n%s=\"%s\"\n\n\n", strtoupper(Str::slug($token->accessToken->name, '_')), $token->plainTextToken);
        $this->AddLocalPrinters($server);



        $server = new PrintServer;
        $server->name = 'LAN Print Server';
        $server->save();
        $token = $server->createToken(sprintf("%s Access Token", $server->name));
        echo sprintf("\n\n\n%s=\"%s\"\n\n\n", strtoupper(Str::slug($token->accessToken->name, '_')), $token->plainTextToken);
        $this->AddLanPrinters($server);
    }


    protected function AddDebugPrinters(PrintServer $server)
    {
        $printer = new Printer;
        $printer->uri = 'debug://debug';
        $printer->name = 'DEBUG';
        $printer->raw_languages_supported = ['*'];
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'Letter' => [
                        'name' => 'US Letter',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Legal' => [
                        'name' => 'US Legal',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'A4' => [
                        'name' => 'A4',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'FanFoldUS' => [
                        'name' => 'US Fanfold',
                        'order' => 4,
                        'enabled' => true
                    ]
                ],
                'default' => 'A4',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Resolution' => [
                'name' => 'Resolution',
                'values' => [
                    '60x72dpi' => [
                        'name' => '60x72dpi',
                        'order' => 1,
                        'enabled' => true
                    ],
                    '120x72dpi' => [
                        'name' => '120x72dpi',
                        'order' => 2,
                        'enabled' => true
                    ],
                    '240x72dpi' => [
                        'name' => '240x72dpi',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => '240x72dpi',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ]
        ];
        $server->Printers()->save($printer);
    }

    protected function AddLocalPrinters(PrintServer $server)
    {
        $printer = new Printer;
        $printer->uri = 'lpd://192.168.9.20/OKI-ML3320?timeout=10&tries=10';
        $printer->name = 'OKI-ML3320 (LPD)';
        $printer->raw_languages_supported = ['microline'];
        $printer->enabled = true;
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'socket://192.168.9.20:9101?timeout=10';
        $printer->name = 'OKI-ML3320 (RAW)';
        $printer->raw_languages_supported = ['microline'];
        $printer->enabled = true;
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://OKI_ML3320';
        $printer->name = 'OKI-ML3320 (CUPS)';
        $printer->raw_languages_supported = ['microline'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'Letter' => [
                        'name' => 'US Letter',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Legal' => [
                        'name' => 'US Legal',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'A4' => [
                        'name' => 'A4',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'FanFoldUS' => [
                        'name' => 'US Fanfold',
                        'order' => 4,
                        'enabled' => true
                    ]
                ],
                'default' => 'A4',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Resolution' => [
                'name' => 'Resolution',
                'values' => [
                    '60x72dpi' => [
                        'name' => '60x72dpi',
                        'order' => 1,
                        'enabled' => true
                    ],
                    '120x72dpi' => [
                        'name' => '120x72dpi',
                        'order' => 2,
                        'enabled' => true
                    ],
                    '240x72dpi' => [
                        'name' => '240x72dpi',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => '240x72dpi',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ]
        ];
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://TM_T88V';
        $printer->name = 'Epson TM-T88V (CUPS)';
        $printer->raw_languages_supported = ['escpos'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'RP80x297' => [
                        'name' => 'Roll Paper 80 x 297 mm',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'RP58x297' => [
                        'name' => 'Roll Paper 58 x 297 mm',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'A4' => [
                        'name' => 'A4',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'Letter' => [
                        'name' => 'Letter',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'Legal' => [
                        'name' => 'Legal',
                        'order' => 5,
                        'enabled' => true
                    ]
                ],
                'default' => 'RP80x297',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Resolution' => [
                'name' => 'Resolution',
                'values' => [
                    '180x180dpi' => [
                        'name' => '180 x 180 dpi',
                        'order' => 1,
                        'enabled' => true
                    ]
                ],
                'default' => '180x180dpi',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'TmtSpeed' => [
                'name' => 'Printing Speed',
                'values' => [
                    'Auto' => [
                        'name' => 'Use the printer settings ',
                        'order' => 1,
                        'enabled' => true
                    ],
                    1 => [
                        'name' => '1st (Fast)',
                        'order' => 2,
                        'enabled' => true
                    ],
                    2 => [
                        'name' => '2nd',
                        'order' => 3,
                        'enabled' => true
                    ],
                    3 => [
                        'name' => '3rd',
                        'order' => 4,
                        'enabled' => true
                    ],
                    4 => [
                        'name' => '4th (Slow)',
                        'order' => 5,
                        'enabled' => true
                    ]
                ],
                'default' => '4',
                'enabled' => true,
                'order' => 3,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'TmtPaperReduction' => [
                'name' => 'Paper Reduction',
                'values' => [
                    'Off' => [
                        'name' => 'None',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Top' => [
                        'name' => 'Top margin',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Bottom' => [
                        'name' => 'Bottom margin',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'Both' => [
                        'name' => 'Top & Bottom margins',
                        'order' => 4,
                        'enabled' => true
                    ]
                ],
                'default' => 'Both',
                'enabled' => true,
                'order' => 4,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'TmtPaperSource' => [
                'name' => 'Paper Source',
                'values' => [
                    'DocFeedCut' => [
                        'name' => 'Document [Feed, Cut]',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'DocFeedNoCut' => [
                        'name' => 'Document [Feed, NoCut]',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'DocNoFeedCut' => [
                        'name' => 'Document [NoFeed, Cut]',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'DocNoFeedNoCut' => [
                        'name' => 'Document [NoFeed, NoCut]',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'PageFeedCut' => [
                        'name' => 'Page [Feed, Cut]',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'PageFeedNoCut' => [
                        'name' => 'Page [Feed, NoCut]',
                        'order' => 6,
                        'enabled' => true
                    ],
                    'PageNoFeedCut' => [
                        'name' => 'Page [NoFeed, Cut]',
                        'order' => 7,
                        'enabled' => true
                    ]
                ],
                'default' => 'DocFeedCut',
                'enabled' => true,
                'order' => 5,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'TmtBuzzerControl' => [
                'name' => 'Buzzer',
                'values' => [
                    'Off' => [
                        'name' => 'Not used',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Before' => [
                        'name' => 'Sounds before printing',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'After' => [
                        'name' => 'Sounds after printing',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Off',
                'enabled' => true,
                'order' => 6,
                'group_key' => 'Buzzer Control',
                'group_name' => 'Buzzer Control'
            ],
            'TmtSoundPattern' => [
                'name' => 'Sound Pattern',
                'values' => [
                    'Internal' => [
                        'name' => 'Internal buzzer',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'A' => [
                        'name' => 'Option buzzer (Pattern A)',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'B' => [
                        'name' => 'Option buzzer (Pattern B)',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'C' => [
                        'name' => 'Option buzzer (Pattern C)',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'D' => [
                        'name' => 'Option buzzer (Pattern D)',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'E' => [
                        'name' => 'Option buzzer (Pattern E)',
                        'order' => 6,
                        'enabled' => true
                    ]
                ],
                'default' => 'Internal',
                'enabled' => true,
                'order' => 7,
                'group_key' => 'Buzzer Control',
                'group_name' => 'Buzzer Control'
            ],
            'TmtBuzzerRepeat' => [
                'name' => 'Buzzer Repeat',
                'values' => [
                    1 => [
                        'name' => '1',
                        'order' => 1,
                        'enabled' => true
                    ],
                    2 => [
                        'name' => '2',
                        'order' => 2,
                        'enabled' => true
                    ],
                    3 => [
                        'name' => '3',
                        'order' => 3,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 4,
                        'enabled' => true
                    ]
                ],
                'default' => '1',
                'enabled' => true,
                'order' => 8,
                'group_key' => 'Buzzer Control',
                'group_name' => 'Buzzer Control'
            ],
            'TmtDrawer1' => [
                'name' => 'Cash Drawer #1',
                'values' => [
                    'Off' => [
                        'name' => 'Does not open',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Before' => [
                        'name' => 'Open before printing',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'After' => [
                        'name' => 'Open after printing',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Off',
                'enabled' => true,
                'order' => 9,
                'group_key' => 'Cash Drawer Control',
                'group_name' => 'Cash Drawer Control'
            ],
            'TmtDrawer2' => [
                'name' => 'Cash Drawer #2',
                'values' => [
                    'Off' => [
                        'name' => 'Does not open',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Before' => [
                        'name' => 'Open before printing',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'After' => [
                        'name' => 'Open after printing',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Off',
                'enabled' => true,
                'order' => 10,
                'group_key' => 'Cash Drawer Control',
                'group_name' => 'Cash Drawer Control'
            ]
        ];
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://HP_LaserJet_400';
        $printer->name = 'HP LaserJet 400 (CUPS)';
        $printer->raw_languages_supported = ['pcl', 'postscript'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'Letter' => 'US Letter',
                    'Legal' => 'US Legal',
                    'Executive' => 'Executive',
                    'FanFoldGermanLegal' => '8.5x13',
                    'A4' => 'A4',
                    'A5' => 'A5',
                    'A6' => 'A6',
                    'B5' => 'JIS B5',
                    '195x270mm' => '16K 195x270 mm',
                    '184x260mm' => '16K 184x260 mm',
                    '7.75x10.75' => '16K 197x273 mm',
                    'Postcard' => 'Postcard',
                    'DoublePostcardRotated' => 'Postcard Double Long Edge',
                    'Env10' => 'Envelope #10',
                    'EnvMonarch' => 'Envelope Monarch',
                    'EnvISOB5' => 'Envelope B5',
                    'EnvC5' => 'Envelope C5',
                    'EnvDL' => 'Envelope DL',
                    'Custom.WIDTHxHEIGHT' => 'Custom',
                ],
                'default' => 'A4',
            ],
            'InputSlot' => [
                'name' => 'Paper Source',
                'values' => [
                    'Tray1' => 'Tray 1',
                    'Tray2' => 'Tray 2',
                    'Tray3' => 'Tray 3',
                    'Tray1_Man' => 'Tray 1 (Manual)',
                ],
                'default' => 'Tray1',
            ],
            'Duplex' => [
                'name' => 'Two-Sided',
                'values' => [
                    'None' => 'Off',
                    'DuplexNoTumble' => 'Long-Edge Binding',
                    'DuplexTumble' => 'Short-Edge Binding',
                ],
                'default' => 'None',
            ],
            'MediaType' => [
                'name' => '',
                'values' => [
                    'Unspecified' => 'Unspecified',
                    'Plain' => 'Plain',
                    'HPEcoSMARTLite' => 'HP EcoSMART Lite',
                    'Light6074' => 'Light 60-74g',
                    'MidWeight96110' => 'Mid-Weight 96-110g',
                    'Heavy111130' => 'Heavy 111-130g',
                    'ExtraHeavy131175' => 'Extra Heavy 131-175g',
                    'MonochromeLaserTransparency' => 'Monochrome Laser Transparency',
                    'Labels' => 'Labels',
                    'Letterhead' => 'Letterhead',
                    'Envelope' => 'Envelope',
                    'Preprinted' => 'Preprinted',
                    'Prepunched' => 'Prepunched',
                    'Colored' => 'Colored',
                    'Bond' => 'Bond',
                    'Recycled' => 'Recycled',
                    'Rough' => 'Rough',
                ],
                'default' => 'Unspecified',
            ],
            'HPPrintQuality' => [
                'name' => 'Print Quality',
                'values' => [
                    'FastRes1200' => 'FastRes 1200',
                    '600dpi' => '600 dpi',
                    'ProRes1200' => 'ProRes 1200',
                ],
                'default' => 'FastRes1200',
            ],
            'HPOption_Duplexer' => [
                'name' => 'Duplex Unit',
                'values' => [
                    'True' => 'True',
                    'False' => 'False',
                ],
                'default' => 'True',
            ],
            'HPOption_Tray3' => [
                'name' => 'Tray 3',
                'values' => [
                    'False' => 'False',
                    'True' => 'True',
                ],
                'default' => 'True',
            ],
            'HPManualDuplexSwitch' => [
                'name' => 'Manual Duplex',
                'values' => [

                    'True' => 'On',
                    'False' => 'Off',
                ],
                'default' => 'False',
            ],
            'HPManualDuplexPrintGuide' => [
                'name' => 'Print Reinsertion Guide',
                'values' => [
                    'True' => 'On',
                    'False' => 'Off',
                ],
                'default' => 'False',
            ],
            'HPManualDuplexOrientation' => [
                'name' => 'Binding',
                'values' => [
                    'DuplexNoTumble' => 'Long-Edge Binding',
                    'DuplexTumble' => 'Short-Edge Binding',
                ],
                'default' => 'DuplexNoTumble',
            ],
            'HPEconoMode' => [
                'name' => 'EconoMode',
                'values' => [
                    'True' => 'On',
                    'False' => 'Off',
                ],
                'default' => 'False',
            ],
            'HPBookletPageSize' => [
                'name' => 'Paper for Booklet',
                'values' => [
                    '184x260mm' => '16K 184x260 mm',
                    '195x270mm' => '16K 195x270 mm',
                    '7.75x10.75' => '16K 197x273 mm',
                    'A4' => 'A4',
                    'A5' => 'A5',
                    'A6' => 'A6',
                    'B5' => 'B5 (JIS)',
                    'DoublePostcardRotated' => 'Double Postcard (JIS)',
                    'Executive' => 'Executive',
                    'FanFoldGermanLegal' => '8.5x13',
                    'Legal' => 'US Legal',
                    'Letter' => 'US Letter',
                    'Postcard' => 'Postcard (JIS)',
                ],
                'default' => 'A4',
            ],
            'HPwmSwitch' => [
                'name' => 'Watermark',
                'values' => [
                    'True' => 'On',
                    'False' => 'Off',
                ],
                'default' => 'False',
            ],
            'HPwmPages' => [
                'name' => 'Pages',
                'values' => [
                    'AllPages' => 'All Pages',
                    'FirstPage' => 'First Only',
                ],
                'default' => 'AllPages',
            ],
            'HPwmTextMessage' => [
                'name' => 'Text',
                'values' => [
                    'Draft' => 'Draft',
                    'CompanyConfidential' => 'Company Confidential',
                    'CompanyProprietary' => 'Company Proprietary',
                    'CompanyPrivate' => 'Company Private',
                    'Confidential' => 'Confidential',
                    'Copy' => 'Copy',
                    'Copyright' => 'Copyright',
                    'FileCopy' => 'File Copy',
                    'Final' => 'Final',
                    'ForInternalUse' => 'For Internal Use Only',
                    'Preliminary' => 'Preliminary',
                    'Proof' => 'Proof',
                    'ReviewCopy' => 'Review Copy',
                    'Sample' => 'Sample',
                    'TopSecret' => 'Top Secret',
                    'Urgent' => 'Urgent',
                    'Custom' => 'Text',
                ],
                'default' => 'Draft',
            ],
            'HPwmFontName' => [
                'name' => 'Font',
                'values' => [
                    'CourierB' => 'Courier Bold',
                    'HelveticaB' => 'Helvetica Bold',
                    'TimesB' => 'Times Bold',
                ],
                'default' => 'HelveticaB',
            ],
            'HPwmFontSize' => [
                'name' => 'Size',
                'values' => [
                    'pt24' => '24',
                    'pt30' => '30',
                    'pt36' => '36',
                    'pt42' => '42',
                    'pt48' => '48',
                    'pt54' => '54',
                    'pt60' => '60',
                    'pt66' => '66',
                    'pt72' => '72',
                    'pt78' => '78',
                    'pt84' => '84',
                    'pt90' => '90',
                ],
                'default' => 'pt48',
            ],
            'HPwmTextAngle' => [
                'name' => 'Angle',
                'values' => [
                    'Deg90' => '90 degrees',
                    'Deg75' => '75 degrees',
                    'Deg60' => '60 degrees',
                    'Deg45' => '45 degrees',
                    'Deg30' => '30 degrees',
                    'Deg15' => '15 degrees',
                    'Deg0' => '0 degrees',
                    'DegN15' => '-15 degrees',
                    'DegN30' => '-30 degrees',
                    'DegN45' => '-45 degrees',
                    'DegN60' => '-60 degrees',
                    'DegN75' => '-75 degrees',
                    'DegN90' => '-90 degrees',
                ],
                'default' => 'Deg45',
            ],
            'HPwmTextStyle' => [
                'name' => 'Style',
                'values' => [
                    'Thin' => 'Thin Outline',
                    'Medium' => 'Medium Outline',
                    'Thick' => 'Thick Outline',
                    'Halo' => 'Thick Outline with Halo',
                    'Fill' => 'Filled',
                ],
                'default' => 'Medium',
            ],
            'HPwmBrightness' => [
                'name' => 'Intensity',
                'values' => [
                    'Darkest' => 'Darkest',
                    'Darker' => 'Very Dark',
                    'Dark' => 'Dark',
                    'MediumDark' => 'Medium Dark',
                    'Medium' => 'Medium',
                    'MediumLight' => 'Medium Light',
                    'Light' => 'Light',
                    'Lighter' => 'Very Light',
                    'Lightest' => 'Lightest',
                ],
                'default' => 'Medium',
            ],
            'HPBookletFilter' => [
                'name' => 'Format Output as Booklet',
                'values' => [
                    'True' => 'On',
                    'False' => 'Off',
                ],
                'default' => 'False',
            ],
            'HPBookletPageOrder' => [
                'name' => 'Bind Pages on the Right',
                'values' => [
                    'True' => 'On',
                    'False' => 'Off',
                ],
                'default' => 'False',
            ],
            'HPBookletBackCover' => [
                'name' => 'Last Page Is Back Cover',
                'values' => [
                    'True' => 'On',
                    'False' => 'Off',
                ],
                'default' => 'False',
            ],
            'HPBookletScaling' => [
                'name' => 'Scaling',
                'values' => [
                    'Proportional' => 'Proportional',
                    'FitPage' => 'To Fit Page Size',
                ],
                'default' => 'Proportional',
            ],
        ];
        $server->Printers()->save($printer);
    }

    protected function AddLanPrinters(PrintServer $server)
    {
        $printer = new Printer;
        $printer->uri = 'cups://CLJ400';
        $printer->name = 'HP Color LaserJet 400 (M452dn)';
        $printer->raw_languages_supported = ['pcl', 'postscript'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'Letter' => [
                        'name' => 'US Letter',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Legal' => [
                        'name' => 'US Legal',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Executive' => [
                        'name' => 'Executive',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'FanFoldGermanLegal' => [
                        'name' => 'Oficio 8.5 x 13',
                        'order' => 4,
                        'enabled' => true
                    ],
                    '4x6' => [
                        'name' => '4 x 6',
                        'order' => 5,
                        'enabled' => true
                    ],
                    '5x8' => [
                        'name' => '5 x 8',
                        'order' => 6,
                        'enabled' => true
                    ],
                    'A4' => [
                        'name' => 'A4',
                        'order' => 7,
                        'enabled' => true
                    ],
                    'A5' => [
                        'name' => 'A5',
                        'order' => 8,
                        'enabled' => true
                    ],
                    'A6' => [
                        'name' => 'A6',
                        'order' => 9,
                        'enabled' => true
                    ],
                    'B5' => [
                        'name' => 'JIS B5',
                        'order' => 10,
                        'enabled' => true
                    ],
                    'B6' => [
                        'name' => 'JIS B6',
                        'order' => 11,
                        'enabled' => true
                    ],
                    'Env4x6' => [
                        'name' => '10 x 15 cm',
                        'order' => 12,
                        'enabled' => true
                    ],
                    'Oficio' => [
                        'name' => 'Oficio 216 x 340 mm',
                        'order' => 13,
                        'enabled' => true
                    ],
                    '195x270mm' => [
                        'name' => '16K 195 x 270 mm',
                        'order' => 14,
                        'enabled' => true
                    ],
                    '184x260mm' => [
                        'name' => '16K 184 x 260 mm',
                        'order' => 15,
                        'enabled' => true
                    ],
                    '7.75x10.75' => [
                        'name' => '16K 197 x 273 mm',
                        'order' => 16,
                        'enabled' => true
                    ],
                    'Postcard' => [
                        'name' => 'Postcard',
                        'order' => 17,
                        'enabled' => true
                    ],
                    'DoublePostcardRotated' => [
                        'name' => 'Postcard Double Long Edge',
                        'order' => 18,
                        'enabled' => true
                    ],
                    'Env10' => [
                        'name' => 'Envelope #10',
                        'order' => 19,
                        'enabled' => true
                    ],
                    'EnvMonarch' => [
                        'name' => 'Envelope Monarch',
                        'order' => 20,
                        'enabled' => true
                    ],
                    'EnvISOB5' => [
                        'name' => 'Envelope B5',
                        'order' => 21,
                        'enabled' => true
                    ],
                    'EnvC5' => [
                        'name' => 'Envelope C5',
                        'order' => 22,
                        'enabled' => true
                    ],
                    'EnvDL' => [
                        'name' => 'Envelope DL',
                        'order' => 23,
                        'enabled' => true
                    ]
                ],
                'default' => 'A4',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Duplex' => [
                'name' => 'Two-Sided',
                'values' => [
                    'None' => [
                        'name' => 'Off',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'DuplexNoTumble' => [
                        'name' => 'Long-Edge Binding',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'DuplexTumble' => [
                        'name' => 'Short-Edge Binding',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'None',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Collate' => [
                'name' => 'Collate',
                'values' => [
                    'True' => [
                        'name' => 'On',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'False' => [
                        'name' => 'Off',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'False',
                'enabled' => true,
                'order' => 3,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'InputSlot' => [
                'name' => 'Paper Feed',
                'values' => [
                    'Auto' => [
                        'name' => 'Automatic',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Tray1' => [
                        'name' => 'Tray 1',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Tray2' => [
                        'name' => 'Tray 2',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'Tray3' => [
                        'name' => 'Tray 3',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'ManualFeed' => [
                        'name' => 'Manual Feed',
                        'order' => 5,
                        'enabled' => true
                    ]
                ],
                'default' => 'Auto',
                'enabled' => true,
                'order' => 4,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'HPOption_Duplexer' => [
                'name' => 'Duplex Unit',
                'values' => [
                    'True' => [
                        'name' => 'On',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'False' => [
                        'name' => 'Off',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'True',
                'enabled' => false,
                'order' => 5,
                'group_key' => 'InstallableOptions',
                'group_name' => 'Installable Options'
            ],
            'HPOption_Tray3' => [
                'name' => 'Tray 3',
                'values' => [
                    'False' => [
                        'name' => 'False',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'True' => [
                        'name' => 'True',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'True',
                'enabled' => false,
                'order' => 6,
                'group_key' => 'InstallableOptions',
                'group_name' => 'Installable Options'
            ],
            'MediaType' => [
                'name' => 'Media Type',
                'values' => [
                    'Unspecified' => [
                        'name' => 'Unspecified',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Plain' => [
                        'name' => 'Plain',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'HPEcoFFICIENT' => [
                        'name' => 'HP EcoFFICIENT',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'HPLaserJet90' => [
                        'name' => 'HP LaserJet 90g',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'HPColorLaserMatte105' => [
                        'name' => 'HP Color Laser Matte 105g',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'HPPremiumChoiceMatte120' => [
                        'name' => 'HP Premium Choice Matte 120g',
                        'order' => 6,
                        'enabled' => true
                    ],
                    'HPBrochureMatte150' => [
                        'name' => 'HP Brochure Matte 150g',
                        'order' => 7,
                        'enabled' => true
                    ],
                    'HPCoverMatte200' => [
                        'name' => 'HP Cover Matte 200g',
                        'order' => 8,
                        'enabled' => true
                    ],
                    'HPMattePhoto200' => [
                        'name' => 'HP Matte Photo 200g',
                        'order' => 9,
                        'enabled' => true
                    ],
                    'HPPremiumPresentationGlossy120' => [
                        'name' => 'HP Premium Presentation Glossy 120g',
                        'order' => 10,
                        'enabled' => true
                    ],
                    'HPBrochureGlossy150' => [
                        'name' => 'HP Brochure Glossy 150g',
                        'order' => 11,
                        'enabled' => true
                    ],
                    'HPTrifoldBrochureGlossy150' => [
                        'name' => 'HP Tri-fold Brochure Glossy 150g',
                        'order' => 12,
                        'enabled' => true
                    ],
                    'HPBrochureGlossy200' => [
                        'name' => 'HP Brochure Glossy 200g',
                        'order' => 13,
                        'enabled' => true
                    ],
                    'Light6074' => [
                        'name' => 'Light 60-74g',
                        'order' => 14,
                        'enabled' => true
                    ],
                    'Intermediate8595' => [
                        'name' => 'Intermediate 85-95g',
                        'order' => 15,
                        'enabled' => true
                    ],
                    'MidWeight96110' => [
                        'name' => 'Mid-Weight 96-110g',
                        'order' => 16,
                        'enabled' => true
                    ],
                    'Heavy111130' => [
                        'name' => 'Heavy 111-130g',
                        'order' => 17,
                        'enabled' => true
                    ],
                    'ExtraHeavy131175' => [
                        'name' => 'Extra Heavy 131-175g',
                        'order' => 18,
                        'enabled' => true
                    ],
                    'Cardstock176220' => [
                        'name' => 'Cardstock 176-220g',
                        'order' => 19,
                        'enabled' => true
                    ],
                    'HeavyGlossy111130' => [
                        'name' => 'Heavy Glossy 111-130g',
                        'order' => 20,
                        'enabled' => true
                    ],
                    'ExtraHeavyGlossy131175' => [
                        'name' => 'Extra Heavy Glossy 131-175g',
                        'order' => 21,
                        'enabled' => true
                    ],
                    'CardGlossy176220' => [
                        'name' => 'Card Glossy 176-220g',
                        'order' => 22,
                        'enabled' => true
                    ],
                    'ColorLaserTransparency' => [
                        'name' => 'Color Laser Transparency',
                        'order' => 23,
                        'enabled' => true
                    ],
                    'Labels' => [
                        'name' => 'Labels',
                        'order' => 24,
                        'enabled' => true
                    ],
                    'Letterhead' => [
                        'name' => 'Letterhead',
                        'order' => 25,
                        'enabled' => true
                    ],
                    'Envelope' => [
                        'name' => 'Envelope',
                        'order' => 26,
                        'enabled' => true
                    ],
                    'HeavyEnvelope' => [
                        'name' => 'Heavy Envelope',
                        'order' => 27,
                        'enabled' => true
                    ],
                    'Preprinted' => [
                        'name' => 'Preprinted',
                        'order' => 28,
                        'enabled' => true
                    ],
                    'Prepunched' => [
                        'name' => 'Prepunched',
                        'order' => 29,
                        'enabled' => true
                    ],
                    'Colored' => [
                        'name' => 'Colored',
                        'order' => 30,
                        'enabled' => true
                    ],
                    'Bond' => [
                        'name' => 'Bond',
                        'order' => 31,
                        'enabled' => true
                    ],
                    'Recycled' => [
                        'name' => 'Recycled',
                        'order' => 32,
                        'enabled' => true
                    ],
                    'Rough' => [
                        'name' => 'Rough',
                        'order' => 33,
                        'enabled' => true
                    ],
                    'HeavyRough' => [
                        'name' => 'Heavy Rough',
                        'order' => 34,
                        'enabled' => true
                    ],
                    'OpaqueFilm' => [
                        'name' => 'Opaque Film',
                        'order' => 35,
                        'enabled' => true
                    ]
                ],
                'default' => 'Unspecified',
                'enabled' => true,
                'order' => 7,
                'group_key' => 'HPPaperQualityPanel',
                'group_name' => 'Paper/Quality'
            ],
            'HPPJLEconoMode2' => [
                'name' => 'EconoMode',
                'values' => [
                    'yes' => [
                        'name' => 'On',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'no' => [
                        'name' => 'Off',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'no',
                'enabled' => true,
                'order' => 8,
                'group_key' => 'HPPaperQualityPanel',
                'group_name' => 'Paper/Quality'
            ],
            'HPEdgeControl' => [
                'name' => 'Edge Control',
                'values' => [
                    'HPEdgeControlOff' => [
                        'name' => 'Off',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Light' => [
                        'name' => 'Light',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Normal' => [
                        'name' => 'Normal',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'Max' => [
                        'name' => 'Maximum',
                        'order' => 4,
                        'enabled' => true
                    ]
                ],
                'default' => 'Normal',
                'enabled' => true,
                'order' => 9,
                'group_key' => 'HPColorOptionsPanel',
                'group_name' => 'Color'
            ],
            'HPGeneralHalftone' => [
                'name' => 'Halftone',
                'values' => [
                    'Smooth' => [
                        'name' => 'Smooth',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Detail' => [
                        'name' => 'Detail',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'Smooth',
                'enabled' => true,
                'order' => 10,
                'group_key' => 'HPColorOptionsPanel',
                'group_name' => 'Color'
            ],
            'HPTextNeutralGrays' => [
                'name' => 'Text Neutral Grays',
                'values' => [
                    'Black' => [
                        'name' => 'Black Only',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'ProcessBlack' => [
                        'name' => '4-Color',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'Black',
                'enabled' => true,
                'order' => 11,
                'group_key' => 'HPColorOptionsPanel',
                'group_name' => 'Color'
            ],
            'HPGraphicsNeutralGrays' => [
                'name' => 'Graphics Neutral Grays',
                'values' => [
                    'Black' => [
                        'name' => 'Black Only',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'ProcessBlack' => [
                        'name' => '4-Color',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'Black',
                'enabled' => true,
                'order' => 12,
                'group_key' => 'HPColorOptionsPanel',
                'group_name' => 'Color'
            ],
            'HPPhotoNeutralGrays' => [
                'name' => 'Photo Neutral Grays',
                'values' => [
                    'Black' => [
                        'name' => 'Black Only',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'ProcessBlack' => [
                        'name' => '4-Color',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'ProcessBlack',
                'enabled' => true,
                'order' => 13,
                'group_key' => 'HPColorOptionsPanel',
                'group_name' => 'Color'
            ],
            'HPPJLColorAsGray' => [
                'name' => 'Print Color as Gray',
                'values' => [
                    'on' => [
                        'name' => 'On',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'off' => [
                        'name' => 'Off',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'off',
                'enabled' => true,
                'order' => 14,
                'group_key' => 'HPColorOptionsPanel',
                'group_name' => 'Color'
            ],
            'HPRGBEmulation' => [
                'name' => 'RGB Color',
                'values' => [
                    'DefaultSRGB' => [
                        'name' => 'Default (sRGB)',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'PhotoSRGB' => [
                        'name' => 'Photo (sRGB)',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Adobe' => [
                        'name' => 'Photo (Adobe RGB 1998)',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'VividSRGB' => [
                        'name' => 'Vivid (sRGB)',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'HPRGBEmulationNone' => [
                        'name' => 'None',
                        'order' => 5,
                        'enabled' => true
                    ]
                ],
                'default' => 'DefaultSRGB',
                'enabled' => true,
                'order' => 15,
                'group_key' => 'HPColorOptionsPanel',
                'group_name' => 'Color'
            ]
        ];
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://LJ400';
        $printer->name = 'HP LaserJet 400 (M401dn)';
        $printer->raw_languages_supported = ['pcl', 'postscript'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'Letter' => [
                        'name' => 'US Letter',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Legal' => [
                        'name' => 'US Legal',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Executive' => [
                        'name' => 'Executive',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'FanFoldGermanLegal' => [
                        'name' => '8.5x13',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'A4' => [
                        'name' => 'A4',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'A5' => [
                        'name' => 'A5',
                        'order' => 6,
                        'enabled' => true
                    ],
                    'A6' => [
                        'name' => 'A6',
                        'order' => 7,
                        'enabled' => true
                    ],
                    'B5' => [
                        'name' => 'JIS B5',
                        'order' => 8,
                        'enabled' => true
                    ],
                    '195x270mm' => [
                        'name' => '16K 195x270 mm',
                        'order' => 9,
                        'enabled' => true
                    ],
                    '7.25x10.2361' => [
                        'name' => '16K 184x260 mm',
                        'order' => 10,
                        'enabled' => true
                    ],
                    '7.75x10.75' => [
                        'name' => '16K 197x273 mm',
                        'order' => 11,
                        'enabled' => true
                    ],
                    'Postcard' => [
                        'name' => 'Postcard',
                        'order' => 12,
                        'enabled' => true
                    ],
                    'DoublePostcardRotated' => [
                        'name' => 'Postcard Double Long Edge',
                        'order' => 13,
                        'enabled' => true
                    ],
                    'Env10' => [
                        'name' => 'Envelope #10',
                        'order' => 14,
                        'enabled' => true
                    ],
                    'EnvMonarch' => [
                        'name' => 'Envelope Monarch',
                        'order' => 15,
                        'enabled' => true
                    ],
                    'EnvISOB5' => [
                        'name' => 'Envelope B5',
                        'order' => 16,
                        'enabled' => true
                    ],
                    'EnvC5' => [
                        'name' => 'Envelope C5',
                        'order' => 17,
                        'enabled' => true
                    ],
                    'EnvDL' => [
                        'name' => 'Envelope DL',
                        'order' => 18,
                        'enabled' => true
                    ]
                ],
                'default' => 'A4',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'InputSlot' => [
                'name' => 'Paper Source',
                'values' => [
                    'Auto' => [
                        'name' => 'Automatic',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Tray1' => [
                        'name' => 'Tray 1',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Tray2' => [
                        'name' => 'Tray 2',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'Tray3' => [
                        'name' => 'Tray 3',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'Tray1_Man' => [
                        'name' => 'Tray 1 (Manual)',
                        'order' => 5,
                        'enabled' => true
                    ]
                ],
                'default' => 'Auto',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Duplex' => [
                'name' => 'Two-Sided',
                'values' => [
                    'None' => [
                        'name' => 'Off',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'DuplexNoTumble' => [
                        'name' => 'Long-Edge Binding',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'DuplexTumble' => [
                        'name' => 'Short-Edge Binding',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'None',
                'enabled' => true,
                'order' => 3,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'MediaType' => [
                'name' => 'Media Type',
                'values' => [
                    'Unspecified' => [
                        'name' => 'Unspecified',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Plain' => [
                        'name' => 'Plain',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'HPEcoSMARTLite' => [
                        'name' => 'HP EcoSMART Lite',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'Light6074' => [
                        'name' => 'Light 60-74g',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'MidWeight96110' => [
                        'name' => 'Mid-Weight 96-110g',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'Heavy111130' => [
                        'name' => 'Heavy 111-130g',
                        'order' => 6,
                        'enabled' => true
                    ],
                    'ExtraHeavy131175' => [
                        'name' => 'Extra Heavy 131-175g',
                        'order' => 7,
                        'enabled' => true
                    ],
                    'MonochromeLaserTransparency' => [
                        'name' => 'Monochrome Laser Transparency',
                        'order' => 8,
                        'enabled' => true
                    ],
                    'Labels' => [
                        'name' => 'Labels',
                        'order' => 9,
                        'enabled' => true
                    ],
                    'Letterhead' => [
                        'name' => 'Letterhead',
                        'order' => 10,
                        'enabled' => true
                    ],
                    'Envelope' => [
                        'name' => 'Envelope',
                        'order' => 11,
                        'enabled' => true
                    ],
                    'Preprinted' => [
                        'name' => 'Preprinted',
                        'order' => 12,
                        'enabled' => true
                    ],
                    'Prepunched' => [
                        'name' => 'Prepunched',
                        'order' => 13,
                        'enabled' => true
                    ],
                    'Colored' => [
                        'name' => 'Colored',
                        'order' => 14,
                        'enabled' => true
                    ],
                    'Bond' => [
                        'name' => 'Bond',
                        'order' => 15,
                        'enabled' => true
                    ],
                    'Recycled' => [
                        'name' => 'Recycled',
                        'order' => 16,
                        'enabled' => true
                    ],
                    'Rough' => [
                        'name' => 'Rough',
                        'order' => 17,
                        'enabled' => true
                    ]
                ],
                'default' => 'Unspecified',
                'enabled' => true,
                'order' => 4,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'HPPrintQuality' => [
                'name' => 'Print Quality',
                'values' => [
                    'FastRes1200' => [
                        'name' => 'FastRes 1200',
                        'order' => 1,
                        'enabled' => true
                    ],
                    '600dpi' => [
                        'name' => '600 dpi',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'ProRes1200' => [
                        'name' => 'ProRes 1200',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'FastRes1200',
                'enabled' => true,
                'order' => 5,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'HPOption_Duplexer' => [
                'name' => 'Duplex Unit',
                'values' => [
                    'True' => [
                        'name' => 'On',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'False' => [
                        'name' => 'Off',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'True',
                'enabled' => false,
                'order' => 6,
                'group_key' => 'InstallableOptions',
                'group_name' => 'Installable Options'
            ],
            'HPOption_Tray3' => [
                'name' => 'Tray 3',
                'values' => [
                    'False' => [
                        'name' => 'False',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'True' => [
                        'name' => 'True',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'True',
                'enabled' => false,
                'order' => 7,
                'group_key' => 'InstallableOptions',
                'group_name' => 'Installable Options'
            ],
            'HPEconoMode' => [
                'name' => 'EconoMode',
                'values' => [
                    'True' => [
                        'name' => 'On',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'False' => [
                        'name' => 'Off',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'False',
                'enabled' => true,
                'order' => 8,
                'group_key' => 'HPPaperQualityPanel',
                'group_name' => 'Paper/Quality'
            ]
        ];
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://OJP8500';
        $printer->name = 'HP Officejet Pro 8500 (A910)';
        $printer->raw_languages_supported = ['pcl', 'postscript'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'Card3x5' => [
                        'name' => 'Index Card 3x5in',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'L' => [
                        'name' => 'L 89x127mm',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'L.FB' => [
                        'name' => 'L Borderless 89x127mm',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'Hagaki' => [
                        'name' => 'Hagaki 100x148mm',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'Hagaki.FB' => [
                        'name' => 'Hagaki Borderless 100x148mm',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'Card4x6' => [
                        'name' => 'Index Card 4x6in',
                        'order' => 6,
                        'enabled' => true
                    ],
                    'Card4x6.Duplex' => [
                        'name' => 'Index Card AutoDuplex 4x6in',
                        'order' => 7,
                        'enabled' => true
                    ],
                    'Photo4x6' => [
                        'name' => 'Photo 4x6in',
                        'order' => 8,
                        'enabled' => true
                    ],
                    'Photo4x6.FB' => [
                        'name' => 'Photo Borderless 4x6in',
                        'order' => 9,
                        'enabled' => true
                    ],
                    'A6' => [
                        'name' => 'A6 105x148mm',
                        'order' => 10,
                        'enabled' => true
                    ],
                    'A6.FB' => [
                        'name' => 'A6 Borderless 105x148mm',
                        'order' => 11,
                        'enabled' => true
                    ],
                    'A6.Duplex' => [
                        'name' => 'A6 AutoDuplex 105x148mm',
                        'order' => 12,
                        'enabled' => true
                    ],
                    'Photo5x7' => [
                        'name' => 'Photo 5x7in',
                        'order' => 13,
                        'enabled' => true
                    ],
                    'Photo5x7.FB' => [
                        'name' => 'Photo Borderless 5x7in',
                        'order' => 14,
                        'enabled' => true
                    ],
                    'Photo2L' => [
                        'name' => 'Photo 2L 127x178mm',
                        'order' => 15,
                        'enabled' => true
                    ],
                    'Photo2L.FB' => [
                        'name' => 'Photo 2L Borderless 127x178mm',
                        'order' => 16,
                        'enabled' => true
                    ],
                    'Oufuku' => [
                        'name' => 'Oufuku-Hagaki 200x148mm',
                        'order' => 17,
                        'enabled' => true
                    ],
                    'Card5x8' => [
                        'name' => 'Index Card 5x8in',
                        'order' => 18,
                        'enabled' => true
                    ],
                    'Card5x8.Duplex' => [
                        'name' => 'Index Card AutoDuplex 5x8in',
                        'order' => 19,
                        'enabled' => true
                    ],
                    'Statement' => [
                        'name' => 'Statement 5.5x8.5in',
                        'order' => 20,
                        'enabled' => true
                    ],
                    'A5' => [
                        'name' => 'A5 148x210mm',
                        'order' => 21,
                        'enabled' => true
                    ],
                    'A5.FB' => [
                        'name' => 'A5 Borderless 148x210mm',
                        'order' => 22,
                        'enabled' => true
                    ],
                    'A5.Duplex' => [
                        'name' => 'A5 AutoDuplex 148x210mm',
                        'order' => 23,
                        'enabled' => true
                    ],
                    '6x8' => [
                        'name' => '6x8in',
                        'order' => 24,
                        'enabled' => true
                    ],
                    '6x8.Duplex' => [
                        'name' => 'AutoDuplex 6x8in',
                        'order' => 25,
                        'enabled' => true
                    ],
                    'JB5' => [
                        'name' => 'JB5 182x257mm',
                        'order' => 26,
                        'enabled' => true
                    ],
                    'JB5.FB' => [
                        'name' => 'JB5 Borderless 182x257mm',
                        'order' => 27,
                        'enabled' => true
                    ],
                    'JB5.Duplex' => [
                        'name' => 'JB5 AutoDuplex 182x257mm',
                        'order' => 28,
                        'enabled' => true
                    ],
                    'Executive' => [
                        'name' => 'Executive 7.25x10.5in',
                        'order' => 29,
                        'enabled' => true
                    ],
                    'Executive.Duplex' => [
                        'name' => 'Executive AutoDuplex 7.25x10.5in',
                        'order' => 30,
                        'enabled' => true
                    ],
                    '8x10' => [
                        'name' => '8x10in',
                        'order' => 31,
                        'enabled' => true
                    ],
                    '8x10.FB' => [
                        'name' => 'Borderless 8x10in',
                        'order' => 32,
                        'enabled' => true
                    ],
                    'Letter' => [
                        'name' => 'Letter 8.5x11in',
                        'order' => 33,
                        'enabled' => true
                    ],
                    'Letter.FB' => [
                        'name' => 'Letter Borderless 8.5x11in',
                        'order' => 34,
                        'enabled' => true
                    ],
                    'Letter.Duplex' => [
                        'name' => 'Letter AutoDuplex 8.5x11in',
                        'order' => 35,
                        'enabled' => true
                    ],
                    'A4' => [
                        'name' => 'A4 210x297mm',
                        'order' => 36,
                        'enabled' => true
                    ],
                    'A4.FB' => [
                        'name' => 'A4 Borderless 210x297mm',
                        'order' => 37,
                        'enabled' => true
                    ],
                    'A4.Duplex' => [
                        'name' => 'A4 AutoDuplex 210x297mm',
                        'order' => 38,
                        'enabled' => true
                    ],
                    '8.5x13' => [
                        'name' => '8.5x13in',
                        'order' => 39,
                        'enabled' => true
                    ],
                    'Legal' => [
                        'name' => 'Legal 8.5x14in',
                        'order' => 40,
                        'enabled' => true
                    ],
                    'EnvA2' => [
                        'name' => 'A2 Envelope 4.37x5.75in',
                        'order' => 41,
                        'enabled' => true
                    ],
                    'EnvCard' => [
                        'name' => 'Card Envelope 4.4x6in',
                        'order' => 42,
                        'enabled' => true
                    ],
                    'EnvC6' => [
                        'name' => 'C6 Envelope 114x162mm',
                        'order' => 43,
                        'enabled' => true
                    ],
                    'EnvChou4' => [
                        'name' => '#4 Japanese Envelope 90x205mm',
                        'order' => 44,
                        'enabled' => true
                    ],
                    'EnvMonarch' => [
                        'name' => 'Monarch Envelope 3.875x7.5in',
                        'order' => 45,
                        'enabled' => true
                    ],
                    'EnvDL' => [
                        'name' => 'DL Envelope 110x220mm',
                        'order' => 46,
                        'enabled' => true
                    ],
                    'Env10' => [
                        'name' => '#10 Envelope 4.125x9.5in',
                        'order' => 47,
                        'enabled' => true
                    ],
                    'EnvChou3' => [
                        'name' => '#3 Japanese Envelope 120x235mm',
                        'order' => 48,
                        'enabled' => true
                    ],
                    'EnvC5' => [
                        'name' => 'C5 Envelope 162x229mm',
                        'order' => 49,
                        'enabled' => true
                    ]
                ],
                'default' => 'A4',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Duplex' => [
                'name' => 'Double-Sided Printing',
                'values' => [
                    'DuplexNoTumble' => [
                        'name' => 'Long Edge (Standard)',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'DuplexTumble' => [
                        'name' => 'Short Edge (Flip)',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'None' => [
                        'name' => 'Off',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'None',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'InputSlot' => [
                'name' => 'Media Source',
                'values' => [
                    'Auto' => [
                        'name' => 'Auto-Select',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Tray1' => [
                        'name' => 'Tray 1',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Tray2' => [
                        'name' => 'Tray 2',
                        'order' => 3,
                        'enabled' => false
                    ]
                ],
                'default' => 'Auto',
                'enabled' => false,
                'order' => 3,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'ColorModel' => [
                'name' => 'Output Mode',
                'values' => [
                    'RGB' => [
                        'name' => 'Color',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'CMYGray' => [
                        'name' => 'High Quality Grayscale',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'KGray' => [
                        'name' => 'Black Only Grayscale',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'RGB',
                'enabled' => true,
                'order' => 4,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'MediaType' => [
                'name' => 'Media Type',
                'values' => [
                    'Plain' => [
                        'name' => 'Plain Paper',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Glossy' => [
                        'name' => 'Photo Paper',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'TransparencyFilm' => [
                        'name' => 'Transparency Film',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Plain',
                'enabled' => true,
                'order' => 5,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'OutputMode' => [
                'name' => 'Print Quality',
                'values' => [
                    'Normal' => [
                        'name' => 'Normal',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Draft' => [
                        'name' => 'Draft',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Best' => [
                        'name' => 'Best',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'Photo' => [
                        'name' => 'High-Resolution Photo',
                        'order' => 4,
                        'enabled' => true
                    ]
                ],
                'default' => 'Normal',
                'enabled' => true,
                'order' => 6,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'OptionDuplex' => [
                'name' => 'Duplexer Installed',
                'values' => [
                    'False' => [
                        'name' => 'Not Installed',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'True' => [
                        'name' => 'Installed',
                        'order' => 2,
                        'enabled' => true
                    ]
                ],
                'default' => 'True',
                'enabled' => false,
                'order' => 7,
                'group_key' => 'InstallableOptions',
                'group_name' => 'Installable Options'
            ]
        ];
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://ML320T';
        $printer->name = 'OKI MicroLine 320 Turbo';
        $printer->raw_languages_supported = ['microline'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'Letter' => [
                        'name' => 'US Letter',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Legal' => [
                        'name' => 'US Legal',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'A4' => [
                        'name' => 'A4',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'FanFoldUS' => [
                        'name' => 'US Fanfold',
                        'order' => 4,
                        'enabled' => true
                    ]
                ],
                'default' => 'A4',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Resolution' => [
                'name' => 'Resolution',
                'values' => [
                    '60x72dpi' => [
                        'name' => '60x72dpi',
                        'order' => 1,
                        'enabled' => true
                    ],
                    '120x72dpi' => [
                        'name' => '120x72dpi',
                        'order' => 2,
                        'enabled' => true
                    ],
                    '240x72dpi' => [
                        'name' => '240x72dpi',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => '240x72dpi',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ]
        ];
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://ML3320';
        $printer->name = 'OKI MicroLine 3320';
        $printer->raw_languages_supported = ['microline'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'Letter' => [
                        'name' => 'US Letter',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Legal' => [
                        'name' => 'US Legal',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'A4' => [
                        'name' => 'A4',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'FanFoldUS' => [
                        'name' => 'US Fanfold',
                        'order' => 4,
                        'enabled' => true
                    ]
                ],
                'default' => 'A4',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Resolution' => [
                'name' => 'Resolution',
                'values' => [
                    '60x72dpi' => [
                        'name' => '60x72dpi',
                        'order' => 1,
                        'enabled' => true
                    ],
                    '120x72dpi' => [
                        'name' => '120x72dpi',
                        'order' => 2,
                        'enabled' => true
                    ],
                    '240x72dpi' => [
                        'name' => '240x72dpi',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => '240x72dpi',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ]
        ];
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://ML3321';
        $printer->name = 'OKI MicroLine 3321';
        $printer->raw_languages_supported = ['microline'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'Letter' => [
                        'name' => 'US Letter',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Legal' => [
                        'name' => 'US Legal',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'A4' => [
                        'name' => 'A4',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'FanFoldUS' => [
                        'name' => 'US Fanfold',
                        'order' => 4,
                        'enabled' => true
                    ]
                ],
                'default' => 'A4',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Resolution' => [
                'name' => 'Resolution',
                'values' => [
                    '60x72dpi' => [
                        'name' => '60x72dpi',
                        'order' => 1,
                        'enabled' => true
                    ],
                    '120x72dpi' => [
                        'name' => '120x72dpi',
                        'order' => 2,
                        'enabled' => true
                    ],
                    '240x72dpi' => [
                        'name' => '240x72dpi',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => '240x72dpi',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ]
        ];
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://TM-T88V';
        $printer->name = 'Epson TM-T88V';
        $printer->raw_languages_supported = ['escpos'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'RP82.5x297' => [
                        'name' => 'Roll paper 82.5 x 297 mm',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'RP80x297' => [
                        'name' => 'Roll paper 80 x 297 mm',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'RP60x297' => [
                        'name' => 'Roll paper 60 x 297 mm',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'RP58x297' => [
                        'name' => 'Roll paper 58 x 297 mm',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'RP82.5x2000' => [
                        'name' => 'Roll paper 82.5 x 2000 mm',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'RP80x2000' => [
                        'name' => 'Roll paper 80 x 2000 mm',
                        'order' => 6,
                        'enabled' => true
                    ],
                    'RP60x2000' => [
                        'name' => 'Roll paper 60 x 2000 mm',
                        'order' => 7,
                        'enabled' => true
                    ],
                    'RP58x2000' => [
                        'name' => 'Roll paper 58 x 2000 mm',
                        'order' => 8,
                        'enabled' => true
                    ],
                    'A4' => [
                        'name' => 'A4',
                        'order' => 9,
                        'enabled' => true
                    ],
                    'LT' => [
                        'name' => 'LT',
                        'order' => 10,
                        'enabled' => true
                    ]
                ],
                'default' => 'RP80x297',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'TmxPrintSpeed' => [
                'name' => 'Print Speed',
                'values' => [
                    'Auto' => [
                        'name' => 'Use the printer settings',
                        'order' => 1,
                        'enabled' => true
                    ],
                    1 => [
                        'name' => '1st (Fast)',
                        'order' => 2,
                        'enabled' => true
                    ],
                    2 => [
                        'name' => '2nd',
                        'order' => 3,
                        'enabled' => true
                    ],
                    3 => [
                        'name' => '3rd',
                        'order' => 4,
                        'enabled' => true
                    ],
                    4 => [
                        'name' => '4th (Slow)',
                        'order' => 5,
                        'enabled' => true
                    ]
                ],
                'default' => '4',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'TmxPaperReduction' => [
                'name' => 'Paper Reduction',
                'values' => [
                    'Off' => [
                        'name' => 'None',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Top' => [
                        'name' => 'Top margin',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Bottom' => [
                        'name' => 'Bottom margin',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'Both' => [
                        'name' => 'Top & Bottom margins',
                        'order' => 4,
                        'enabled' => true
                    ]
                ],
                'default' => 'Both',
                'enabled' => true,
                'order' => 3,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'TmxPaperSource' => [
                'name' => 'Paper Source',
                'values' => [
                    'DocFeedCut' => [
                        'name' => 'Document [Feed, Cut]',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'DocFeedNoCut' => [
                        'name' => 'Document [Feed, NoCut]',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'DocNoFeedCut' => [
                        'name' => 'Document [NoFeed, Cut]',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'DocNoFeedNoCut' => [
                        'name' => 'Document [NoFeed, NoCut]',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'PageFeedCut' => [
                        'name' => 'Page [Feed, Cut]',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'PageFeedNoCut' => [
                        'name' => 'Page [Feed, NoCut]',
                        'order' => 6,
                        'enabled' => true
                    ],
                    'PageNoFeedCut' => [
                        'name' => 'Page [NoFeed, Cut]',
                        'order' => 7,
                        'enabled' => true
                    ]
                ],
                'default' => 'DocFeedCut',
                'enabled' => true,
                'order' => 4,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'TmxPrinterType' => [
                'name' => 'Printer Type',
                'values' => [
                    'ThermalReceipt' => [
                        'name' => 'Thermal receipt',
                        'order' => 1,
                        'enabled' => true
                    ]
                ],
                'default' => 'ThermalReceipt',
                'enabled' => false,
                'order' => 5,
                'group_key' => 'PrinterSetting',
                'group_name' => 'Printer Setting'
            ],
            'Resolution' => [
                'name' => 'Resolution',
                'values' => [
                    '180x180dpi' => [
                        'name' => '180 x 180 dpi',
                        'order' => 1,
                        'enabled' => true
                    ],
                    '203x203dpi' => [
                        'name' => '203 x 203 dpi',
                        'order' => 2,
                        'enabled' => false
                    ],
                    '162x162dpi' => [
                        'name' => '[90%/180dpi]',
                        'order' => 3,
                        'enabled' => true
                    ],
                    '144x144dpi' => [
                        'name' => '[80%/180dpi]',
                        'order' => 4,
                        'enabled' => true
                    ],
                    '126x126dpi' => [
                        'name' => '[70%/180dpi]',
                        'order' => 5,
                        'enabled' => true
                    ],
                    '108x108dpi' => [
                        'name' => '[60%/180dpi]',
                        'order' => 6,
                        'enabled' => true
                    ],
                    '90x90dpi' => [
                        'name' => '[50%/180dpi]',
                        'order' => 7,
                        'enabled' => true
                    ],
                    '72x72dpi' => [
                        'name' => '[40%/180dpi]',
                        'order' => 8,
                        'enabled' => true
                    ],
                    '68x68dpi' => [
                        'name' => '[38%(A4 to 80mm)/180dpi]',
                        'order' => 9,
                        'enabled' => true
                    ],
                    '48x48dpi' => [
                        'name' => '[27%(A4 to 58mm)/180dpi]',
                        'order' => 10,
                        'enabled' => true
                    ],
                    '0182x182dpi' => [
                        'name' => '[90%/203dpi]',
                        'order' => 11,
                        'enabled' => false
                    ],
                    '0162x162dpi' => [
                        'name' => '[80%/203dpi]',
                        'order' => 12,
                        'enabled' => false
                    ],
                    '0142x142dpi' => [
                        'name' => '[70%/203dpi]',
                        'order' => 13,
                        'enabled' => false
                    ],
                    '0121x121dpi' => [
                        'name' => '[60%/203dpi]',
                        'order' => 14,
                        'enabled' => false
                    ],
                    '0102x102dpi' => [
                        'name' => '[50%/203dpi]',
                        'order' => 15,
                        'enabled' => false
                    ],
                    '081x81dpi' => [
                        'name' => '[40%/203dpi]',
                        'order' => 16,
                        'enabled' => false
                    ],
                    '077x77dpi' => [
                        'name' => '[38%(A4 to 80mm)/203dpi]',
                        'order' => 17,
                        'enabled' => false
                    ],
                    '056x56dpi' => [
                        'name' => '[28%(A4 to 58mm)/203dpi]',
                        'order' => 18,
                        'enabled' => false
                    ]
                ],
                'default' => '180x180dpi',
                'enabled' => true,
                'order' => 6,
                'group_key' => 'PrinterSetting',
                'group_name' => 'Printer Setting'
            ],
            'TmxFeedPitch' => [
                'name' => 'Pitch of Feed',
                'values' => [
                    '180.0' => [
                        'name' => '180 dpi',
                        'order' => 1,
                        'enabled' => true
                    ],
                    '203.2' => [
                        'name' => '203 dpi',
                        'order' => 2,
                        'enabled' => true
                    ],
                    '360.0' => [
                        'name' => '360 dpi',
                        'order' => 3,
                        'enabled' => true
                    ],
                    '406.4' => [
                        'name' => '406 dpi',
                        'order' => 4,
                        'enabled' => true
                    ]
                ],
                'default' => '360.0',
                'enabled' => false,
                'order' => 7,
                'group_key' => 'PrinterSetting',
                'group_name' => 'Printer Setting'
            ],
            'TmxMaxBandWidth' => [
                'name' => 'Maximum Band Width',
                'values' => [
                    360 => [
                        'name' => '360',
                        'order' => 1,
                        'enabled' => true
                    ],
                    384 => [
                        'name' => '384',
                        'order' => 2,
                        'enabled' => true
                    ],
                    416 => [
                        'name' => '416',
                        'order' => 3,
                        'enabled' => true
                    ],
                    420 => [
                        'name' => '420',
                        'order' => 4,
                        'enabled' => true
                    ],
                    436 => [
                        'name' => '436',
                        'order' => 5,
                        'enabled' => true
                    ],
                    512 => [
                        'name' => '512',
                        'order' => 6,
                        'enabled' => true
                    ],
                    576 => [
                        'name' => '576',
                        'order' => 7,
                        'enabled' => true
                    ],
                    640 => [
                        'name' => '640',
                        'order' => 8,
                        'enabled' => true
                    ]
                ],
                'default' => '512',
                'enabled' => false,
                'order' => 8,
                'group_key' => 'PrinterSetting',
                'group_name' => 'Printer Setting'
            ],
            'TmxBandLines' => [
                'name' => 'Band Lines',
                'values' => [
                    256 => [
                        'name' => '256',
                        'order' => 1,
                        'enabled' => true
                    ]
                ],
                'default' => '256',
                'enabled' => false,
                'order' => 9,
                'group_key' => 'PrinterSetting',
                'group_name' => 'Printer Setting'
            ],
            'TmxSpeedControl' => [
                'name' => 'Speed Control',
                'values' => [
                    '0,0,0,0' => [
                        'name' => 'None',
                        'order' => 1,
                        'enabled' => true
                    ],
                    '-1,-1,-1,-1' => [
                        'name' => 'BA-T series',
                        'order' => 2,
                        'enabled' => true
                    ],
                    '9,7,4,1' => [
                        'name' => 'Max. level 9',
                        'order' => 3,
                        'enabled' => true
                    ],
                    '10,7,4,1' => [
                        'name' => 'Max. level 10',
                        'order' => 4,
                        'enabled' => true
                    ],
                    '11,8,4,1' => [
                        'name' => 'Max. level 11',
                        'order' => 5,
                        'enabled' => true
                    ],
                    '13,9,5,1' => [
                        'name' => 'Max. level 13',
                        'order' => 6,
                        'enabled' => true
                    ]
                ],
                'default' => '13,9,5,1',
                'enabled' => false,
                'order' => 10,
                'group_key' => 'PrinterSetting',
                'group_name' => 'Printer Setting'
            ],
            'TmxBuzzerControl' => [
                'name' => 'Buzzer',
                'values' => [
                    'Off' => [
                        'name' => 'Not used',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Before' => [
                        'name' => 'Sounds before printing',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'After' => [
                        'name' => 'Sounds after printing',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Off',
                'enabled' => false,
                'order' => 11,
                'group_key' => 'BuzzerControl',
                'group_name' => 'Buzzer Control'
            ],
            'TmxSoundPattern' => [
                'name' => 'Sound Pattern',
                'values' => [
                    'Internal' => [
                        'name' => 'Internal buzzer',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'A' => [
                        'name' => 'Option buzzer (Pattern A)',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'B' => [
                        'name' => 'Option buzzer (Pattern B)',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'C' => [
                        'name' => 'Option buzzer (Pattern C)',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'D' => [
                        'name' => 'Option buzzer (Pattern D)',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'E' => [
                        'name' => 'Option buzzer (Pattern E)',
                        'order' => 6,
                        'enabled' => true
                    ]
                ],
                'default' => 'Internal',
                'enabled' => false,
                'order' => 12,
                'group_key' => 'BuzzerControl',
                'group_name' => 'Buzzer Control'
            ],
            'TmxBuzzerRepeat' => [
                'name' => 'Buzzer Repeat',
                'values' => [
                    1 => [
                        'name' => '1',
                        'order' => 1,
                        'enabled' => true
                    ],
                    2 => [
                        'name' => '2',
                        'order' => 2,
                        'enabled' => true
                    ],
                    3 => [
                        'name' => '3',
                        'order' => 3,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 4,
                        'enabled' => true
                    ]
                ],
                'default' => '1',
                'enabled' => false,
                'order' => 13,
                'group_key' => 'BuzzerControl',
                'group_name' => 'Buzzer Control'
            ],
            'TmxDrawerControl' => [
                'name' => 'Cash Drawer',
                'values' => [
                    'None' => [
                        'name' => 'Don\'t open drawers',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Drawer#1,Before' => [
                        'name' => 'Open drawer #1 BEFORE printing',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Drawer#1,After' => [
                        'name' => 'Open drawer #1 AFTER printing',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'Drawer#2,Before' => [
                        'name' => 'Open drawer #2 BEFORE printing',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'Drawer#2,After' => [
                        'name' => 'Open drawer #2 AFTER printing',
                        'order' => 5,
                        'enabled' => true
                    ]
                ],
                'default' => 'None',
                'enabled' => false,
                'order' => 14,
                'group_key' => 'CashDrawerControl',
                'group_name' => 'Cash Drawer Control'
            ],
            'TmxPulseOnTime' => [
                'name' => 'Pulse On Time',
                'values' => [
                    '20,10,100' => [
                        'name' => '20 msec',
                        'order' => 1,
                        'enabled' => true
                    ],
                    '40,20,100' => [
                        'name' => '40 msec',
                        'order' => 2,
                        'enabled' => true
                    ],
                    '60,30,120' => [
                        'name' => '60 msec',
                        'order' => 3,
                        'enabled' => true
                    ],
                    '80,40,160' => [
                        'name' => '80 msec',
                        'order' => 4,
                        'enabled' => true
                    ],
                    '100,50,200' => [
                        'name' => '100 msec',
                        'order' => 5,
                        'enabled' => true
                    ],
                    '120,60,240' => [
                        'name' => '120 msec',
                        'order' => 6,
                        'enabled' => true
                    ]
                ],
                'default' => '20,10,100',
                'enabled' => false,
                'order' => 15,
                'group_key' => 'CashDrawerControl',
                'group_name' => 'Cash Drawer Control'
            ]
        ];
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://ZD410';
        $printer->name = 'Zebra ZD410';
        $printer->raw_languages_supported = ['zpl'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'w90h18' => [
                        'name' => '1.25x0.25"',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'w90h162' => [
                        'name' => '1.25x2.25"',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'w108h18' => [
                        'name' => '1.50x0.25"',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'w108h36' => [
                        'name' => '1.50x0.50"',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'w108h72' => [
                        'name' => '1.50x1.00"',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'w108h144' => [
                        'name' => '1.50x2.00"',
                        'order' => 6,
                        'enabled' => true
                    ],
                    'w144h26' => [
                        'name' => '2.00x0.37"',
                        'order' => 7,
                        'enabled' => true
                    ],
                    'w144h36' => [
                        'name' => '2.00x0.50"',
                        'order' => 8,
                        'enabled' => true
                    ],
                    'w144h72' => [
                        'name' => '2.00x1.00"',
                        'order' => 9,
                        'enabled' => true
                    ],
                    'w144h90' => [
                        'name' => '2.00x1.25"',
                        'order' => 10,
                        'enabled' => true
                    ],
                    'w144h288' => [
                        'name' => '2.00x4.00"',
                        'order' => 11,
                        'enabled' => true
                    ],
                    'w144h396' => [
                        'name' => '2.00x5.50"',
                        'order' => 12,
                        'enabled' => true
                    ],
                    'w162h36' => [
                        'name' => '2.25x0.50"',
                        'order' => 13,
                        'enabled' => false
                    ],
                    'w162h90' => [
                        'name' => '2.25x1.25"',
                        'order' => 14,
                        'enabled' => false
                    ],
                    'w162h288' => [
                        'name' => '2.25x4.00"',
                        'order' => 15,
                        'enabled' => false
                    ],
                    'w162h396' => [
                        'name' => '2.25x5.50"',
                        'order' => 16,
                        'enabled' => false
                    ],
                    'w171h396' => [
                        'name' => '2.38x5.50"',
                        'order' => 17,
                        'enabled' => false
                    ],
                    'w180h72' => [
                        'name' => '2.50x1.00"',
                        'order' => 18,
                        'enabled' => false
                    ],
                    'w180h144' => [
                        'name' => '2.50x2.00"',
                        'order' => 19,
                        'enabled' => false
                    ],
                    'w198h90' => [
                        'name' => '2.75x1.25"',
                        'order' => 20,
                        'enabled' => false
                    ],
                    'w216h72' => [
                        'name' => '3.00x1.00"',
                        'order' => 21,
                        'enabled' => false
                    ],
                    'w216h90' => [
                        'name' => '3.00x1.25"',
                        'order' => 22,
                        'enabled' => false
                    ],
                    'w216h144' => [
                        'name' => '3.00x2.00"',
                        'order' => 23,
                        'enabled' => false
                    ],
                    'w216h216' => [
                        'name' => '3.00x3.00"',
                        'order' => 24,
                        'enabled' => false
                    ],
                    'w216h360' => [
                        'name' => '3.00x5.00"',
                        'order' => 25,
                        'enabled' => false
                    ],
                    'w234h144' => [
                        'name' => '3.25x2.00"',
                        'order' => 26,
                        'enabled' => false
                    ],
                    'w234h360' => [
                        'name' => '3.25x5.00"',
                        'order' => 27,
                        'enabled' => false
                    ],
                    'w234h396' => [
                        'name' => '3.25x5.50"',
                        'order' => 28,
                        'enabled' => false
                    ],
                    'w234h419' => [
                        'name' => '3.25x5.83"',
                        'order' => 29,
                        'enabled' => false
                    ],
                    'w234h563' => [
                        'name' => '3.25x7.83"',
                        'order' => 30,
                        'enabled' => false
                    ],
                    'w252h72' => [
                        'name' => '3.50x1.00"',
                        'order' => 31,
                        'enabled' => false
                    ],
                    'w288h72' => [
                        'name' => '4.00x1.00"',
                        'order' => 32,
                        'enabled' => false
                    ],
                    'w288h144' => [
                        'name' => '4.00x2.00"',
                        'order' => 33,
                        'enabled' => false
                    ],
                    'w288h180' => [
                        'name' => '4.00x2.50"',
                        'order' => 34,
                        'enabled' => false
                    ],
                    'w288h216' => [
                        'name' => '4.00x3.00"',
                        'order' => 35,
                        'enabled' => false
                    ],
                    'w288h288' => [
                        'name' => '4.00x4.00"',
                        'order' => 36,
                        'enabled' => false
                    ],
                    'w288h360' => [
                        'name' => '4.00x5.00"',
                        'order' => 37,
                        'enabled' => false
                    ],
                    'w288h432' => [
                        'name' => '4.00x6.00"',
                        'order' => 38,
                        'enabled' => false
                    ],
                    'w288h468' => [
                        'name' => '4.00x6.50"',
                        'order' => 39,
                        'enabled' => false
                    ],
                    'w288h936' => [
                        'name' => '4.00x13.00"',
                        'order' => 40,
                        'enabled' => false
                    ],
                    'w432h72' => [
                        'name' => '6.00x1.00"',
                        'order' => 41,
                        'enabled' => false
                    ],
                    'w432h144' => [
                        'name' => '6.00x2.00"',
                        'order' => 42,
                        'enabled' => false
                    ],
                    'w432h216' => [
                        'name' => '6.00x3.00"',
                        'order' => 43,
                        'enabled' => false
                    ],
                    'w432h288' => [
                        'name' => '6.00x4.00"',
                        'order' => 44,
                        'enabled' => false
                    ],
                    'w432h360' => [
                        'name' => '6.00x5.00"',
                        'order' => 45,
                        'enabled' => false
                    ],
                    'w432h432' => [
                        'name' => '6.00x6.00"',
                        'order' => 46,
                        'enabled' => false
                    ],
                    'w432h468' => [
                        'name' => '6.00x6.50"',
                        'order' => 47,
                        'enabled' => false
                    ],
                    'w576h72' => [
                        'name' => '8.00x1.00"',
                        'order' => 48,
                        'enabled' => false
                    ],
                    'w576h144' => [
                        'name' => '8.00x2.00"',
                        'order' => 49,
                        'enabled' => false
                    ],
                    'w576h216' => [
                        'name' => '8.00x3.00"',
                        'order' => 50,
                        'enabled' => false
                    ],
                    'w576h288' => [
                        'name' => '8.00x4.00"',
                        'order' => 51,
                        'enabled' => false
                    ],
                    'w576h360' => [
                        'name' => '8.00x5.00"',
                        'order' => 52,
                        'enabled' => false
                    ],
                    'w576h432' => [
                        'name' => '8.00x6.00"',
                        'order' => 53,
                        'enabled' => false
                    ],
                    'w576h468' => [
                        'name' => '8.00x6.50"',
                        'order' => 54,
                        'enabled' => false
                    ]
                ],
                'default' => 'w144h72',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Resolution' => [
                'name' => 'Resolution',
                'values' => [
                    '203dpi' => [
                        'name' => '203dpi',
                        'order' => 1,
                        'enabled' => true
                    ],
                    '300dpi' => [
                        'name' => '300dpi',
                        'order' => 2,
                        'enabled' => false
                    ],
                    '600dpi' => [
                        'name' => '600dpi',
                        'order' => 3,
                        'enabled' => false
                    ]
                ],
                'default' => '203dpi',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'zeMediaTracking' => [
                'name' => 'Media Tracking',
                'values' => [
                    'Continuous' => [
                        'name' => 'Continuous',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Web' => [
                        'name' => 'Non-continuous (Web sensing)',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Mark' => [
                        'name' => 'Non-continuous (Mark sensing)',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Web',
                'enabled' => true,
                'order' => 3,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'MediaType' => [
                'name' => 'Media Type',
                'values' => [
                    'Saved' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Thermal' => [
                        'name' => 'Thermal Transfer Media',
                        'order' => 2,
                        'enabled' => false
                    ],
                    'Direct' => [
                        'name' => 'Direct Thermal Media',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Direct',
                'enabled' => true,
                'order' => 4,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Darkness' => [
                'name' => 'Darkness',
                'values' => [
                    -1 => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    1 => [
                        'name' => '1',
                        'order' => 2,
                        'enabled' => true
                    ],
                    2 => [
                        'name' => '2',
                        'order' => 3,
                        'enabled' => true
                    ],
                    3 => [
                        'name' => '3',
                        'order' => 4,
                        'enabled' => true
                    ],
                    4 => [
                        'name' => '4',
                        'order' => 5,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 6,
                        'enabled' => true
                    ],
                    6 => [
                        'name' => '6',
                        'order' => 7,
                        'enabled' => true
                    ],
                    7 => [
                        'name' => '7',
                        'order' => 8,
                        'enabled' => true
                    ],
                    8 => [
                        'name' => '8',
                        'order' => 9,
                        'enabled' => true
                    ],
                    9 => [
                        'name' => '9',
                        'order' => 10,
                        'enabled' => true
                    ],
                    10 => [
                        'name' => '10',
                        'order' => 11,
                        'enabled' => true
                    ],
                    11 => [
                        'name' => '11',
                        'order' => 12,
                        'enabled' => true
                    ],
                    12 => [
                        'name' => '12',
                        'order' => 13,
                        'enabled' => true
                    ],
                    13 => [
                        'name' => '13',
                        'order' => 14,
                        'enabled' => true
                    ],
                    14 => [
                        'name' => '14',
                        'order' => 15,
                        'enabled' => true
                    ],
                    15 => [
                        'name' => '15',
                        'order' => 16,
                        'enabled' => true
                    ],
                    16 => [
                        'name' => '16',
                        'order' => 17,
                        'enabled' => true
                    ],
                    17 => [
                        'name' => '17',
                        'order' => 18,
                        'enabled' => true
                    ],
                    18 => [
                        'name' => '18',
                        'order' => 19,
                        'enabled' => true
                    ],
                    19 => [
                        'name' => '19',
                        'order' => 20,
                        'enabled' => true
                    ],
                    20 => [
                        'name' => '20',
                        'order' => 21,
                        'enabled' => true
                    ],
                    21 => [
                        'name' => '21',
                        'order' => 22,
                        'enabled' => true
                    ],
                    22 => [
                        'name' => '22',
                        'order' => 23,
                        'enabled' => true
                    ],
                    23 => [
                        'name' => '23',
                        'order' => 24,
                        'enabled' => true
                    ],
                    24 => [
                        'name' => '24',
                        'order' => 25,
                        'enabled' => true
                    ],
                    25 => [
                        'name' => '25',
                        'order' => 26,
                        'enabled' => true
                    ],
                    26 => [
                        'name' => '26',
                        'order' => 27,
                        'enabled' => true
                    ],
                    27 => [
                        'name' => '27',
                        'order' => 28,
                        'enabled' => true
                    ],
                    28 => [
                        'name' => '28',
                        'order' => 29,
                        'enabled' => true
                    ],
                    29 => [
                        'name' => '29',
                        'order' => 30,
                        'enabled' => true
                    ],
                    30 => [
                        'name' => '30',
                        'order' => 31,
                        'enabled' => true
                    ]
                ],
                'default' => '-1',
                'enabled' => true,
                'order' => 5,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zePrintRate' => [
                'name' => 'Print Rate',
                'values' => [
                    'Default' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    1 => [
                        'name' => '1 inch/sec.',
                        'order' => 2,
                        'enabled' => true
                    ],
                    2 => [
                        'name' => '2 inches/sec.',
                        'order' => 3,
                        'enabled' => true
                    ],
                    3 => [
                        'name' => '3 inches/sec.',
                        'order' => 4,
                        'enabled' => true
                    ],
                    4 => [
                        'name' => '4 inches/sec.',
                        'order' => 5,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5 inches/sec.',
                        'order' => 6,
                        'enabled' => false
                    ],
                    6 => [
                        'name' => '6 inches/sec.',
                        'order' => 7,
                        'enabled' => false
                    ],
                    7 => [
                        'name' => '7 inches/sec.',
                        'order' => 8,
                        'enabled' => false
                    ],
                    8 => [
                        'name' => '8 inches/sec.',
                        'order' => 9,
                        'enabled' => false
                    ],
                    9 => [
                        'name' => '9 inches/sec.',
                        'order' => 10,
                        'enabled' => false
                    ],
                    10 => [
                        'name' => '10 inches/sec.',
                        'order' => 11,
                        'enabled' => false
                    ],
                    11 => [
                        'name' => '11 inches/sec.',
                        'order' => 12,
                        'enabled' => false
                    ],
                    12 => [
                        'name' => '12 inches/sec.',
                        'order' => 13,
                        'enabled' => false
                    ]
                ],
                'default' => 'Default',
                'enabled' => true,
                'order' => 6,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zeLabelTop' => [
                'name' => 'Label Top',
                'values' => [
                    200 => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    -120 => [
                        'name' => '-120',
                        'order' => 2,
                        'enabled' => true
                    ],
                    -115 => [
                        'name' => '-115',
                        'order' => 3,
                        'enabled' => true
                    ],
                    -110 => [
                        'name' => '-110',
                        'order' => 4,
                        'enabled' => true
                    ],
                    -105 => [
                        'name' => '-105',
                        'order' => 5,
                        'enabled' => true
                    ],
                    -100 => [
                        'name' => '-100',
                        'order' => 6,
                        'enabled' => true
                    ],
                    -95 => [
                        'name' => '-95',
                        'order' => 7,
                        'enabled' => true
                    ],
                    -90 => [
                        'name' => '-90',
                        'order' => 8,
                        'enabled' => true
                    ],
                    -85 => [
                        'name' => '-85',
                        'order' => 9,
                        'enabled' => true
                    ],
                    -80 => [
                        'name' => '-80',
                        'order' => 10,
                        'enabled' => true
                    ],
                    -75 => [
                        'name' => '-75',
                        'order' => 11,
                        'enabled' => true
                    ],
                    -70 => [
                        'name' => '-70',
                        'order' => 12,
                        'enabled' => true
                    ],
                    -65 => [
                        'name' => '-65',
                        'order' => 13,
                        'enabled' => true
                    ],
                    -60 => [
                        'name' => '-60',
                        'order' => 14,
                        'enabled' => true
                    ],
                    -55 => [
                        'name' => '-55',
                        'order' => 15,
                        'enabled' => true
                    ],
                    -50 => [
                        'name' => '-50',
                        'order' => 16,
                        'enabled' => true
                    ],
                    -45 => [
                        'name' => '-45',
                        'order' => 17,
                        'enabled' => true
                    ],
                    -40 => [
                        'name' => '-40',
                        'order' => 18,
                        'enabled' => true
                    ],
                    -35 => [
                        'name' => '-35',
                        'order' => 19,
                        'enabled' => true
                    ],
                    -30 => [
                        'name' => '-30',
                        'order' => 20,
                        'enabled' => true
                    ],
                    -25 => [
                        'name' => '-25',
                        'order' => 21,
                        'enabled' => true
                    ],
                    -20 => [
                        'name' => '-20',
                        'order' => 22,
                        'enabled' => true
                    ],
                    -15 => [
                        'name' => '-15',
                        'order' => 23,
                        'enabled' => true
                    ],
                    -10 => [
                        'name' => '-10',
                        'order' => 24,
                        'enabled' => true
                    ],
                    -5 => [
                        'name' => '-5',
                        'order' => 25,
                        'enabled' => true
                    ],
                    0 => [
                        'name' => '0',
                        'order' => 26,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 27,
                        'enabled' => true
                    ],
                    10 => [
                        'name' => '10',
                        'order' => 28,
                        'enabled' => true
                    ],
                    15 => [
                        'name' => '15',
                        'order' => 29,
                        'enabled' => true
                    ],
                    20 => [
                        'name' => '20',
                        'order' => 30,
                        'enabled' => true
                    ],
                    25 => [
                        'name' => '25',
                        'order' => 31,
                        'enabled' => true
                    ],
                    30 => [
                        'name' => '30',
                        'order' => 32,
                        'enabled' => true
                    ],
                    35 => [
                        'name' => '35',
                        'order' => 33,
                        'enabled' => true
                    ],
                    40 => [
                        'name' => '40',
                        'order' => 34,
                        'enabled' => true
                    ],
                    45 => [
                        'name' => '45',
                        'order' => 35,
                        'enabled' => true
                    ],
                    50 => [
                        'name' => '50',
                        'order' => 36,
                        'enabled' => true
                    ],
                    55 => [
                        'name' => '55',
                        'order' => 37,
                        'enabled' => true
                    ],
                    60 => [
                        'name' => '60',
                        'order' => 38,
                        'enabled' => true
                    ],
                    65 => [
                        'name' => '65',
                        'order' => 39,
                        'enabled' => true
                    ],
                    70 => [
                        'name' => '70',
                        'order' => 40,
                        'enabled' => true
                    ],
                    75 => [
                        'name' => '75',
                        'order' => 41,
                        'enabled' => true
                    ],
                    80 => [
                        'name' => '80',
                        'order' => 42,
                        'enabled' => true
                    ],
                    85 => [
                        'name' => '85',
                        'order' => 43,
                        'enabled' => true
                    ],
                    90 => [
                        'name' => '90',
                        'order' => 44,
                        'enabled' => true
                    ],
                    95 => [
                        'name' => '95',
                        'order' => 45,
                        'enabled' => true
                    ],
                    100 => [
                        'name' => '100',
                        'order' => 46,
                        'enabled' => true
                    ],
                    105 => [
                        'name' => '105',
                        'order' => 47,
                        'enabled' => true
                    ],
                    110 => [
                        'name' => '110',
                        'order' => 48,
                        'enabled' => true
                    ],
                    115 => [
                        'name' => '115',
                        'order' => 49,
                        'enabled' => true
                    ],
                    120 => [
                        'name' => '120',
                        'order' => 50,
                        'enabled' => true
                    ]
                ],
                'default' => '200',
                'enabled' => true,
                'order' => 7,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zePrintMode' => [
                'name' => 'Print Mode',
                'values' => [
                    'Saved' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Tear' => [
                        'name' => 'Tear-Off',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Peel' => [
                        'name' => 'Peel-Off',
                        'order' => 3,
                        'enabled' => false
                    ],
                    'Rewind' => [
                        'name' => 'Rewind',
                        'order' => 4,
                        'enabled' => false
                    ],
                    'Applicator' => [
                        'name' => 'Applicator',
                        'order' => 5,
                        'enabled' => false
                    ],
                    'Cutter' => [
                        'name' => 'Cutter',
                        'order' => 6,
                        'enabled' => false
                    ]
                ],
                'default' => 'Saved',
                'enabled' => true,
                'order' => 8,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zeTearOffPosition' => [
                'name' => 'Tear-Off Adjust Position',
                'values' => [
                    1000 => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    -120 => [
                        'name' => '-120',
                        'order' => 2,
                        'enabled' => true
                    ],
                    -115 => [
                        'name' => '-115',
                        'order' => 3,
                        'enabled' => true
                    ],
                    -110 => [
                        'name' => '-110',
                        'order' => 4,
                        'enabled' => true
                    ],
                    -105 => [
                        'name' => '-105',
                        'order' => 5,
                        'enabled' => true
                    ],
                    -100 => [
                        'name' => '-100',
                        'order' => 6,
                        'enabled' => true
                    ],
                    -95 => [
                        'name' => '-95',
                        'order' => 7,
                        'enabled' => true
                    ],
                    -90 => [
                        'name' => '-90',
                        'order' => 8,
                        'enabled' => true
                    ],
                    -85 => [
                        'name' => '-85',
                        'order' => 9,
                        'enabled' => true
                    ],
                    -80 => [
                        'name' => '-80',
                        'order' => 10,
                        'enabled' => true
                    ],
                    -75 => [
                        'name' => '-75',
                        'order' => 11,
                        'enabled' => true
                    ],
                    -70 => [
                        'name' => '-70',
                        'order' => 12,
                        'enabled' => true
                    ],
                    -65 => [
                        'name' => '-65',
                        'order' => 13,
                        'enabled' => true
                    ],
                    -60 => [
                        'name' => '-60',
                        'order' => 14,
                        'enabled' => true
                    ],
                    -55 => [
                        'name' => '-55',
                        'order' => 15,
                        'enabled' => true
                    ],
                    -50 => [
                        'name' => '-50',
                        'order' => 16,
                        'enabled' => true
                    ],
                    -45 => [
                        'name' => '-45',
                        'order' => 17,
                        'enabled' => true
                    ],
                    -40 => [
                        'name' => '-40',
                        'order' => 18,
                        'enabled' => true
                    ],
                    -35 => [
                        'name' => '-35',
                        'order' => 19,
                        'enabled' => true
                    ],
                    -30 => [
                        'name' => '-30',
                        'order' => 20,
                        'enabled' => true
                    ],
                    -25 => [
                        'name' => '-25',
                        'order' => 21,
                        'enabled' => true
                    ],
                    -20 => [
                        'name' => '-20',
                        'order' => 22,
                        'enabled' => true
                    ],
                    -15 => [
                        'name' => '-15',
                        'order' => 23,
                        'enabled' => true
                    ],
                    -10 => [
                        'name' => '-10',
                        'order' => 24,
                        'enabled' => true
                    ],
                    -5 => [
                        'name' => '-5',
                        'order' => 25,
                        'enabled' => true
                    ],
                    0 => [
                        'name' => '0',
                        'order' => 26,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 27,
                        'enabled' => true
                    ],
                    10 => [
                        'name' => '10',
                        'order' => 28,
                        'enabled' => true
                    ],
                    15 => [
                        'name' => '15',
                        'order' => 29,
                        'enabled' => true
                    ],
                    20 => [
                        'name' => '20',
                        'order' => 30,
                        'enabled' => true
                    ],
                    25 => [
                        'name' => '25',
                        'order' => 31,
                        'enabled' => true
                    ],
                    30 => [
                        'name' => '30',
                        'order' => 32,
                        'enabled' => true
                    ],
                    35 => [
                        'name' => '35',
                        'order' => 33,
                        'enabled' => true
                    ],
                    40 => [
                        'name' => '40',
                        'order' => 34,
                        'enabled' => true
                    ],
                    45 => [
                        'name' => '45',
                        'order' => 35,
                        'enabled' => true
                    ],
                    50 => [
                        'name' => '50',
                        'order' => 36,
                        'enabled' => true
                    ],
                    55 => [
                        'name' => '55',
                        'order' => 37,
                        'enabled' => true
                    ],
                    60 => [
                        'name' => '60',
                        'order' => 38,
                        'enabled' => true
                    ],
                    65 => [
                        'name' => '65',
                        'order' => 39,
                        'enabled' => true
                    ],
                    70 => [
                        'name' => '70',
                        'order' => 40,
                        'enabled' => true
                    ],
                    75 => [
                        'name' => '75',
                        'order' => 41,
                        'enabled' => true
                    ],
                    80 => [
                        'name' => '80',
                        'order' => 42,
                        'enabled' => true
                    ],
                    85 => [
                        'name' => '85',
                        'order' => 43,
                        'enabled' => true
                    ],
                    90 => [
                        'name' => '90',
                        'order' => 44,
                        'enabled' => true
                    ],
                    95 => [
                        'name' => '95',
                        'order' => 45,
                        'enabled' => true
                    ],
                    100 => [
                        'name' => '100',
                        'order' => 46,
                        'enabled' => true
                    ],
                    105 => [
                        'name' => '105',
                        'order' => 47,
                        'enabled' => true
                    ],
                    110 => [
                        'name' => '110',
                        'order' => 48,
                        'enabled' => true
                    ],
                    115 => [
                        'name' => '115',
                        'order' => 49,
                        'enabled' => true
                    ],
                    120 => [
                        'name' => '120',
                        'order' => 50,
                        'enabled' => true
                    ]
                ],
                'default' => '1000',
                'enabled' => true,
                'order' => 9,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zeErrorReprint' => [
                'name' => 'Reprint After Error',
                'values' => [
                    'Saved' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Always' => [
                        'name' => 'Always',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Never' => [
                        'name' => 'Never',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Saved',
                'enabled' => true,
                'order' => 10,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ]
        ];
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://ZD420C';
        $printer->name = 'Zebra ZD420C (Cartridge)';
        $printer->raw_languages_supported = ['zpl'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'w90h18' => [
                        'name' => '1.25x0.25"',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'w90h162' => [
                        'name' => '1.25x2.25"',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'w108h18' => [
                        'name' => '1.50x0.25"',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'w108h36' => [
                        'name' => '1.50x0.50"',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'w108h72' => [
                        'name' => '1.50x1.00"',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'w108h144' => [
                        'name' => '1.50x2.00"',
                        'order' => 6,
                        'enabled' => true
                    ],
                    'w144h26' => [
                        'name' => '2.00x0.37"',
                        'order' => 7,
                        'enabled' => true
                    ],
                    'w144h36' => [
                        'name' => '2.00x0.50"',
                        'order' => 8,
                        'enabled' => true
                    ],
                    'w144h72' => [
                        'name' => '2.00x1.00"',
                        'order' => 9,
                        'enabled' => true
                    ],
                    'w144h90' => [
                        'name' => '2.00x1.25"',
                        'order' => 10,
                        'enabled' => true
                    ],
                    'w144h288' => [
                        'name' => '2.00x4.00"',
                        'order' => 11,
                        'enabled' => true
                    ],
                    'w144h396' => [
                        'name' => '2.00x5.50"',
                        'order' => 12,
                        'enabled' => true
                    ],
                    'w162h36' => [
                        'name' => '2.25x0.50"',
                        'order' => 13,
                        'enabled' => true
                    ],
                    'w162h90' => [
                        'name' => '2.25x1.25"',
                        'order' => 14,
                        'enabled' => true
                    ],
                    'w162h288' => [
                        'name' => '2.25x4.00"',
                        'order' => 15,
                        'enabled' => true
                    ],
                    'w162h396' => [
                        'name' => '2.25x5.50"',
                        'order' => 16,
                        'enabled' => true
                    ],
                    'w171h396' => [
                        'name' => '2.38x5.50"',
                        'order' => 17,
                        'enabled' => true
                    ],
                    'w180h72' => [
                        'name' => '2.50x1.00"',
                        'order' => 18,
                        'enabled' => true
                    ],
                    'w180h144' => [
                        'name' => '2.50x2.00"',
                        'order' => 19,
                        'enabled' => true
                    ],
                    'w198h90' => [
                        'name' => '2.75x1.25"',
                        'order' => 20,
                        'enabled' => true
                    ],
                    'w216h72' => [
                        'name' => '3.00x1.00"',
                        'order' => 21,
                        'enabled' => true
                    ],
                    'w216h90' => [
                        'name' => '3.00x1.25"',
                        'order' => 22,
                        'enabled' => true
                    ],
                    'w216h144' => [
                        'name' => '3.00x2.00"',
                        'order' => 23,
                        'enabled' => true
                    ],
                    'w216h216' => [
                        'name' => '3.00x3.00"',
                        'order' => 24,
                        'enabled' => true
                    ],
                    'w216h360' => [
                        'name' => '3.00x5.00"',
                        'order' => 25,
                        'enabled' => true
                    ],
                    'w234h144' => [
                        'name' => '3.25x2.00"',
                        'order' => 26,
                        'enabled' => true
                    ],
                    'w234h360' => [
                        'name' => '3.25x5.00"',
                        'order' => 27,
                        'enabled' => true
                    ],
                    'w234h396' => [
                        'name' => '3.25x5.50"',
                        'order' => 28,
                        'enabled' => true
                    ],
                    'w234h419' => [
                        'name' => '3.25x5.83"',
                        'order' => 29,
                        'enabled' => true
                    ],
                    'w234h563' => [
                        'name' => '3.25x7.83"',
                        'order' => 30,
                        'enabled' => true
                    ],
                    'w252h72' => [
                        'name' => '3.50x1.00"',
                        'order' => 31,
                        'enabled' => true
                    ],
                    'w288h72' => [
                        'name' => '4.00x1.00"',
                        'order' => 32,
                        'enabled' => true
                    ],
                    'w288h144' => [
                        'name' => '4.00x2.00"',
                        'order' => 33,
                        'enabled' => true
                    ],
                    'w288h180' => [
                        'name' => '4.00x2.50"',
                        'order' => 34,
                        'enabled' => true
                    ],
                    'w288h216' => [
                        'name' => '4.00x3.00"',
                        'order' => 35,
                        'enabled' => true
                    ],
                    'w288h288' => [
                        'name' => '4.00x4.00"',
                        'order' => 36,
                        'enabled' => true
                    ],
                    'w288h360' => [
                        'name' => '4.00x5.00"',
                        'order' => 37,
                        'enabled' => true
                    ],
                    'w288h432' => [
                        'name' => '4.00x6.00"',
                        'order' => 38,
                        'enabled' => true
                    ],
                    'w288h468' => [
                        'name' => '4.00x6.50"',
                        'order' => 39,
                        'enabled' => true
                    ],
                    'w288h936' => [
                        'name' => '4.00x13.00"',
                        'order' => 40,
                        'enabled' => true
                    ],
                    'w432h72' => [
                        'name' => '6.00x1.00"',
                        'order' => 41,
                        'enabled' => false
                    ],
                    'w432h144' => [
                        'name' => '6.00x2.00"',
                        'order' => 42,
                        'enabled' => false
                    ],
                    'w432h216' => [
                        'name' => '6.00x3.00"',
                        'order' => 43,
                        'enabled' => false
                    ],
                    'w432h288' => [
                        'name' => '6.00x4.00"',
                        'order' => 44,
                        'enabled' => false
                    ],
                    'w432h360' => [
                        'name' => '6.00x5.00"',
                        'order' => 45,
                        'enabled' => false
                    ],
                    'w432h432' => [
                        'name' => '6.00x6.00"',
                        'order' => 46,
                        'enabled' => false
                    ],
                    'w432h468' => [
                        'name' => '6.00x6.50"',
                        'order' => 47,
                        'enabled' => false
                    ],
                    'w576h72' => [
                        'name' => '8.00x1.00"',
                        'order' => 48,
                        'enabled' => false
                    ],
                    'w576h144' => [
                        'name' => '8.00x2.00"',
                        'order' => 49,
                        'enabled' => false
                    ],
                    'w576h216' => [
                        'name' => '8.00x3.00"',
                        'order' => 50,
                        'enabled' => false
                    ],
                    'w576h288' => [
                        'name' => '8.00x4.00"',
                        'order' => 51,
                        'enabled' => false
                    ],
                    'w576h360' => [
                        'name' => '8.00x5.00"',
                        'order' => 52,
                        'enabled' => false
                    ],
                    'w576h432' => [
                        'name' => '8.00x6.00"',
                        'order' => 53,
                        'enabled' => false
                    ],
                    'w576h468' => [
                        'name' => '8.00x6.50"',
                        'order' => 54,
                        'enabled' => false
                    ]
                ],
                'default' => 'w144h72',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Resolution' => [
                'name' => 'Resolution',
                'values' => [
                    '203dpi' => [
                        'name' => '203dpi',
                        'order' => 1,
                        'enabled' => false
                    ],
                    '300dpi' => [
                        'name' => '300dpi',
                        'order' => 2,
                        'enabled' => true
                    ],
                    '600dpi' => [
                        'name' => '600dpi',
                        'order' => 3,
                        'enabled' => false
                    ]
                ],
                'default' => '300dpi',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'zeMediaTracking' => [
                'name' => 'Media Tracking',
                'values' => [
                    'Continuous' => [
                        'name' => 'Continuous',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Web' => [
                        'name' => 'Non-continuous (Web sensing)',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Mark' => [
                        'name' => 'Non-continuous (Mark sensing)',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Web',
                'enabled' => true,
                'order' => 3,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'MediaType' => [
                'name' => 'Media Type',
                'values' => [
                    'Saved' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Thermal' => [
                        'name' => 'Thermal Transfer Media',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Direct' => [
                        'name' => 'Direct Thermal Media',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Direct',
                'enabled' => true,
                'order' => 4,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Darkness' => [
                'name' => 'Darkness',
                'values' => [
                    -1 => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    1 => [
                        'name' => '1',
                        'order' => 2,
                        'enabled' => true
                    ],
                    2 => [
                        'name' => '2',
                        'order' => 3,
                        'enabled' => true
                    ],
                    3 => [
                        'name' => '3',
                        'order' => 4,
                        'enabled' => true
                    ],
                    4 => [
                        'name' => '4',
                        'order' => 5,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 6,
                        'enabled' => true
                    ],
                    6 => [
                        'name' => '6',
                        'order' => 7,
                        'enabled' => true
                    ],
                    7 => [
                        'name' => '7',
                        'order' => 8,
                        'enabled' => true
                    ],
                    8 => [
                        'name' => '8',
                        'order' => 9,
                        'enabled' => true
                    ],
                    9 => [
                        'name' => '9',
                        'order' => 10,
                        'enabled' => true
                    ],
                    10 => [
                        'name' => '10',
                        'order' => 11,
                        'enabled' => true
                    ],
                    11 => [
                        'name' => '11',
                        'order' => 12,
                        'enabled' => true
                    ],
                    12 => [
                        'name' => '12',
                        'order' => 13,
                        'enabled' => true
                    ],
                    13 => [
                        'name' => '13',
                        'order' => 14,
                        'enabled' => true
                    ],
                    14 => [
                        'name' => '14',
                        'order' => 15,
                        'enabled' => true
                    ],
                    15 => [
                        'name' => '15',
                        'order' => 16,
                        'enabled' => true
                    ],
                    16 => [
                        'name' => '16',
                        'order' => 17,
                        'enabled' => true
                    ],
                    17 => [
                        'name' => '17',
                        'order' => 18,
                        'enabled' => true
                    ],
                    18 => [
                        'name' => '18',
                        'order' => 19,
                        'enabled' => true
                    ],
                    19 => [
                        'name' => '19',
                        'order' => 20,
                        'enabled' => true
                    ],
                    20 => [
                        'name' => '20',
                        'order' => 21,
                        'enabled' => true
                    ],
                    21 => [
                        'name' => '21',
                        'order' => 22,
                        'enabled' => true
                    ],
                    22 => [
                        'name' => '22',
                        'order' => 23,
                        'enabled' => true
                    ],
                    23 => [
                        'name' => '23',
                        'order' => 24,
                        'enabled' => true
                    ],
                    24 => [
                        'name' => '24',
                        'order' => 25,
                        'enabled' => true
                    ],
                    25 => [
                        'name' => '25',
                        'order' => 26,
                        'enabled' => true
                    ],
                    26 => [
                        'name' => '26',
                        'order' => 27,
                        'enabled' => true
                    ],
                    27 => [
                        'name' => '27',
                        'order' => 28,
                        'enabled' => true
                    ],
                    28 => [
                        'name' => '28',
                        'order' => 29,
                        'enabled' => true
                    ],
                    29 => [
                        'name' => '29',
                        'order' => 30,
                        'enabled' => true
                    ],
                    30 => [
                        'name' => '30',
                        'order' => 31,
                        'enabled' => true
                    ]
                ],
                'default' => '-1',
                'enabled' => true,
                'order' => 5,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zePrintRate' => [
                'name' => 'Print Rate',
                'values' => [
                    'Default' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    1 => [
                        'name' => '1 inch/sec.',
                        'order' => 2,
                        'enabled' => true
                    ],
                    2 => [
                        'name' => '2 inches/sec.',
                        'order' => 3,
                        'enabled' => true
                    ],
                    3 => [
                        'name' => '3 inches/sec.',
                        'order' => 4,
                        'enabled' => true
                    ],
                    4 => [
                        'name' => '4 inches/sec.',
                        'order' => 5,
                        'enabled' => false
                    ],
                    5 => [
                        'name' => '5 inches/sec.',
                        'order' => 6,
                        'enabled' => false
                    ],
                    6 => [
                        'name' => '6 inches/sec.',
                        'order' => 7,
                        'enabled' => false
                    ],
                    7 => [
                        'name' => '7 inches/sec.',
                        'order' => 8,
                        'enabled' => false
                    ],
                    8 => [
                        'name' => '8 inches/sec.',
                        'order' => 9,
                        'enabled' => false
                    ],
                    9 => [
                        'name' => '9 inches/sec.',
                        'order' => 10,
                        'enabled' => false
                    ],
                    10 => [
                        'name' => '10 inches/sec.',
                        'order' => 11,
                        'enabled' => false
                    ],
                    11 => [
                        'name' => '11 inches/sec.',
                        'order' => 12,
                        'enabled' => false
                    ],
                    12 => [
                        'name' => '12 inches/sec.',
                        'order' => 13,
                        'enabled' => false
                    ]
                ],
                'default' => 'Default',
                'enabled' => true,
                'order' => 6,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zeLabelTop' => [
                'name' => 'Label Top',
                'values' => [
                    200 => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    -120 => [
                        'name' => '-120',
                        'order' => 2,
                        'enabled' => true
                    ],
                    -115 => [
                        'name' => '-115',
                        'order' => 3,
                        'enabled' => true
                    ],
                    -110 => [
                        'name' => '-110',
                        'order' => 4,
                        'enabled' => true
                    ],
                    -105 => [
                        'name' => '-105',
                        'order' => 5,
                        'enabled' => true
                    ],
                    -100 => [
                        'name' => '-100',
                        'order' => 6,
                        'enabled' => true
                    ],
                    -95 => [
                        'name' => '-95',
                        'order' => 7,
                        'enabled' => true
                    ],
                    -90 => [
                        'name' => '-90',
                        'order' => 8,
                        'enabled' => true
                    ],
                    -85 => [
                        'name' => '-85',
                        'order' => 9,
                        'enabled' => true
                    ],
                    -80 => [
                        'name' => '-80',
                        'order' => 10,
                        'enabled' => true
                    ],
                    -75 => [
                        'name' => '-75',
                        'order' => 11,
                        'enabled' => true
                    ],
                    -70 => [
                        'name' => '-70',
                        'order' => 12,
                        'enabled' => true
                    ],
                    -65 => [
                        'name' => '-65',
                        'order' => 13,
                        'enabled' => true
                    ],
                    -60 => [
                        'name' => '-60',
                        'order' => 14,
                        'enabled' => true
                    ],
                    -55 => [
                        'name' => '-55',
                        'order' => 15,
                        'enabled' => true
                    ],
                    -50 => [
                        'name' => '-50',
                        'order' => 16,
                        'enabled' => true
                    ],
                    -45 => [
                        'name' => '-45',
                        'order' => 17,
                        'enabled' => true
                    ],
                    -40 => [
                        'name' => '-40',
                        'order' => 18,
                        'enabled' => true
                    ],
                    -35 => [
                        'name' => '-35',
                        'order' => 19,
                        'enabled' => true
                    ],
                    -30 => [
                        'name' => '-30',
                        'order' => 20,
                        'enabled' => true
                    ],
                    -25 => [
                        'name' => '-25',
                        'order' => 21,
                        'enabled' => true
                    ],
                    -20 => [
                        'name' => '-20',
                        'order' => 22,
                        'enabled' => true
                    ],
                    -15 => [
                        'name' => '-15',
                        'order' => 23,
                        'enabled' => true
                    ],
                    -10 => [
                        'name' => '-10',
                        'order' => 24,
                        'enabled' => true
                    ],
                    -5 => [
                        'name' => '-5',
                        'order' => 25,
                        'enabled' => true
                    ],
                    0 => [
                        'name' => '0',
                        'order' => 26,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 27,
                        'enabled' => true
                    ],
                    10 => [
                        'name' => '10',
                        'order' => 28,
                        'enabled' => true
                    ],
                    15 => [
                        'name' => '15',
                        'order' => 29,
                        'enabled' => true
                    ],
                    20 => [
                        'name' => '20',
                        'order' => 30,
                        'enabled' => true
                    ],
                    25 => [
                        'name' => '25',
                        'order' => 31,
                        'enabled' => true
                    ],
                    30 => [
                        'name' => '30',
                        'order' => 32,
                        'enabled' => true
                    ],
                    35 => [
                        'name' => '35',
                        'order' => 33,
                        'enabled' => true
                    ],
                    40 => [
                        'name' => '40',
                        'order' => 34,
                        'enabled' => true
                    ],
                    45 => [
                        'name' => '45',
                        'order' => 35,
                        'enabled' => true
                    ],
                    50 => [
                        'name' => '50',
                        'order' => 36,
                        'enabled' => true
                    ],
                    55 => [
                        'name' => '55',
                        'order' => 37,
                        'enabled' => true
                    ],
                    60 => [
                        'name' => '60',
                        'order' => 38,
                        'enabled' => true
                    ],
                    65 => [
                        'name' => '65',
                        'order' => 39,
                        'enabled' => true
                    ],
                    70 => [
                        'name' => '70',
                        'order' => 40,
                        'enabled' => true
                    ],
                    75 => [
                        'name' => '75',
                        'order' => 41,
                        'enabled' => true
                    ],
                    80 => [
                        'name' => '80',
                        'order' => 42,
                        'enabled' => true
                    ],
                    85 => [
                        'name' => '85',
                        'order' => 43,
                        'enabled' => true
                    ],
                    90 => [
                        'name' => '90',
                        'order' => 44,
                        'enabled' => true
                    ],
                    95 => [
                        'name' => '95',
                        'order' => 45,
                        'enabled' => true
                    ],
                    100 => [
                        'name' => '100',
                        'order' => 46,
                        'enabled' => true
                    ],
                    105 => [
                        'name' => '105',
                        'order' => 47,
                        'enabled' => true
                    ],
                    110 => [
                        'name' => '110',
                        'order' => 48,
                        'enabled' => true
                    ],
                    115 => [
                        'name' => '115',
                        'order' => 49,
                        'enabled' => true
                    ],
                    120 => [
                        'name' => '120',
                        'order' => 50,
                        'enabled' => true
                    ]
                ],
                'default' => '200',
                'enabled' => true,
                'order' => 7,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zePrintMode' => [
                'name' => 'Print Mode',
                'values' => [
                    'Saved' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Tear' => [
                        'name' => 'Tear-Off',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Peel' => [
                        'name' => 'Peel-Off',
                        'order' => 3,
                        'enabled' => false
                    ],
                    'Rewind' => [
                        'name' => 'Rewind',
                        'order' => 4,
                        'enabled' => false
                    ],
                    'Applicator' => [
                        'name' => 'Applicator',
                        'order' => 5,
                        'enabled' => false
                    ],
                    'Cutter' => [
                        'name' => 'Cutter',
                        'order' => 6,
                        'enabled' => false
                    ]
                ],
                'default' => 'Saved',
                'enabled' => true,
                'order' => 8,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zeTearOffPosition' => [
                'name' => 'Tear-Off Adjust Position',
                'values' => [
                    1000 => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    -120 => [
                        'name' => '-120',
                        'order' => 2,
                        'enabled' => true
                    ],
                    -115 => [
                        'name' => '-115',
                        'order' => 3,
                        'enabled' => true
                    ],
                    -110 => [
                        'name' => '-110',
                        'order' => 4,
                        'enabled' => true
                    ],
                    -105 => [
                        'name' => '-105',
                        'order' => 5,
                        'enabled' => true
                    ],
                    -100 => [
                        'name' => '-100',
                        'order' => 6,
                        'enabled' => true
                    ],
                    -95 => [
                        'name' => '-95',
                        'order' => 7,
                        'enabled' => true
                    ],
                    -90 => [
                        'name' => '-90',
                        'order' => 8,
                        'enabled' => true
                    ],
                    -85 => [
                        'name' => '-85',
                        'order' => 9,
                        'enabled' => true
                    ],
                    -80 => [
                        'name' => '-80',
                        'order' => 10,
                        'enabled' => true
                    ],
                    -75 => [
                        'name' => '-75',
                        'order' => 11,
                        'enabled' => true
                    ],
                    -70 => [
                        'name' => '-70',
                        'order' => 12,
                        'enabled' => true
                    ],
                    -65 => [
                        'name' => '-65',
                        'order' => 13,
                        'enabled' => true
                    ],
                    -60 => [
                        'name' => '-60',
                        'order' => 14,
                        'enabled' => true
                    ],
                    -55 => [
                        'name' => '-55',
                        'order' => 15,
                        'enabled' => true
                    ],
                    -50 => [
                        'name' => '-50',
                        'order' => 16,
                        'enabled' => true
                    ],
                    -45 => [
                        'name' => '-45',
                        'order' => 17,
                        'enabled' => true
                    ],
                    -40 => [
                        'name' => '-40',
                        'order' => 18,
                        'enabled' => true
                    ],
                    -35 => [
                        'name' => '-35',
                        'order' => 19,
                        'enabled' => true
                    ],
                    -30 => [
                        'name' => '-30',
                        'order' => 20,
                        'enabled' => true
                    ],
                    -25 => [
                        'name' => '-25',
                        'order' => 21,
                        'enabled' => true
                    ],
                    -20 => [
                        'name' => '-20',
                        'order' => 22,
                        'enabled' => true
                    ],
                    -15 => [
                        'name' => '-15',
                        'order' => 23,
                        'enabled' => true
                    ],
                    -10 => [
                        'name' => '-10',
                        'order' => 24,
                        'enabled' => true
                    ],
                    -5 => [
                        'name' => '-5',
                        'order' => 25,
                        'enabled' => true
                    ],
                    0 => [
                        'name' => '0',
                        'order' => 26,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 27,
                        'enabled' => true
                    ],
                    10 => [
                        'name' => '10',
                        'order' => 28,
                        'enabled' => true
                    ],
                    15 => [
                        'name' => '15',
                        'order' => 29,
                        'enabled' => true
                    ],
                    20 => [
                        'name' => '20',
                        'order' => 30,
                        'enabled' => true
                    ],
                    25 => [
                        'name' => '25',
                        'order' => 31,
                        'enabled' => true
                    ],
                    30 => [
                        'name' => '30',
                        'order' => 32,
                        'enabled' => true
                    ],
                    35 => [
                        'name' => '35',
                        'order' => 33,
                        'enabled' => true
                    ],
                    40 => [
                        'name' => '40',
                        'order' => 34,
                        'enabled' => true
                    ],
                    45 => [
                        'name' => '45',
                        'order' => 35,
                        'enabled' => true
                    ],
                    50 => [
                        'name' => '50',
                        'order' => 36,
                        'enabled' => true
                    ],
                    55 => [
                        'name' => '55',
                        'order' => 37,
                        'enabled' => true
                    ],
                    60 => [
                        'name' => '60',
                        'order' => 38,
                        'enabled' => true
                    ],
                    65 => [
                        'name' => '65',
                        'order' => 39,
                        'enabled' => true
                    ],
                    70 => [
                        'name' => '70',
                        'order' => 40,
                        'enabled' => true
                    ],
                    75 => [
                        'name' => '75',
                        'order' => 41,
                        'enabled' => true
                    ],
                    80 => [
                        'name' => '80',
                        'order' => 42,
                        'enabled' => true
                    ],
                    85 => [
                        'name' => '85',
                        'order' => 43,
                        'enabled' => true
                    ],
                    90 => [
                        'name' => '90',
                        'order' => 44,
                        'enabled' => true
                    ],
                    95 => [
                        'name' => '95',
                        'order' => 45,
                        'enabled' => true
                    ],
                    100 => [
                        'name' => '100',
                        'order' => 46,
                        'enabled' => true
                    ],
                    105 => [
                        'name' => '105',
                        'order' => 47,
                        'enabled' => true
                    ],
                    110 => [
                        'name' => '110',
                        'order' => 48,
                        'enabled' => true
                    ],
                    115 => [
                        'name' => '115',
                        'order' => 49,
                        'enabled' => true
                    ],
                    120 => [
                        'name' => '120',
                        'order' => 50,
                        'enabled' => true
                    ]
                ],
                'default' => '1000',
                'enabled' => true,
                'order' => 9,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zeErrorReprint' => [
                'name' => 'Reprint After Error',
                'values' => [
                    'Saved' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Always' => [
                        'name' => 'Always',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Never' => [
                        'name' => 'Never',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Saved',
                'enabled' => true,
                'order' => 10,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ]
        ];
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://ZD420T';
        $printer->name = 'Zebra ZD420T (Ribbon)';
        $printer->raw_languages_supported = ['zpl'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'w90h18' => [
                        'name' => '1.25x0.25"',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'w90h162' => [
                        'name' => '1.25x2.25"',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'w108h18' => [
                        'name' => '1.50x0.25"',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'w108h36' => [
                        'name' => '1.50x0.50"',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'w108h72' => [
                        'name' => '1.50x1.00"',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'w108h144' => [
                        'name' => '1.50x2.00"',
                        'order' => 6,
                        'enabled' => true
                    ],
                    'w144h26' => [
                        'name' => '2.00x0.37"',
                        'order' => 7,
                        'enabled' => true
                    ],
                    'w144h36' => [
                        'name' => '2.00x0.50"',
                        'order' => 8,
                        'enabled' => true
                    ],
                    'w144h72' => [
                        'name' => '2.00x1.00"',
                        'order' => 9,
                        'enabled' => true
                    ],
                    'w144h90' => [
                        'name' => '2.00x1.25"',
                        'order' => 10,
                        'enabled' => true
                    ],
                    'w144h288' => [
                        'name' => '2.00x4.00"',
                        'order' => 11,
                        'enabled' => true
                    ],
                    'w144h396' => [
                        'name' => '2.00x5.50"',
                        'order' => 12,
                        'enabled' => true
                    ],
                    'w162h36' => [
                        'name' => '2.25x0.50"',
                        'order' => 13,
                        'enabled' => true
                    ],
                    'w162h90' => [
                        'name' => '2.25x1.25"',
                        'order' => 14,
                        'enabled' => true
                    ],
                    'w162h288' => [
                        'name' => '2.25x4.00"',
                        'order' => 15,
                        'enabled' => true
                    ],
                    'w162h396' => [
                        'name' => '2.25x5.50"',
                        'order' => 16,
                        'enabled' => true
                    ],
                    'w171h396' => [
                        'name' => '2.38x5.50"',
                        'order' => 17,
                        'enabled' => true
                    ],
                    'w180h72' => [
                        'name' => '2.50x1.00"',
                        'order' => 18,
                        'enabled' => true
                    ],
                    'w180h144' => [
                        'name' => '2.50x2.00"',
                        'order' => 19,
                        'enabled' => true
                    ],
                    'w198h90' => [
                        'name' => '2.75x1.25"',
                        'order' => 20,
                        'enabled' => true
                    ],
                    'w216h72' => [
                        'name' => '3.00x1.00"',
                        'order' => 21,
                        'enabled' => true
                    ],
                    'w216h90' => [
                        'name' => '3.00x1.25"',
                        'order' => 22,
                        'enabled' => true
                    ],
                    'w216h144' => [
                        'name' => '3.00x2.00"',
                        'order' => 23,
                        'enabled' => true
                    ],
                    'w216h216' => [
                        'name' => '3.00x3.00"',
                        'order' => 24,
                        'enabled' => true
                    ],
                    'w216h360' => [
                        'name' => '3.00x5.00"',
                        'order' => 25,
                        'enabled' => true
                    ],
                    'w234h144' => [
                        'name' => '3.25x2.00"',
                        'order' => 26,
                        'enabled' => true
                    ],
                    'w234h360' => [
                        'name' => '3.25x5.00"',
                        'order' => 27,
                        'enabled' => true
                    ],
                    'w234h396' => [
                        'name' => '3.25x5.50"',
                        'order' => 28,
                        'enabled' => true
                    ],
                    'w234h419' => [
                        'name' => '3.25x5.83"',
                        'order' => 29,
                        'enabled' => true
                    ],
                    'w234h563' => [
                        'name' => '3.25x7.83"',
                        'order' => 30,
                        'enabled' => true
                    ],
                    'w252h72' => [
                        'name' => '3.50x1.00"',
                        'order' => 31,
                        'enabled' => true
                    ],
                    'w288h72' => [
                        'name' => '4.00x1.00"',
                        'order' => 32,
                        'enabled' => true
                    ],
                    'w288h144' => [
                        'name' => '4.00x2.00"',
                        'order' => 33,
                        'enabled' => true
                    ],
                    'w288h180' => [
                        'name' => '4.00x2.50"',
                        'order' => 34,
                        'enabled' => true
                    ],
                    'w288h216' => [
                        'name' => '4.00x3.00"',
                        'order' => 35,
                        'enabled' => true
                    ],
                    'w288h288' => [
                        'name' => '4.00x4.00"',
                        'order' => 36,
                        'enabled' => true
                    ],
                    'w288h360' => [
                        'name' => '4.00x5.00"',
                        'order' => 37,
                        'enabled' => true
                    ],
                    'w288h432' => [
                        'name' => '4.00x6.00"',
                        'order' => 38,
                        'enabled' => true
                    ],
                    'w288h468' => [
                        'name' => '4.00x6.50"',
                        'order' => 39,
                        'enabled' => true
                    ],
                    'w288h936' => [
                        'name' => '4.00x13.00"',
                        'order' => 40,
                        'enabled' => true
                    ],
                    'w432h72' => [
                        'name' => '6.00x1.00"',
                        'order' => 41,
                        'enabled' => false
                    ],
                    'w432h144' => [
                        'name' => '6.00x2.00"',
                        'order' => 42,
                        'enabled' => false
                    ],
                    'w432h216' => [
                        'name' => '6.00x3.00"',
                        'order' => 43,
                        'enabled' => false
                    ],
                    'w432h288' => [
                        'name' => '6.00x4.00"',
                        'order' => 44,
                        'enabled' => false
                    ],
                    'w432h360' => [
                        'name' => '6.00x5.00"',
                        'order' => 45,
                        'enabled' => false
                    ],
                    'w432h432' => [
                        'name' => '6.00x6.00"',
                        'order' => 46,
                        'enabled' => false
                    ],
                    'w432h468' => [
                        'name' => '6.00x6.50"',
                        'order' => 47,
                        'enabled' => false
                    ],
                    'w576h72' => [
                        'name' => '8.00x1.00"',
                        'order' => 48,
                        'enabled' => false
                    ],
                    'w576h144' => [
                        'name' => '8.00x2.00"',
                        'order' => 49,
                        'enabled' => false
                    ],
                    'w576h216' => [
                        'name' => '8.00x3.00"',
                        'order' => 50,
                        'enabled' => false
                    ],
                    'w576h288' => [
                        'name' => '8.00x4.00"',
                        'order' => 51,
                        'enabled' => false
                    ],
                    'w576h360' => [
                        'name' => '8.00x5.00"',
                        'order' => 52,
                        'enabled' => false
                    ],
                    'w576h432' => [
                        'name' => '8.00x6.00"',
                        'order' => 53,
                        'enabled' => false
                    ],
                    'w576h468' => [
                        'name' => '8.00x6.50"',
                        'order' => 54,
                        'enabled' => false
                    ]
                ],
                'default' => 'w144h72',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Resolution' => [
                'name' => 'Resolution',
                'values' => [
                    '203dpi' => [
                        'name' => '203dpi',
                        'order' => 1,
                        'enabled' => false
                    ],
                    '300dpi' => [
                        'name' => '300dpi',
                        'order' => 2,
                        'enabled' => true
                    ],
                    '600dpi' => [
                        'name' => '600dpi',
                        'order' => 3,
                        'enabled' => false
                    ]
                ],
                'default' => '300dpi',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'zeMediaTracking' => [
                'name' => 'Media Tracking',
                'values' => [
                    'Continuous' => [
                        'name' => 'Continuous',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Web' => [
                        'name' => 'Non-continuous (Web sensing)',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Mark' => [
                        'name' => 'Non-continuous (Mark sensing)',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Web',
                'enabled' => true,
                'order' => 3,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'MediaType' => [
                'name' => 'Media Type',
                'values' => [
                    'Saved' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Thermal' => [
                        'name' => 'Thermal Transfer Media',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Direct' => [
                        'name' => 'Direct Thermal Media',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Direct',
                'enabled' => true,
                'order' => 4,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Darkness' => [
                'name' => 'Darkness',
                'values' => [
                    -1 => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    1 => [
                        'name' => '1',
                        'order' => 2,
                        'enabled' => true
                    ],
                    2 => [
                        'name' => '2',
                        'order' => 3,
                        'enabled' => true
                    ],
                    3 => [
                        'name' => '3',
                        'order' => 4,
                        'enabled' => true
                    ],
                    4 => [
                        'name' => '4',
                        'order' => 5,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 6,
                        'enabled' => true
                    ],
                    6 => [
                        'name' => '6',
                        'order' => 7,
                        'enabled' => true
                    ],
                    7 => [
                        'name' => '7',
                        'order' => 8,
                        'enabled' => true
                    ],
                    8 => [
                        'name' => '8',
                        'order' => 9,
                        'enabled' => true
                    ],
                    9 => [
                        'name' => '9',
                        'order' => 10,
                        'enabled' => true
                    ],
                    10 => [
                        'name' => '10',
                        'order' => 11,
                        'enabled' => true
                    ],
                    11 => [
                        'name' => '11',
                        'order' => 12,
                        'enabled' => true
                    ],
                    12 => [
                        'name' => '12',
                        'order' => 13,
                        'enabled' => true
                    ],
                    13 => [
                        'name' => '13',
                        'order' => 14,
                        'enabled' => true
                    ],
                    14 => [
                        'name' => '14',
                        'order' => 15,
                        'enabled' => true
                    ],
                    15 => [
                        'name' => '15',
                        'order' => 16,
                        'enabled' => true
                    ],
                    16 => [
                        'name' => '16',
                        'order' => 17,
                        'enabled' => true
                    ],
                    17 => [
                        'name' => '17',
                        'order' => 18,
                        'enabled' => true
                    ],
                    18 => [
                        'name' => '18',
                        'order' => 19,
                        'enabled' => true
                    ],
                    19 => [
                        'name' => '19',
                        'order' => 20,
                        'enabled' => true
                    ],
                    20 => [
                        'name' => '20',
                        'order' => 21,
                        'enabled' => true
                    ],
                    21 => [
                        'name' => '21',
                        'order' => 22,
                        'enabled' => true
                    ],
                    22 => [
                        'name' => '22',
                        'order' => 23,
                        'enabled' => true
                    ],
                    23 => [
                        'name' => '23',
                        'order' => 24,
                        'enabled' => true
                    ],
                    24 => [
                        'name' => '24',
                        'order' => 25,
                        'enabled' => true
                    ],
                    25 => [
                        'name' => '25',
                        'order' => 26,
                        'enabled' => true
                    ],
                    26 => [
                        'name' => '26',
                        'order' => 27,
                        'enabled' => true
                    ],
                    27 => [
                        'name' => '27',
                        'order' => 28,
                        'enabled' => true
                    ],
                    28 => [
                        'name' => '28',
                        'order' => 29,
                        'enabled' => true
                    ],
                    29 => [
                        'name' => '29',
                        'order' => 30,
                        'enabled' => true
                    ],
                    30 => [
                        'name' => '30',
                        'order' => 31,
                        'enabled' => true
                    ]
                ],
                'default' => '-1',
                'enabled' => true,
                'order' => 5,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zePrintRate' => [
                'name' => 'Print Rate',
                'values' => [
                    'Default' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    1 => [
                        'name' => '1 inch/sec.',
                        'order' => 2,
                        'enabled' => true
                    ],
                    2 => [
                        'name' => '2 inches/sec.',
                        'order' => 3,
                        'enabled' => true
                    ],
                    3 => [
                        'name' => '3 inches/sec.',
                        'order' => 4,
                        'enabled' => true
                    ],
                    4 => [
                        'name' => '4 inches/sec.',
                        'order' => 5,
                        'enabled' => false
                    ],
                    5 => [
                        'name' => '5 inches/sec.',
                        'order' => 6,
                        'enabled' => false
                    ],
                    6 => [
                        'name' => '6 inches/sec.',
                        'order' => 7,
                        'enabled' => false
                    ],
                    7 => [
                        'name' => '7 inches/sec.',
                        'order' => 8,
                        'enabled' => false
                    ],
                    8 => [
                        'name' => '8 inches/sec.',
                        'order' => 9,
                        'enabled' => false
                    ],
                    9 => [
                        'name' => '9 inches/sec.',
                        'order' => 10,
                        'enabled' => false
                    ],
                    10 => [
                        'name' => '10 inches/sec.',
                        'order' => 11,
                        'enabled' => false
                    ],
                    11 => [
                        'name' => '11 inches/sec.',
                        'order' => 12,
                        'enabled' => false
                    ],
                    12 => [
                        'name' => '12 inches/sec.',
                        'order' => 13,
                        'enabled' => false
                    ]
                ],
                'default' => 'Default',
                'enabled' => true,
                'order' => 6,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zeLabelTop' => [
                'name' => 'Label Top',
                'values' => [
                    200 => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    -120 => [
                        'name' => '-120',
                        'order' => 2,
                        'enabled' => true
                    ],
                    -115 => [
                        'name' => '-115',
                        'order' => 3,
                        'enabled' => true
                    ],
                    -110 => [
                        'name' => '-110',
                        'order' => 4,
                        'enabled' => true
                    ],
                    -105 => [
                        'name' => '-105',
                        'order' => 5,
                        'enabled' => true
                    ],
                    -100 => [
                        'name' => '-100',
                        'order' => 6,
                        'enabled' => true
                    ],
                    -95 => [
                        'name' => '-95',
                        'order' => 7,
                        'enabled' => true
                    ],
                    -90 => [
                        'name' => '-90',
                        'order' => 8,
                        'enabled' => true
                    ],
                    -85 => [
                        'name' => '-85',
                        'order' => 9,
                        'enabled' => true
                    ],
                    -80 => [
                        'name' => '-80',
                        'order' => 10,
                        'enabled' => true
                    ],
                    -75 => [
                        'name' => '-75',
                        'order' => 11,
                        'enabled' => true
                    ],
                    -70 => [
                        'name' => '-70',
                        'order' => 12,
                        'enabled' => true
                    ],
                    -65 => [
                        'name' => '-65',
                        'order' => 13,
                        'enabled' => true
                    ],
                    -60 => [
                        'name' => '-60',
                        'order' => 14,
                        'enabled' => true
                    ],
                    -55 => [
                        'name' => '-55',
                        'order' => 15,
                        'enabled' => true
                    ],
                    -50 => [
                        'name' => '-50',
                        'order' => 16,
                        'enabled' => true
                    ],
                    -45 => [
                        'name' => '-45',
                        'order' => 17,
                        'enabled' => true
                    ],
                    -40 => [
                        'name' => '-40',
                        'order' => 18,
                        'enabled' => true
                    ],
                    -35 => [
                        'name' => '-35',
                        'order' => 19,
                        'enabled' => true
                    ],
                    -30 => [
                        'name' => '-30',
                        'order' => 20,
                        'enabled' => true
                    ],
                    -25 => [
                        'name' => '-25',
                        'order' => 21,
                        'enabled' => true
                    ],
                    -20 => [
                        'name' => '-20',
                        'order' => 22,
                        'enabled' => true
                    ],
                    -15 => [
                        'name' => '-15',
                        'order' => 23,
                        'enabled' => true
                    ],
                    -10 => [
                        'name' => '-10',
                        'order' => 24,
                        'enabled' => true
                    ],
                    -5 => [
                        'name' => '-5',
                        'order' => 25,
                        'enabled' => true
                    ],
                    0 => [
                        'name' => '0',
                        'order' => 26,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 27,
                        'enabled' => true
                    ],
                    10 => [
                        'name' => '10',
                        'order' => 28,
                        'enabled' => true
                    ],
                    15 => [
                        'name' => '15',
                        'order' => 29,
                        'enabled' => true
                    ],
                    20 => [
                        'name' => '20',
                        'order' => 30,
                        'enabled' => true
                    ],
                    25 => [
                        'name' => '25',
                        'order' => 31,
                        'enabled' => true
                    ],
                    30 => [
                        'name' => '30',
                        'order' => 32,
                        'enabled' => true
                    ],
                    35 => [
                        'name' => '35',
                        'order' => 33,
                        'enabled' => true
                    ],
                    40 => [
                        'name' => '40',
                        'order' => 34,
                        'enabled' => true
                    ],
                    45 => [
                        'name' => '45',
                        'order' => 35,
                        'enabled' => true
                    ],
                    50 => [
                        'name' => '50',
                        'order' => 36,
                        'enabled' => true
                    ],
                    55 => [
                        'name' => '55',
                        'order' => 37,
                        'enabled' => true
                    ],
                    60 => [
                        'name' => '60',
                        'order' => 38,
                        'enabled' => true
                    ],
                    65 => [
                        'name' => '65',
                        'order' => 39,
                        'enabled' => true
                    ],
                    70 => [
                        'name' => '70',
                        'order' => 40,
                        'enabled' => true
                    ],
                    75 => [
                        'name' => '75',
                        'order' => 41,
                        'enabled' => true
                    ],
                    80 => [
                        'name' => '80',
                        'order' => 42,
                        'enabled' => true
                    ],
                    85 => [
                        'name' => '85',
                        'order' => 43,
                        'enabled' => true
                    ],
                    90 => [
                        'name' => '90',
                        'order' => 44,
                        'enabled' => true
                    ],
                    95 => [
                        'name' => '95',
                        'order' => 45,
                        'enabled' => true
                    ],
                    100 => [
                        'name' => '100',
                        'order' => 46,
                        'enabled' => true
                    ],
                    105 => [
                        'name' => '105',
                        'order' => 47,
                        'enabled' => true
                    ],
                    110 => [
                        'name' => '110',
                        'order' => 48,
                        'enabled' => true
                    ],
                    115 => [
                        'name' => '115',
                        'order' => 49,
                        'enabled' => true
                    ],
                    120 => [
                        'name' => '120',
                        'order' => 50,
                        'enabled' => true
                    ]
                ],
                'default' => '200',
                'enabled' => true,
                'order' => 7,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zePrintMode' => [
                'name' => 'Print Mode',
                'values' => [
                    'Saved' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Tear' => [
                        'name' => 'Tear-Off',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Peel' => [
                        'name' => 'Peel-Off',
                        'order' => 3,
                        'enabled' => false
                    ],
                    'Rewind' => [
                        'name' => 'Rewind',
                        'order' => 4,
                        'enabled' => false
                    ],
                    'Applicator' => [
                        'name' => 'Applicator',
                        'order' => 5,
                        'enabled' => false
                    ],
                    'Cutter' => [
                        'name' => 'Cutter',
                        'order' => 6,
                        'enabled' => false
                    ]
                ],
                'default' => 'Saved',
                'enabled' => true,
                'order' => 8,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zeTearOffPosition' => [
                'name' => 'Tear-Off Adjust Position',
                'values' => [
                    1000 => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    -120 => [
                        'name' => '-120',
                        'order' => 2,
                        'enabled' => true
                    ],
                    -115 => [
                        'name' => '-115',
                        'order' => 3,
                        'enabled' => true
                    ],
                    -110 => [
                        'name' => '-110',
                        'order' => 4,
                        'enabled' => true
                    ],
                    -105 => [
                        'name' => '-105',
                        'order' => 5,
                        'enabled' => true
                    ],
                    -100 => [
                        'name' => '-100',
                        'order' => 6,
                        'enabled' => true
                    ],
                    -95 => [
                        'name' => '-95',
                        'order' => 7,
                        'enabled' => true
                    ],
                    -90 => [
                        'name' => '-90',
                        'order' => 8,
                        'enabled' => true
                    ],
                    -85 => [
                        'name' => '-85',
                        'order' => 9,
                        'enabled' => true
                    ],
                    -80 => [
                        'name' => '-80',
                        'order' => 10,
                        'enabled' => true
                    ],
                    -75 => [
                        'name' => '-75',
                        'order' => 11,
                        'enabled' => true
                    ],
                    -70 => [
                        'name' => '-70',
                        'order' => 12,
                        'enabled' => true
                    ],
                    -65 => [
                        'name' => '-65',
                        'order' => 13,
                        'enabled' => true
                    ],
                    -60 => [
                        'name' => '-60',
                        'order' => 14,
                        'enabled' => true
                    ],
                    -55 => [
                        'name' => '-55',
                        'order' => 15,
                        'enabled' => true
                    ],
                    -50 => [
                        'name' => '-50',
                        'order' => 16,
                        'enabled' => true
                    ],
                    -45 => [
                        'name' => '-45',
                        'order' => 17,
                        'enabled' => true
                    ],
                    -40 => [
                        'name' => '-40',
                        'order' => 18,
                        'enabled' => true
                    ],
                    -35 => [
                        'name' => '-35',
                        'order' => 19,
                        'enabled' => true
                    ],
                    -30 => [
                        'name' => '-30',
                        'order' => 20,
                        'enabled' => true
                    ],
                    -25 => [
                        'name' => '-25',
                        'order' => 21,
                        'enabled' => true
                    ],
                    -20 => [
                        'name' => '-20',
                        'order' => 22,
                        'enabled' => true
                    ],
                    -15 => [
                        'name' => '-15',
                        'order' => 23,
                        'enabled' => true
                    ],
                    -10 => [
                        'name' => '-10',
                        'order' => 24,
                        'enabled' => true
                    ],
                    -5 => [
                        'name' => '-5',
                        'order' => 25,
                        'enabled' => true
                    ],
                    0 => [
                        'name' => '0',
                        'order' => 26,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 27,
                        'enabled' => true
                    ],
                    10 => [
                        'name' => '10',
                        'order' => 28,
                        'enabled' => true
                    ],
                    15 => [
                        'name' => '15',
                        'order' => 29,
                        'enabled' => true
                    ],
                    20 => [
                        'name' => '20',
                        'order' => 30,
                        'enabled' => true
                    ],
                    25 => [
                        'name' => '25',
                        'order' => 31,
                        'enabled' => true
                    ],
                    30 => [
                        'name' => '30',
                        'order' => 32,
                        'enabled' => true
                    ],
                    35 => [
                        'name' => '35',
                        'order' => 33,
                        'enabled' => true
                    ],
                    40 => [
                        'name' => '40',
                        'order' => 34,
                        'enabled' => true
                    ],
                    45 => [
                        'name' => '45',
                        'order' => 35,
                        'enabled' => true
                    ],
                    50 => [
                        'name' => '50',
                        'order' => 36,
                        'enabled' => true
                    ],
                    55 => [
                        'name' => '55',
                        'order' => 37,
                        'enabled' => true
                    ],
                    60 => [
                        'name' => '60',
                        'order' => 38,
                        'enabled' => true
                    ],
                    65 => [
                        'name' => '65',
                        'order' => 39,
                        'enabled' => true
                    ],
                    70 => [
                        'name' => '70',
                        'order' => 40,
                        'enabled' => true
                    ],
                    75 => [
                        'name' => '75',
                        'order' => 41,
                        'enabled' => true
                    ],
                    80 => [
                        'name' => '80',
                        'order' => 42,
                        'enabled' => true
                    ],
                    85 => [
                        'name' => '85',
                        'order' => 43,
                        'enabled' => true
                    ],
                    90 => [
                        'name' => '90',
                        'order' => 44,
                        'enabled' => true
                    ],
                    95 => [
                        'name' => '95',
                        'order' => 45,
                        'enabled' => true
                    ],
                    100 => [
                        'name' => '100',
                        'order' => 46,
                        'enabled' => true
                    ],
                    105 => [
                        'name' => '105',
                        'order' => 47,
                        'enabled' => true
                    ],
                    110 => [
                        'name' => '110',
                        'order' => 48,
                        'enabled' => true
                    ],
                    115 => [
                        'name' => '115',
                        'order' => 49,
                        'enabled' => true
                    ],
                    120 => [
                        'name' => '120',
                        'order' => 50,
                        'enabled' => true
                    ]
                ],
                'default' => '1000',
                'enabled' => true,
                'order' => 9,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zeErrorReprint' => [
                'name' => 'Reprint After Error',
                'values' => [
                    'Saved' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Always' => [
                        'name' => 'Always',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Never' => [
                        'name' => 'Never',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Saved',
                'enabled' => true,
                'order' => 10,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ]
        ];
        $server->Printers()->save($printer);

        $printer = new Printer;
        $printer->uri = 'cups://ZQ520';
        $printer->name = 'Zebra ZQ520 (WiFi)';
        $printer->raw_languages_supported = ['zpl'];
        $printer->enabled = true;
        $printer->ppd_support = true;
        $printer->ppd_options = [
            'PageSize' => [
                'name' => 'Media Size',
                'values' => [
                    'w90h18' => [
                        'name' => '1.25x0.25"',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'w90h162' => [
                        'name' => '1.25x2.25"',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'w108h18' => [
                        'name' => '1.50x0.25"',
                        'order' => 3,
                        'enabled' => true
                    ],
                    'w108h36' => [
                        'name' => '1.50x0.50"',
                        'order' => 4,
                        'enabled' => true
                    ],
                    'w108h72' => [
                        'name' => '1.50x1.00"',
                        'order' => 5,
                        'enabled' => true
                    ],
                    'w108h144' => [
                        'name' => '1.50x2.00"',
                        'order' => 6,
                        'enabled' => true
                    ],
                    'w144h26' => [
                        'name' => '2.00x0.37"',
                        'order' => 7,
                        'enabled' => true
                    ],
                    'w144h36' => [
                        'name' => '2.00x0.50"',
                        'order' => 8,
                        'enabled' => true
                    ],
                    'w144h72' => [
                        'name' => '2.00x1.00"',
                        'order' => 9,
                        'enabled' => true
                    ],
                    'w144h90' => [
                        'name' => '2.00x1.25"',
                        'order' => 10,
                        'enabled' => true
                    ],
                    'w144h288' => [
                        'name' => '2.00x4.00"',
                        'order' => 11,
                        'enabled' => true
                    ],
                    'w144h396' => [
                        'name' => '2.00x5.50"',
                        'order' => 12,
                        'enabled' => true
                    ],
                    'w162h36' => [
                        'name' => '2.25x0.50"',
                        'order' => 13,
                        'enabled' => true
                    ],
                    'w162h90' => [
                        'name' => '2.25x1.25"',
                        'order' => 14,
                        'enabled' => true
                    ],
                    'w162h288' => [
                        'name' => '2.25x4.00"',
                        'order' => 15,
                        'enabled' => true
                    ],
                    'w162h396' => [
                        'name' => '2.25x5.50"',
                        'order' => 16,
                        'enabled' => true
                    ],
                    'w171h396' => [
                        'name' => '2.38x5.50"',
                        'order' => 17,
                        'enabled' => true
                    ],
                    'w180h72' => [
                        'name' => '2.50x1.00"',
                        'order' => 18,
                        'enabled' => true
                    ],
                    'w180h144' => [
                        'name' => '2.50x2.00"',
                        'order' => 19,
                        'enabled' => true
                    ],
                    'w198h90' => [
                        'name' => '2.75x1.25"',
                        'order' => 20,
                        'enabled' => true
                    ],
                    'w216h72' => [
                        'name' => '3.00x1.00"',
                        'order' => 21,
                        'enabled' => true
                    ],
                    'w216h90' => [
                        'name' => '3.00x1.25"',
                        'order' => 22,
                        'enabled' => true
                    ],
                    'w216h144' => [
                        'name' => '3.00x2.00"',
                        'order' => 23,
                        'enabled' => true
                    ],
                    'w216h216' => [
                        'name' => '3.00x3.00"',
                        'order' => 24,
                        'enabled' => true
                    ],
                    'w216h360' => [
                        'name' => '3.00x5.00"',
                        'order' => 25,
                        'enabled' => true
                    ],
                    'w234h144' => [
                        'name' => '3.25x2.00"',
                        'order' => 26,
                        'enabled' => true
                    ],
                    'w234h360' => [
                        'name' => '3.25x5.00"',
                        'order' => 27,
                        'enabled' => true
                    ],
                    'w234h396' => [
                        'name' => '3.25x5.50"',
                        'order' => 28,
                        'enabled' => true
                    ],
                    'w234h419' => [
                        'name' => '3.25x5.83"',
                        'order' => 29,
                        'enabled' => true
                    ],
                    'w234h563' => [
                        'name' => '3.25x7.83"',
                        'order' => 30,
                        'enabled' => true
                    ],
                    'w252h72' => [
                        'name' => '3.50x1.00"',
                        'order' => 31,
                        'enabled' => true
                    ],
                    'w288h72' => [
                        'name' => '4.00x1.00"',
                        'order' => 32,
                        'enabled' => true
                    ],
                    'w288h144' => [
                        'name' => '4.00x2.00"',
                        'order' => 33,
                        'enabled' => true
                    ],
                    'w288h180' => [
                        'name' => '4.00x2.50"',
                        'order' => 34,
                        'enabled' => true
                    ],
                    'w288h216' => [
                        'name' => '4.00x3.00"',
                        'order' => 35,
                        'enabled' => true
                    ],
                    'w288h288' => [
                        'name' => '4.00x4.00"',
                        'order' => 36,
                        'enabled' => true
                    ],
                    'w288h360' => [
                        'name' => '4.00x5.00"',
                        'order' => 37,
                        'enabled' => true
                    ],
                    'w288h432' => [
                        'name' => '4.00x6.00"',
                        'order' => 38,
                        'enabled' => true
                    ],
                    'w288h468' => [
                        'name' => '4.00x6.50"',
                        'order' => 39,
                        'enabled' => true
                    ],
                    'w288h936' => [
                        'name' => '4.00x13.00"',
                        'order' => 40,
                        'enabled' => true
                    ],
                    'w432h72' => [
                        'name' => '6.00x1.00"',
                        'order' => 41,
                        'enabled' => false
                    ],
                    'w432h144' => [
                        'name' => '6.00x2.00"',
                        'order' => 42,
                        'enabled' => false
                    ],
                    'w432h216' => [
                        'name' => '6.00x3.00"',
                        'order' => 43,
                        'enabled' => false
                    ],
                    'w432h288' => [
                        'name' => '6.00x4.00"',
                        'order' => 44,
                        'enabled' => false
                    ],
                    'w432h360' => [
                        'name' => '6.00x5.00"',
                        'order' => 45,
                        'enabled' => false
                    ],
                    'w432h432' => [
                        'name' => '6.00x6.00"',
                        'order' => 46,
                        'enabled' => false
                    ],
                    'w432h468' => [
                        'name' => '6.00x6.50"',
                        'order' => 47,
                        'enabled' => false
                    ],
                    'w576h72' => [
                        'name' => '8.00x1.00"',
                        'order' => 48,
                        'enabled' => false
                    ],
                    'w576h144' => [
                        'name' => '8.00x2.00"',
                        'order' => 49,
                        'enabled' => false
                    ],
                    'w576h216' => [
                        'name' => '8.00x3.00"',
                        'order' => 50,
                        'enabled' => false
                    ],
                    'w576h288' => [
                        'name' => '8.00x4.00"',
                        'order' => 51,
                        'enabled' => false
                    ],
                    'w576h360' => [
                        'name' => '8.00x5.00"',
                        'order' => 52,
                        'enabled' => false
                    ],
                    'w576h432' => [
                        'name' => '8.00x6.00"',
                        'order' => 53,
                        'enabled' => false
                    ],
                    'w576h468' => [
                        'name' => '8.00x6.50"',
                        'order' => 54,
                        'enabled' => false
                    ]
                ],
                'default' => 'w144h72',
                'enabled' => true,
                'order' => 1,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Resolution' => [
                'name' => 'Resolution',
                'values' => [
                    '203dpi' => [
                        'name' => '203dpi',
                        'order' => 1,
                        'enabled' => true
                    ],
                    '300dpi' => [
                        'name' => '300dpi',
                        'order' => 2,
                        'enabled' => false
                    ],
                    '600dpi' => [
                        'name' => '600dpi',
                        'order' => 3,
                        'enabled' => false
                    ]
                ],
                'default' => '203dpi',
                'enabled' => true,
                'order' => 2,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'zeMediaTracking' => [
                'name' => 'Media Tracking',
                'values' => [
                    'Continuous' => [
                        'name' => 'Continuous',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Web' => [
                        'name' => 'Non-continuous (Web sensing)',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Mark' => [
                        'name' => 'Non-continuous (Mark sensing)',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Web',
                'enabled' => true,
                'order' => 3,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'MediaType' => [
                'name' => 'Media Type',
                'values' => [
                    'Saved' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Thermal' => [
                        'name' => 'Thermal Transfer Media',
                        'order' => 2,
                        'enabled' => false
                    ],
                    'Direct' => [
                        'name' => 'Direct Thermal Media',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Direct',
                'enabled' => true,
                'order' => 4,
                'group_key' => 'General',
                'group_name' => 'General'
            ],
            'Darkness' => [
                'name' => 'Darkness',
                'values' => [
                    -1 => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    1 => [
                        'name' => '1',
                        'order' => 2,
                        'enabled' => true
                    ],
                    2 => [
                        'name' => '2',
                        'order' => 3,
                        'enabled' => true
                    ],
                    3 => [
                        'name' => '3',
                        'order' => 4,
                        'enabled' => true
                    ],
                    4 => [
                        'name' => '4',
                        'order' => 5,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 6,
                        'enabled' => true
                    ],
                    6 => [
                        'name' => '6',
                        'order' => 7,
                        'enabled' => true
                    ],
                    7 => [
                        'name' => '7',
                        'order' => 8,
                        'enabled' => true
                    ],
                    8 => [
                        'name' => '8',
                        'order' => 9,
                        'enabled' => true
                    ],
                    9 => [
                        'name' => '9',
                        'order' => 10,
                        'enabled' => true
                    ],
                    10 => [
                        'name' => '10',
                        'order' => 11,
                        'enabled' => true
                    ],
                    11 => [
                        'name' => '11',
                        'order' => 12,
                        'enabled' => true
                    ],
                    12 => [
                        'name' => '12',
                        'order' => 13,
                        'enabled' => true
                    ],
                    13 => [
                        'name' => '13',
                        'order' => 14,
                        'enabled' => true
                    ],
                    14 => [
                        'name' => '14',
                        'order' => 15,
                        'enabled' => true
                    ],
                    15 => [
                        'name' => '15',
                        'order' => 16,
                        'enabled' => true
                    ],
                    16 => [
                        'name' => '16',
                        'order' => 17,
                        'enabled' => true
                    ],
                    17 => [
                        'name' => '17',
                        'order' => 18,
                        'enabled' => true
                    ],
                    18 => [
                        'name' => '18',
                        'order' => 19,
                        'enabled' => true
                    ],
                    19 => [
                        'name' => '19',
                        'order' => 20,
                        'enabled' => true
                    ],
                    20 => [
                        'name' => '20',
                        'order' => 21,
                        'enabled' => true
                    ],
                    21 => [
                        'name' => '21',
                        'order' => 22,
                        'enabled' => true
                    ],
                    22 => [
                        'name' => '22',
                        'order' => 23,
                        'enabled' => true
                    ],
                    23 => [
                        'name' => '23',
                        'order' => 24,
                        'enabled' => true
                    ],
                    24 => [
                        'name' => '24',
                        'order' => 25,
                        'enabled' => true
                    ],
                    25 => [
                        'name' => '25',
                        'order' => 26,
                        'enabled' => true
                    ],
                    26 => [
                        'name' => '26',
                        'order' => 27,
                        'enabled' => true
                    ],
                    27 => [
                        'name' => '27',
                        'order' => 28,
                        'enabled' => true
                    ],
                    28 => [
                        'name' => '28',
                        'order' => 29,
                        'enabled' => true
                    ],
                    29 => [
                        'name' => '29',
                        'order' => 30,
                        'enabled' => true
                    ],
                    30 => [
                        'name' => '30',
                        'order' => 31,
                        'enabled' => true
                    ]
                ],
                'default' => '-1',
                'enabled' => true,
                'order' => 5,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zePrintRate' => [
                'name' => 'Print Rate',
                'values' => [
                    'Default' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    1 => [
                        'name' => '1 inch/sec.',
                        'order' => 2,
                        'enabled' => true
                    ],
                    2 => [
                        'name' => '2 inches/sec.',
                        'order' => 3,
                        'enabled' => true
                    ],
                    3 => [
                        'name' => '3 inches/sec.',
                        'order' => 4,
                        'enabled' => true
                    ],
                    4 => [
                        'name' => '4 inches/sec.',
                        'order' => 5,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5 inches/sec.',
                        'order' => 6,
                        'enabled' => false
                    ],
                    6 => [
                        'name' => '6 inches/sec.',
                        'order' => 7,
                        'enabled' => false
                    ],
                    7 => [
                        'name' => '7 inches/sec.',
                        'order' => 8,
                        'enabled' => false
                    ],
                    8 => [
                        'name' => '8 inches/sec.',
                        'order' => 9,
                        'enabled' => false
                    ],
                    9 => [
                        'name' => '9 inches/sec.',
                        'order' => 10,
                        'enabled' => false
                    ],
                    10 => [
                        'name' => '10 inches/sec.',
                        'order' => 11,
                        'enabled' => false
                    ],
                    11 => [
                        'name' => '11 inches/sec.',
                        'order' => 12,
                        'enabled' => false
                    ],
                    12 => [
                        'name' => '12 inches/sec.',
                        'order' => 13,
                        'enabled' => false
                    ]
                ],
                'default' => 'Default',
                'enabled' => true,
                'order' => 6,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zeLabelTop' => [
                'name' => 'Label Top',
                'values' => [
                    200 => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    -120 => [
                        'name' => '-120',
                        'order' => 2,
                        'enabled' => true
                    ],
                    -115 => [
                        'name' => '-115',
                        'order' => 3,
                        'enabled' => true
                    ],
                    -110 => [
                        'name' => '-110',
                        'order' => 4,
                        'enabled' => true
                    ],
                    -105 => [
                        'name' => '-105',
                        'order' => 5,
                        'enabled' => true
                    ],
                    -100 => [
                        'name' => '-100',
                        'order' => 6,
                        'enabled' => true
                    ],
                    -95 => [
                        'name' => '-95',
                        'order' => 7,
                        'enabled' => true
                    ],
                    -90 => [
                        'name' => '-90',
                        'order' => 8,
                        'enabled' => true
                    ],
                    -85 => [
                        'name' => '-85',
                        'order' => 9,
                        'enabled' => true
                    ],
                    -80 => [
                        'name' => '-80',
                        'order' => 10,
                        'enabled' => true
                    ],
                    -75 => [
                        'name' => '-75',
                        'order' => 11,
                        'enabled' => true
                    ],
                    -70 => [
                        'name' => '-70',
                        'order' => 12,
                        'enabled' => true
                    ],
                    -65 => [
                        'name' => '-65',
                        'order' => 13,
                        'enabled' => true
                    ],
                    -60 => [
                        'name' => '-60',
                        'order' => 14,
                        'enabled' => true
                    ],
                    -55 => [
                        'name' => '-55',
                        'order' => 15,
                        'enabled' => true
                    ],
                    -50 => [
                        'name' => '-50',
                        'order' => 16,
                        'enabled' => true
                    ],
                    -45 => [
                        'name' => '-45',
                        'order' => 17,
                        'enabled' => true
                    ],
                    -40 => [
                        'name' => '-40',
                        'order' => 18,
                        'enabled' => true
                    ],
                    -35 => [
                        'name' => '-35',
                        'order' => 19,
                        'enabled' => true
                    ],
                    -30 => [
                        'name' => '-30',
                        'order' => 20,
                        'enabled' => true
                    ],
                    -25 => [
                        'name' => '-25',
                        'order' => 21,
                        'enabled' => true
                    ],
                    -20 => [
                        'name' => '-20',
                        'order' => 22,
                        'enabled' => true
                    ],
                    -15 => [
                        'name' => '-15',
                        'order' => 23,
                        'enabled' => true
                    ],
                    -10 => [
                        'name' => '-10',
                        'order' => 24,
                        'enabled' => true
                    ],
                    -5 => [
                        'name' => '-5',
                        'order' => 25,
                        'enabled' => true
                    ],
                    0 => [
                        'name' => '0',
                        'order' => 26,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 27,
                        'enabled' => true
                    ],
                    10 => [
                        'name' => '10',
                        'order' => 28,
                        'enabled' => true
                    ],
                    15 => [
                        'name' => '15',
                        'order' => 29,
                        'enabled' => true
                    ],
                    20 => [
                        'name' => '20',
                        'order' => 30,
                        'enabled' => true
                    ],
                    25 => [
                        'name' => '25',
                        'order' => 31,
                        'enabled' => true
                    ],
                    30 => [
                        'name' => '30',
                        'order' => 32,
                        'enabled' => true
                    ],
                    35 => [
                        'name' => '35',
                        'order' => 33,
                        'enabled' => true
                    ],
                    40 => [
                        'name' => '40',
                        'order' => 34,
                        'enabled' => true
                    ],
                    45 => [
                        'name' => '45',
                        'order' => 35,
                        'enabled' => true
                    ],
                    50 => [
                        'name' => '50',
                        'order' => 36,
                        'enabled' => true
                    ],
                    55 => [
                        'name' => '55',
                        'order' => 37,
                        'enabled' => true
                    ],
                    60 => [
                        'name' => '60',
                        'order' => 38,
                        'enabled' => true
                    ],
                    65 => [
                        'name' => '65',
                        'order' => 39,
                        'enabled' => true
                    ],
                    70 => [
                        'name' => '70',
                        'order' => 40,
                        'enabled' => true
                    ],
                    75 => [
                        'name' => '75',
                        'order' => 41,
                        'enabled' => true
                    ],
                    80 => [
                        'name' => '80',
                        'order' => 42,
                        'enabled' => true
                    ],
                    85 => [
                        'name' => '85',
                        'order' => 43,
                        'enabled' => true
                    ],
                    90 => [
                        'name' => '90',
                        'order' => 44,
                        'enabled' => true
                    ],
                    95 => [
                        'name' => '95',
                        'order' => 45,
                        'enabled' => true
                    ],
                    100 => [
                        'name' => '100',
                        'order' => 46,
                        'enabled' => true
                    ],
                    105 => [
                        'name' => '105',
                        'order' => 47,
                        'enabled' => true
                    ],
                    110 => [
                        'name' => '110',
                        'order' => 48,
                        'enabled' => true
                    ],
                    115 => [
                        'name' => '115',
                        'order' => 49,
                        'enabled' => true
                    ],
                    120 => [
                        'name' => '120',
                        'order' => 50,
                        'enabled' => true
                    ]
                ],
                'default' => '200',
                'enabled' => true,
                'order' => 7,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zePrintMode' => [
                'name' => 'Print Mode',
                'values' => [
                    'Saved' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Tear' => [
                        'name' => 'Tear-Off',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Peel' => [
                        'name' => 'Peel-Off',
                        'order' => 3,
                        'enabled' => false
                    ],
                    'Rewind' => [
                        'name' => 'Rewind',
                        'order' => 4,
                        'enabled' => false
                    ],
                    'Applicator' => [
                        'name' => 'Applicator',
                        'order' => 5,
                        'enabled' => false
                    ],
                    'Cutter' => [
                        'name' => 'Cutter',
                        'order' => 6,
                        'enabled' => false
                    ]
                ],
                'default' => 'Saved',
                'enabled' => true,
                'order' => 8,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zeTearOffPosition' => [
                'name' => 'Tear-Off Adjust Position',
                'values' => [
                    1000 => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    -120 => [
                        'name' => '-120',
                        'order' => 2,
                        'enabled' => true
                    ],
                    -115 => [
                        'name' => '-115',
                        'order' => 3,
                        'enabled' => true
                    ],
                    -110 => [
                        'name' => '-110',
                        'order' => 4,
                        'enabled' => true
                    ],
                    -105 => [
                        'name' => '-105',
                        'order' => 5,
                        'enabled' => true
                    ],
                    -100 => [
                        'name' => '-100',
                        'order' => 6,
                        'enabled' => true
                    ],
                    -95 => [
                        'name' => '-95',
                        'order' => 7,
                        'enabled' => true
                    ],
                    -90 => [
                        'name' => '-90',
                        'order' => 8,
                        'enabled' => true
                    ],
                    -85 => [
                        'name' => '-85',
                        'order' => 9,
                        'enabled' => true
                    ],
                    -80 => [
                        'name' => '-80',
                        'order' => 10,
                        'enabled' => true
                    ],
                    -75 => [
                        'name' => '-75',
                        'order' => 11,
                        'enabled' => true
                    ],
                    -70 => [
                        'name' => '-70',
                        'order' => 12,
                        'enabled' => true
                    ],
                    -65 => [
                        'name' => '-65',
                        'order' => 13,
                        'enabled' => true
                    ],
                    -60 => [
                        'name' => '-60',
                        'order' => 14,
                        'enabled' => true
                    ],
                    -55 => [
                        'name' => '-55',
                        'order' => 15,
                        'enabled' => true
                    ],
                    -50 => [
                        'name' => '-50',
                        'order' => 16,
                        'enabled' => true
                    ],
                    -45 => [
                        'name' => '-45',
                        'order' => 17,
                        'enabled' => true
                    ],
                    -40 => [
                        'name' => '-40',
                        'order' => 18,
                        'enabled' => true
                    ],
                    -35 => [
                        'name' => '-35',
                        'order' => 19,
                        'enabled' => true
                    ],
                    -30 => [
                        'name' => '-30',
                        'order' => 20,
                        'enabled' => true
                    ],
                    -25 => [
                        'name' => '-25',
                        'order' => 21,
                        'enabled' => true
                    ],
                    -20 => [
                        'name' => '-20',
                        'order' => 22,
                        'enabled' => true
                    ],
                    -15 => [
                        'name' => '-15',
                        'order' => 23,
                        'enabled' => true
                    ],
                    -10 => [
                        'name' => '-10',
                        'order' => 24,
                        'enabled' => true
                    ],
                    -5 => [
                        'name' => '-5',
                        'order' => 25,
                        'enabled' => true
                    ],
                    0 => [
                        'name' => '0',
                        'order' => 26,
                        'enabled' => true
                    ],
                    5 => [
                        'name' => '5',
                        'order' => 27,
                        'enabled' => true
                    ],
                    10 => [
                        'name' => '10',
                        'order' => 28,
                        'enabled' => true
                    ],
                    15 => [
                        'name' => '15',
                        'order' => 29,
                        'enabled' => true
                    ],
                    20 => [
                        'name' => '20',
                        'order' => 30,
                        'enabled' => true
                    ],
                    25 => [
                        'name' => '25',
                        'order' => 31,
                        'enabled' => true
                    ],
                    30 => [
                        'name' => '30',
                        'order' => 32,
                        'enabled' => true
                    ],
                    35 => [
                        'name' => '35',
                        'order' => 33,
                        'enabled' => true
                    ],
                    40 => [
                        'name' => '40',
                        'order' => 34,
                        'enabled' => true
                    ],
                    45 => [
                        'name' => '45',
                        'order' => 35,
                        'enabled' => true
                    ],
                    50 => [
                        'name' => '50',
                        'order' => 36,
                        'enabled' => true
                    ],
                    55 => [
                        'name' => '55',
                        'order' => 37,
                        'enabled' => true
                    ],
                    60 => [
                        'name' => '60',
                        'order' => 38,
                        'enabled' => true
                    ],
                    65 => [
                        'name' => '65',
                        'order' => 39,
                        'enabled' => true
                    ],
                    70 => [
                        'name' => '70',
                        'order' => 40,
                        'enabled' => true
                    ],
                    75 => [
                        'name' => '75',
                        'order' => 41,
                        'enabled' => true
                    ],
                    80 => [
                        'name' => '80',
                        'order' => 42,
                        'enabled' => true
                    ],
                    85 => [
                        'name' => '85',
                        'order' => 43,
                        'enabled' => true
                    ],
                    90 => [
                        'name' => '90',
                        'order' => 44,
                        'enabled' => true
                    ],
                    95 => [
                        'name' => '95',
                        'order' => 45,
                        'enabled' => true
                    ],
                    100 => [
                        'name' => '100',
                        'order' => 46,
                        'enabled' => true
                    ],
                    105 => [
                        'name' => '105',
                        'order' => 47,
                        'enabled' => true
                    ],
                    110 => [
                        'name' => '110',
                        'order' => 48,
                        'enabled' => true
                    ],
                    115 => [
                        'name' => '115',
                        'order' => 49,
                        'enabled' => true
                    ],
                    120 => [
                        'name' => '120',
                        'order' => 50,
                        'enabled' => true
                    ]
                ],
                'default' => '1000',
                'enabled' => true,
                'order' => 9,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ],
            'zeErrorReprint' => [
                'name' => 'Reprint After Error',
                'values' => [
                    'Saved' => [
                        'name' => 'Printer Default',
                        'order' => 1,
                        'enabled' => true
                    ],
                    'Always' => [
                        'name' => 'Always',
                        'order' => 2,
                        'enabled' => true
                    ],
                    'Never' => [
                        'name' => 'Never',
                        'order' => 3,
                        'enabled' => true
                    ]
                ],
                'default' => 'Saved',
                'enabled' => true,
                'order' => 10,
                'group_key' => 'PrinterSettings',
                'group_name' => 'Printer Settings'
            ]
        ];
        $server->Printers()->save($printer);
    }
}
