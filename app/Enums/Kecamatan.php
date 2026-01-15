<?php

namespace App\Enums;

enum Kecamatan: string
{
    case GERUNG = 'Gerung';
    case KEDIRI = 'Kediri';
    case NARMADA = 'Narmada';
    case SEKOTONG = 'Sekotong';
    case LABU_API = 'Labu Api';
    case GUNUNG_SARI = 'Gunung Sari';
    case LINGSAR = 'Lingsar';
    case LEMBAR = 'Lembar';
    case BATU_LAYAR = 'Batu Layar';
    case KURIPAN = 'Kuripan';

    public function desaKelurahan(): array
    {
        return match ($this) {
            self::GERUNG => ['Babussalam','Banyu Urip','Beleke','Dasan Tapen','Gapuk','Giri Tembesi','Kebun Ayu','Mesanggok','Suka Makmur','Taman Ayu','Tempos','Dasan Geres','Gerung Selatan','Gerung Utara'],
            self::KEDIRI => ['Banyumulek','Dasan Baru','Gelogor','Jagaraga Indah','Kediri','Kediri Selatan','Lelede','Montong Are','Ombe Baru','Rumak'],
            self::NARMADA => ['Badrain','Batu Kuta','Buwun Sejati','Dasan Tereng','Gerimak Indah','Golong','Krama Jaya','Lebah Sempaga','Lembuak','Mekarsari','Narmada','Nyur Lembang','Pakuan','Peresak','Peru','Sedau','Selat','Sembung','Sesait','Suranadi','Tanak Beak'],
            self::SEKOTONG => ['Batu Putih','Buwun Mas','Cendi Manik','Gili Gede Indah','Kedaro','Pelangan','Sekotong Barat','Sekotong Tengah','Taman Baru'],
            self::LABU_API => ['Bagik Polak','Bagik Polak Barat','Bajur','Bengkel','Karang Bongkot','Kuranji','Kuranji Dalang','Labu Api','Merembu','Perampuan','Telagawaru','Terong Tawah'],
            self::GUNUNG_SARI => ['Bukittinggi','Dopang','Gelanggsar','Guntur Macan','Gunung Sari','Jatisela','Jeringo','Kekait','Kekeri','Mekarsari','Midang','Nambalan','Penimbung','Ranjok','Sesela','Taman Sari'],
            self::LINGSAR => ['Batu Kumbung','Batu Mekar','Bug-Bug','Dasan Geria','Duman','Gegelung','Gegerung','Giri Madia','Gontoran','Karang Bayan','Langko','Lingsar','Peteluan Indah','Saribaye','Sigerongan'],
            self::LEMBAR => ['Eyat Mayang','Jembatan Gantung','Jembatan Kembar','Jembatan Kembar Timur','Labuan Kereng','Lembar','Lembar Selatan','Mareje','Mareje Timur','Sekotong Timur'],
            self::BATU_LAYAR => ['Batu Layar','Batu Layar Barat','Bengkaung','Lembah Sari','Meninting','Pusuk Lestari','Sandik','Senggigi','Senteluk'],
            self::KURIPAN => ['Giri Sasak','Jagaraga','Kuripan','Kuripan Selatan','Kuripan Timur','Kuripan Utara'],
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($c) => [$c->value => $c->value])
            ->toArray();
    }
}
