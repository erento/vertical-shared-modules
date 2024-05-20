<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

switch (SPINOFFID) {
    case 'sportauto':
        $btnColor = '#E0434C';
        break;
    case 'limo':
        $btnColor = '#B13660';
        break;
    case 'zelte':
        $btnColor = '#2A6BE8';
        break;
    case 'oldtimer':
        $btnColor = '#9A2332';
        break;
    case 'eventmodul':
        $btnColor = '#5337AA';
        break;
    default:
        $btnColor = '#111';
}
