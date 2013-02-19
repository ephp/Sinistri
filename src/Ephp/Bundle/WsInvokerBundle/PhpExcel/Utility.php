<?php

namespace Ephp\Bundle\WsInvokerBundle\PhpExcel;

class Utility {

    public static $NUM_BIG_BLOCK_DEPOT_BLOCKS_POS = 0x2c; //ok
    public static $SMALL_BLOCK_DEPOT_BLOCK_POS = 0x3c; //ok
    public static $ROOT_START_BLOCK_POS = 0x30; //ok
    public static $BIG_BLOCK_SIZE = 0x200; //ok
    public static $SMALL_BLOCK_SIZE = 0x40; //ok
    public static $EXTENSION_BLOCK_POS = 0x44; //ok
    public static $NUM_EXTENSION_BLOCK_POS = 0x48; //ok
    public static $PROPERTY_STORAGE_BLOCK_SIZE = 0x80; //ok
    public static $BIG_BLOCK_DEPOT_BLOCKS_POS = 0x4c; //ok
    public static $SMALL_BLOCK_THRESHOLD = 0x1000; //ok
// property storage offsets
    public static $SIZE_OF_NAME_POS = 0x40; //ok
    public static $TYPE_POS = 0x42; //ok
    public static $START_BLOCK_POS = 0x74; //ok
    public static $SIZE_POS = 0x78;
// property spreadsheet
    public static $SPREADSHEET_EXCEL_READER_BIFF8 = 0x600;
    public static $SPREADSHEET_EXCEL_READER_BIFF7 = 0x500;
    public static $SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS = 0x5;
    public static $SPREADSHEET_EXCEL_READER_WORKSHEET = 0x10;
    public static $SPREADSHEET_EXCEL_READER_TYPE_BOF = 0x809;
    public static $SPREADSHEET_EXCEL_READER_TYPE_EOF = 0x0a;
    public static $SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET = 0x85;
    public static $SPREADSHEET_EXCEL_READER_TYPE_DIMENSION = 0x200;
    public static $SPREADSHEET_EXCEL_READER_TYPE_ROW = 0x208;
    public static $SPREADSHEET_EXCEL_READER_TYPE_DBCELL = 0xd7;
    public static $SPREADSHEET_EXCEL_READER_TYPE_FILEPASS = 0x2f;
    public static $SPREADSHEET_EXCEL_READER_TYPE_NOTE = 0x1c;
    public static $SPREADSHEET_EXCEL_READER_TYPE_TXO = 0x1b6;
    public static $SPREADSHEET_EXCEL_READER_TYPE_RK = 0x7e;
    public static $SPREADSHEET_EXCEL_READER_TYPE_RK2 = 0x27e;
    public static $SPREADSHEET_EXCEL_READER_TYPE_MULRK = 0xbd;
    public static $SPREADSHEET_EXCEL_READER_TYPE_MULBLANK = 0xbe;
    public static $SPREADSHEET_EXCEL_READER_TYPE_INDEX = 0x20b;
    public static $SPREADSHEET_EXCEL_READER_TYPE_SST = 0xfc;
    public static $SPREADSHEET_EXCEL_READER_TYPE_EXTSST = 0xff;
    public static $SPREADSHEET_EXCEL_READER_TYPE_CONTINUE = 0x3c;
    public static $SPREADSHEET_EXCEL_READER_TYPE_LABEL = 0x204;
    public static $SPREADSHEET_EXCEL_READER_TYPE_LABELSST = 0xfd;
    public static $SPREADSHEET_EXCEL_READER_TYPE_NUMBER = 0x203;
    public static $SPREADSHEET_EXCEL_READER_TYPE_NAME = 0x18;
    public static $SPREADSHEET_EXCEL_READER_TYPE_ARRAY = 0x221;
    public static $SPREADSHEET_EXCEL_READER_TYPE_STRING = 0x207;
    public static $SPREADSHEET_EXCEL_READER_TYPE_FORMULA = 0x406;
    public static $SPREADSHEET_EXCEL_READER_TYPE_FORMULA2 = 0x6;
    public static $SPREADSHEET_EXCEL_READER_TYPE_FORMAT = 0x41e;
    public static $SPREADSHEET_EXCEL_READER_TYPE_XF = 0xe0;
    public static $SPREADSHEET_EXCEL_READER_TYPE_BOOLERR = 0x205;
    public static $SPREADSHEET_EXCEL_READER_TYPE_FONT = 0x0031;
    public static $SPREADSHEET_EXCEL_READER_TYPE_PALETTE = 0x0092;
    public static $SPREADSHEET_EXCEL_READER_TYPE_UNKNOWN = 0xffff;
    public static $SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR = 0x22;
    public static $SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS = 0xE5;
    public static $SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS = 25569;
    public static $SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904 = 24107;
    public static $SPREADSHEET_EXCEL_READER_MSINADAY = 86400;
    public static $SPREADSHEET_EXCEL_READER_TYPE_HYPER = 0x01b8;
    public static $SPREADSHEET_EXCEL_READER_TYPE_COLINFO = 0x7d;
    public static $SPREADSHEET_EXCEL_READER_TYPE_DEFCOLWIDTH = 0x55;
    public static $SPREADSHEET_EXCEL_READER_TYPE_STANDARDWIDTH = 0x99;
    public static $SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT = "%s";

    public static function identifierOle() {
        return pack("CCCCCCCC", 0xd0, 0xcf, 0x11, 0xe0, 0xa1, 0xb1, 0x1a, 0xe1);
    }

    public static function GetInt4d($data, $pos) {
        $value = ord($data[$pos]) | (ord($data[$pos + 1]) << 8) | (ord($data[$pos + 2]) << 16) | (ord($data[$pos + 3]) << 24);
        if ($value >= 4294967294) {
            $value = -2;
        }
        return $value;
    }

// http://uk.php.net/manual/en/public static function.getdate.php
    public static function gmgetdate($ts = null) {
        $k = array('seconds', 'minutes', 'hours', 'mday', 'wday', 'mon', 'year', 'yday', 'weekday', 'month', 0);
        return(Utility::array_comb($k, explode(":", gmdate('s:i:G:j:w:n:Y:z:l:F:U', is_null($ts) ? time() : $ts))));
    }

// Added for PHP4 compatibility
    public static function array_comb($array1, $array2) {
        $out = array();
        foreach ($array1 as $key => $value) {
            $out[$value] = $array2[$key];
        }
        return $out;
    }

    public static function v($data, $pos) {
        return ord($data[$pos]) | ord($data[$pos + 1]) << 8;
    }
}
