<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CountriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        // \DB::table('countries')->delete();
        Schema::disableForeignKeyConstraints();
        \DB::table('countries')->truncate();
        Schema::enableForeignKeyConstraints();
        
        \DB::table('countries')->insert(array (
            0 => 
            array (
                'code' => 'CA',
                'name' => 'Canada',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            1 => 
            array (
                'code' => 'FR',
                'name' => 'France',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            2 => 
            array (
                'code' => 'CH',
                'name' => 'Switzerland',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            3 => 
            array (
                'code' => 'US',
                'name' => 'United States',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            4 => 
            array (
                'code' => 'AI',
                'name' => 'Anguilla',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            5 => 
            array (
                'code' => 'AL',
                'name' => 'Albania',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            6 => 
            array (
                'code' => 'AM',
                'name' => 'Armenia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            7 => 
            array (
                'code' => 'AN',
                'name' => 'Netherlands Antilles',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            8 => 
            array (
                'code' => 'AO',
                'name' => 'Angola',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            9 => 
            array (
                'code' => 'AQ',
                'name' => 'Antarctica',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            10 => 
            array (
                'code' => 'AR',
                'name' => 'Argentina',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            11 => 
            array (
                'code' => 'AS',
                'name' => 'American Samoa',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            12 => 
            array (
                'code' => 'AT',
                'name' => 'Austria',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            13 => 
            array (
                'code' => 'AU',
                'name' => 'Australia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            14 => 
            array (
                'code' => 'AW',
                'name' => 'Aruba',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            15 => 
            array (
                'code' => 'AX',
                'name' => 'Åland Islands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            16 => 
            array (
                'code' => 'AZ',
                'name' => 'Azerbaijan',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            17 => 
            array (
                'code' => 'BA',
                'name' => 'Bosnia and Herzegovina',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            18 => 
            array (
                'code' => 'BB',
                'name' => 'Barbados',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            19 => 
            array (
                'code' => 'BD',
                'name' => 'Bangladesh',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            20 => 
            array (
                'code' => 'BE',
                'name' => 'Belgium',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            21 => 
            array (
                'code' => 'BF',
                'name' => 'Burkina Faso',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            22 => 
            array (
                'code' => 'BG',
                'name' => 'Bulgaria',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            23 => 
            array (
                'code' => 'BH',
                'name' => 'Bahrain',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            24 => 
            array (
                'code' => 'BI',
                'name' => 'Burundi',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            25 => 
            array (
                'code' => 'BJ',
                'name' => 'Benin',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            26 => 
            array (
                'code' => 'BM',
                'name' => 'Bermuda',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            27 => 
            array (
                'code' => 'BN',
                'name' => 'Brunei Darussalam',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            28 => 
            array (
                'code' => 'BO',
                'name' => 'Bolivia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            29 => 
            array (
                'code' => 'BR',
                'name' => 'Brazil',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            30 => 
            array (
                'code' => 'BS',
                'name' => 'Bahamas',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            31 => 
            array (
                'code' => 'BT',
                'name' => 'Bhutan',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            32 => 
            array (
                'code' => 'BV',
                'name' => 'Bouvet Island',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            33 => 
            array (
                'code' => 'BW',
                'name' => 'Botswana',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            34 => 
            array (
                'code' => 'BY',
                'name' => 'Belarus',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            35 => 
            array (
                'code' => 'BZ',
                'name' => 'Belize',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            36 => 
            array (
                'code' => 'AD',
                'name' => 'AndorrA',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            37 => 
            array (
                'code' => 'CC',
            'name' => 'Cocos (Keeling) Islands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            38 => 
            array (
                'code' => 'CD',
                'name' => 'Congo, The Democratic Republic of the',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            39 => 
            array (
                'code' => 'CF',
                'name' => 'Central African Republic',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            40 => 
            array (
                'code' => 'CG',
                'name' => 'Congo',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            41 => 
            array (
                'code' => 'AF',
                'name' => 'Afghanistan',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            42 => 
            array (
                'code' => 'CI',
                'name' => 'Cote D\'Ivoire',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            43 => 
            array (
                'code' => 'CK',
                'name' => 'Cook Islands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            44 => 
            array (
                'code' => 'CL',
                'name' => 'Chile',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            45 => 
            array (
                'code' => 'CM',
                'name' => 'Cameroon',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            46 => 
            array (
                'code' => 'CN',
                'name' => 'China',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            47 => 
            array (
                'code' => 'CO',
                'name' => 'Colombia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            48 => 
            array (
                'code' => 'CR',
                'name' => 'Costa Rica',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            49 => 
            array (
                'code' => 'CS',
                'name' => 'Serbia and Montenegro',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            50 => 
            array (
                'code' => 'CU',
                'name' => 'Cuba',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            51 => 
            array (
                'code' => 'CV',
                'name' => 'Cape Verde',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            52 => 
            array (
                'code' => 'CX',
                'name' => 'Christmas Island',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            53 => 
            array (
                'code' => 'CY',
                'name' => 'Cyprus',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            54 => 
            array (
                'code' => 'CZ',
                'name' => 'Czech Republic',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            55 => 
            array (
                'code' => 'DE',
                'name' => 'Germany',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            56 => 
            array (
                'code' => 'DJ',
                'name' => 'Djibouti',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            57 => 
            array (
                'code' => 'DK',
                'name' => 'Denmark',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            58 => 
            array (
                'code' => 'DM',
                'name' => 'Dominica',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            59 => 
            array (
                'code' => 'DO',
                'name' => 'Dominican Republic',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            60 => 
            array (
                'code' => 'DZ',
                'name' => 'Algeria',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            61 => 
            array (
                'code' => 'EC',
                'name' => 'Ecuador',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            62 => 
            array (
                'code' => 'EE',
                'name' => 'Estonia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            63 => 
            array (
                'code' => 'EG',
                'name' => 'Egypt',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            64 => 
            array (
                'code' => 'EH',
                'name' => 'Western Sahara',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            65 => 
            array (
                'code' => 'ER',
                'name' => 'Eritrea',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            66 => 
            array (
                'code' => 'ES',
                'name' => 'Spain',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            67 => 
            array (
                'code' => 'ET',
                'name' => 'Ethiopia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            68 => 
            array (
                'code' => 'FI',
                'name' => 'Finland',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            69 => 
            array (
                'code' => 'FJ',
                'name' => 'Fiji',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            70 => 
            array (
                'code' => 'FK',
            'name' => 'Falkland Islands (Malvinas)',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            71 => 
            array (
                'code' => 'FM',
                'name' => 'Micronesia, Federated States of',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            72 => 
            array (
                'code' => 'FO',
                'name' => 'Faroe Islands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            73 => 
            array (
                'code' => 'AE',
                'name' => 'United Arab Emirates',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            74 => 
            array (
                'code' => 'GA',
                'name' => 'Gabon',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            75 => 
            array (
                'code' => 'GB',
                'name' => 'United Kingdom',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            76 => 
            array (
                'code' => 'GD',
                'name' => 'Grenada',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            77 => 
            array (
                'code' => 'GE',
                'name' => 'Georgia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            78 => 
            array (
                'code' => 'GF',
                'name' => 'French Guiana',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            79 => 
            array (
                'code' => 'GG',
                'name' => 'Guernsey',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            80 => 
            array (
                'code' => 'GH',
                'name' => 'Ghana',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            81 => 
            array (
                'code' => 'GI',
                'name' => 'Gibraltar',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            82 => 
            array (
                'code' => 'GL',
                'name' => 'Greenland',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            83 => 
            array (
                'code' => 'GM',
                'name' => 'Gambia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            84 => 
            array (
                'code' => 'GN',
                'name' => 'Guinea',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            85 => 
            array (
                'code' => 'GP',
                'name' => 'Guadeloupe',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            86 => 
            array (
                'code' => 'GQ',
                'name' => 'Equatorial Guinea',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            87 => 
            array (
                'code' => 'GR',
                'name' => 'Greece',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            88 => 
            array (
                'code' => 'GS',
                'name' => 'South Georgia and the South Sandwich Islands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            89 => 
            array (
                'code' => 'GT',
                'name' => 'Guatemala',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            90 => 
            array (
                'code' => 'GU',
                'name' => 'Guam',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            91 => 
            array (
                'code' => 'GW',
                'name' => 'Guinea-Bissau',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            92 => 
            array (
                'code' => 'GY',
                'name' => 'Guyana',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            93 => 
            array (
                'code' => 'HK',
                'name' => 'Hong Kong',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            94 => 
            array (
                'code' => 'HM',
                'name' => 'Heard Island and Mcdonald Islands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            95 => 
            array (
                'code' => 'HN',
                'name' => 'Honduras',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            96 => 
            array (
                'code' => 'HR',
                'name' => 'Croatia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            97 => 
            array (
                'code' => 'HT',
                'name' => 'Haiti',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            98 => 
            array (
                'code' => 'HU',
                'name' => 'Hungary',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            99 => 
            array (
                'code' => 'ID',
                'name' => 'Indonesia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            100 => 
            array (
                'code' => 'IE',
                'name' => 'Ireland',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            101 => 
            array (
                'code' => 'IL',
                'name' => 'Israel',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            102 => 
            array (
                'code' => 'IM',
                'name' => 'Isle of Man',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            103 => 
            array (
                'code' => 'IN',
                'name' => 'India',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            104 => 
            array (
                'code' => 'IO',
                'name' => 'British Indian Ocean Territory',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            105 => 
            array (
                'code' => 'IQ',
                'name' => 'Iraq',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            106 => 
            array (
                'code' => 'IR',
                'name' => 'Iran, Islamic Republic Of',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            107 => 
            array (
                'code' => 'IS',
                'name' => 'Iceland',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            108 => 
            array (
                'code' => 'IT',
                'name' => 'Italy',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            109 => 
            array (
                'code' => 'JE',
                'name' => 'Jersey',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            110 => 
            array (
                'code' => 'JM',
                'name' => 'Jamaica',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            111 => 
            array (
                'code' => 'JO',
                'name' => 'Jordan',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            112 => 
            array (
                'code' => 'JP',
                'name' => 'Japan',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            113 => 
            array (
                'code' => 'KE',
                'name' => 'Kenya',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            114 => 
            array (
                'code' => 'KG',
                'name' => 'Kyrgyzstan',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            115 => 
            array (
                'code' => 'KH',
                'name' => 'Cambodia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            116 => 
            array (
                'code' => 'KI',
                'name' => 'Kiribati',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            117 => 
            array (
                'code' => 'KM',
                'name' => 'Comoros',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            118 => 
            array (
                'code' => 'KN',
                'name' => 'Saint Kitts and Nevis',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            119 => 
            array (
                'code' => 'KP',
                'name' => 'Korea, Democratic People\'S Republic of',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            120 => 
            array (
                'code' => 'KR',
                'name' => 'Korea, Republic of',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            121 => 
            array (
                'code' => 'KW',
                'name' => 'Kuwait',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            122 => 
            array (
                'code' => 'KY',
                'name' => 'Cayman Islands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            123 => 
            array (
                'code' => 'KZ',
                'name' => 'Kazakhstan',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            124 => 
            array (
                'code' => 'LA',
                'name' => 'Lao People\'S Democratic Republic',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            125 => 
            array (
                'code' => 'LB',
                'name' => 'Lebanon',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            126 => 
            array (
                'code' => 'LC',
                'name' => 'Saint Lucia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            127 => 
            array (
                'code' => 'LI',
                'name' => 'Liechtenstein',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            128 => 
            array (
                'code' => 'LK',
                'name' => 'Sri Lanka',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            129 => 
            array (
                'code' => 'LR',
                'name' => 'Liberia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            130 => 
            array (
                'code' => 'LS',
                'name' => 'Lesotho',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            131 => 
            array (
                'code' => 'LT',
                'name' => 'Lithuania',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            132 => 
            array (
                'code' => 'LU',
                'name' => 'Luxembourg',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            133 => 
            array (
                'code' => 'LV',
                'name' => 'Latvia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            134 => 
            array (
                'code' => 'LY',
                'name' => 'Libyan Arab Jamahiriya',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            135 => 
            array (
                'code' => 'MA',
                'name' => 'Morocco',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            136 => 
            array (
                'code' => 'MC',
                'name' => 'Monaco',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            137 => 
            array (
                'code' => 'MD',
                'name' => 'Moldova, Republic of',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            138 => 
            array (
                'code' => 'MG',
                'name' => 'Madagascar',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            139 => 
            array (
                'code' => 'MH',
                'name' => 'Marshall Islands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            140 => 
            array (
                'code' => 'MK',
                'name' => 'Macedonia, The Former Yugoslav Republic of',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            141 => 
            array (
                'code' => 'ML',
                'name' => 'Mali',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            142 => 
            array (
                'code' => 'MM',
                'name' => 'Myanmar',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            143 => 
            array (
                'code' => 'MN',
                'name' => 'Mongolia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            144 => 
            array (
                'code' => 'MO',
                'name' => 'Macao',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            145 => 
            array (
                'code' => 'MP',
                'name' => 'Northern Mariana Islands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            146 => 
            array (
                'code' => 'MQ',
                'name' => 'Martinique',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            147 => 
            array (
                'code' => 'MR',
                'name' => 'Mauritania',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            148 => 
            array (
                'code' => 'MS',
                'name' => 'Montserrat',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            149 => 
            array (
                'code' => 'MT',
                'name' => 'Malta',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            150 => 
            array (
                'code' => 'MU',
                'name' => 'Mauritius',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            151 => 
            array (
                'code' => 'MV',
                'name' => 'Maldives',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            152 => 
            array (
                'code' => 'MW',
                'name' => 'Malawi',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            153 => 
            array (
                'code' => 'MX',
                'name' => 'Mexico',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            154 => 
            array (
                'code' => 'MY',
                'name' => 'Malaysia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            155 => 
            array (
                'code' => 'MZ',
                'name' => 'Mozambique',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            156 => 
            array (
                'code' => 'NA',
                'name' => 'Namibia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            157 => 
            array (
                'code' => 'NC',
                'name' => 'New Caledonia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            158 => 
            array (
                'code' => 'NE',
                'name' => 'Niger',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            159 => 
            array (
                'code' => 'NF',
                'name' => 'Norfolk Island',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            160 => 
            array (
                'code' => 'NG',
                'name' => 'Nigeria',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            161 => 
            array (
                'code' => 'NI',
                'name' => 'Nicaragua',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            162 => 
            array (
                'code' => 'NL',
                'name' => 'Netherlands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            163 => 
            array (
                'code' => 'NO',
                'name' => 'Norway',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            164 => 
            array (
                'code' => 'NP',
                'name' => 'Nepal',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            165 => 
            array (
                'code' => 'NR',
                'name' => 'Nauru',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            166 => 
            array (
                'code' => 'NU',
                'name' => 'Niue',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            167 => 
            array (
                'code' => 'NZ',
                'name' => 'New Zealand',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            168 => 
            array (
                'code' => 'OM',
                'name' => 'Oman',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            169 => 
            array (
                'code' => 'PA',
                'name' => 'Panama',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            170 => 
            array (
                'code' => 'PE',
                'name' => 'Peru',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            171 => 
            array (
                'code' => 'PF',
                'name' => 'French Polynesia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            172 => 
            array (
                'code' => 'PG',
                'name' => 'Papua New Guinea',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            173 => 
            array (
                'code' => 'PH',
                'name' => 'Philippines',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            174 => 
            array (
                'code' => 'PK',
                'name' => 'Pakistan',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            175 => 
            array (
                'code' => 'PL',
                'name' => 'Poland',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            176 => 
            array (
                'code' => 'PM',
                'name' => 'Saint Pierre and Miquelon',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            177 => 
            array (
                'code' => 'PN',
                'name' => 'Pitcairn',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            178 => 
            array (
                'code' => 'PR',
                'name' => 'Puerto Rico',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            179 => 
            array (
                'code' => 'PS',
                'name' => 'Palestinian Territory, Occupied',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            180 => 
            array (
                'code' => 'PT',
                'name' => 'Portugal',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            181 => 
            array (
                'code' => 'PW',
                'name' => 'Palau',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            182 => 
            array (
                'code' => 'PY',
                'name' => 'Paraguay',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            183 => 
            array (
                'code' => 'QA',
                'name' => 'Qatar',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            184 => 
            array (
                'code' => 'RE',
                'name' => 'Reunion',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            185 => 
            array (
                'code' => 'RO',
                'name' => 'Romania',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            186 => 
            array (
                'code' => 'RU',
                'name' => 'Russian Federation',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            187 => 
            array (
                'code' => 'RW',
                'name' => 'RWANDA',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            188 => 
            array (
                'code' => 'SA',
                'name' => 'Saudi Arabia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            189 => 
            array (
                'code' => 'SB',
                'name' => 'Solomon Islands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            190 => 
            array (
                'code' => 'SC',
                'name' => 'Seychelles',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            191 => 
            array (
                'code' => 'SD',
                'name' => 'Sudan',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            192 => 
            array (
                'code' => 'SE',
                'name' => 'Sweden',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            193 => 
            array (
                'code' => 'SG',
                'name' => 'Singapore',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            194 => 
            array (
                'code' => 'SH',
                'name' => 'Saint Helena',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            195 => 
            array (
                'code' => 'SI',
                'name' => 'Slovenia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            196 => 
            array (
                'code' => 'SJ',
                'name' => 'Svalbard and Jan Mayen',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            197 => 
            array (
                'code' => 'SK',
                'name' => 'Slovakia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            198 => 
            array (
                'code' => 'SL',
                'name' => 'Sierra Leone',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            199 => 
            array (
                'code' => 'SM',
                'name' => 'San Marino',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            200 => 
            array (
                'code' => 'SN',
                'name' => 'Senegal',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            201 => 
            array (
                'code' => 'SO',
                'name' => 'Somalia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            202 => 
            array (
                'code' => 'SR',
                'name' => 'Suriname',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            203 => 
            array (
                'code' => 'ST',
                'name' => 'Sao Tome and Principe',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            204 => 
            array (
                'code' => 'SV',
                'name' => 'El Salvador',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            205 => 
            array (
                'code' => 'SY',
                'name' => 'Syrian Arab Republic',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            206 => 
            array (
                'code' => 'SZ',
                'name' => 'Swaziland',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            207 => 
            array (
                'code' => 'TC',
                'name' => 'Turks and Caicos Islands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            208 => 
            array (
                'code' => 'TD',
                'name' => 'Chad',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            209 => 
            array (
                'code' => 'TF',
                'name' => 'French Southern Territories',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            210 => 
            array (
                'code' => 'TG',
                'name' => 'Togo',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            211 => 
            array (
                'code' => 'TH',
                'name' => 'Thailand',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            212 => 
            array (
                'code' => 'TJ',
                'name' => 'Tajikistan',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            213 => 
            array (
                'code' => 'TK',
                'name' => 'Tokelau',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            214 => 
            array (
                'code' => 'TL',
                'name' => 'Timor-Leste',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            215 => 
            array (
                'code' => 'TM',
                'name' => 'Turkmenistan',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            216 => 
            array (
                'code' => 'TN',
                'name' => 'Tunisia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            217 => 
            array (
                'code' => 'TO',
                'name' => 'Tonga',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            218 => 
            array (
                'code' => 'TR',
                'name' => 'Turkey',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            219 => 
            array (
                'code' => 'TT',
                'name' => 'Trinidad and Tobago',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            220 => 
            array (
                'code' => 'TV',
                'name' => 'Tuvalu',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            221 => 
            array (
                'code' => 'TW',
                'name' => 'Taiwan, Province of China',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            222 => 
            array (
                'code' => 'TZ',
                'name' => 'Tanzania, United Republic of',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            223 => 
            array (
                'code' => 'UA',
                'name' => 'Ukraine',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            224 => 
            array (
                'code' => 'UG',
                'name' => 'Uganda',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            225 => 
            array (
                'code' => 'UM',
                'name' => 'United States Minor Outlying Islands',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            226 => 
            array (
                'code' => 'AG',
                'name' => 'Antigua and Barbuda',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            227 => 
            array (
                'code' => 'UY',
                'name' => 'Uruguay',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            228 => 
            array (
                'code' => 'UZ',
                'name' => 'Uzbekistan',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            229 => 
            array (
                'code' => 'VA',
            'name' => 'Holy See (Vatican City State)',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            230 => 
            array (
                'code' => 'VC',
                'name' => 'Saint Vincent and the Grenadines',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            231 => 
            array (
                'code' => 'VE',
                'name' => 'Venezuela',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            232 => 
            array (
                'code' => 'VG',
                'name' => 'Virgin Islands, British',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            233 => 
            array (
                'code' => 'VI',
                'name' => 'Virgin Islands, U.S.',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            234 => 
            array (
                'code' => 'VN',
                'name' => 'Viet Nam',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            235 => 
            array (
                'code' => 'VU',
                'name' => 'Vanuatu',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            236 => 
            array (
                'code' => 'WF',
                'name' => 'Wallis and Futuna',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            237 => 
            array (
                'code' => 'WS',
                'name' => 'Samoa',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            238 => 
            array (
                'code' => 'YE',
                'name' => 'Yemen',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            239 => 
            array (
                'code' => 'YT',
                'name' => 'Mayotte',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            240 => 
            array (
                'code' => 'ZA',
                'name' => 'South Africa',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            241 => 
            array (
                'code' => 'ZM',
                'name' => 'Zambia',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
            242 => 
            array (
                'code' => 'ZW',
                'name' => 'Zimbabwe',
                'icon' => NULL,
                'is_active' => 1,
                'created_at' => now(),
            ),
        ));
        
        
    }
}