<?php

namespace App\Enums;

enum StatusPerkawinan: string
{
    case BELUM_KAWIN = "Belum Kawin";
    case KAWIN = "Kawin";
    case CERAI_HIDUP = "Cerai Hidup";
    case CERAI_MATI = "Cerai Mati";
}

