<?php

namespace Udoktor;

use Illuminate\Database\Eloquent\Model;

class MailSettings extends Model
{
    protected $table='mailsettings';
    protected $fillable=['header','footer','boldtitle','italicizedtitle','colortitle','sizetitle',
        'boldlabel','italicizedlabel','colorlabel','sizelabel','bolddetail','italicizeddetail',
        'colordetail','sizedetail',
        ];
    public $timestamps = false;
}
