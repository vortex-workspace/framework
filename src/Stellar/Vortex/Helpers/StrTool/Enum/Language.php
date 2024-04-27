<?php

namespace Stellar\Vortex\Helpers\StrTool\Enum;

use Doctrine\Inflector\Language as DoctrineLanguage;

enum Language: string
{
    case ENGLISH = DoctrineLanguage::ENGLISH;
    case FRENCH = DoctrineLanguage::FRENCH;
    case NORWEGIAN_BOKMAL = DoctrineLanguage::NORWEGIAN_BOKMAL;
    case PORTUGUESE = DoctrineLanguage::PORTUGUESE;
    case SPANISH = DoctrineLanguage::SPANISH;
    case TURKISH = DoctrineLanguage::TURKISH;
}
