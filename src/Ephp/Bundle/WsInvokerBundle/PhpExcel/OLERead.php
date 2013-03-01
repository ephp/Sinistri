<?php

namespace Ephp\Bundle\WsInvokerBundle\PhpExcel;

class OLERead {

    var $data = '';

    function OLERead() {
        
    }

    function read($sFileName) {
        // check if file exist and is readable (Darko Miljanovic)
        if (!is_readable($sFileName)) {
            $this->error = 1;
            return false;
        }
        $this->data = @file_get_contents($sFileName);
        if (!$this->data) {
            $this->error = 1;
            return false;
        }
        if (substr($this->data, 0, 8) != Utility::identifierOle()) {
            \Ephp\UtilityBundle\Utility\Log::pr(substr($this->data, 0, 8), true);
            \Ephp\UtilityBundle\Utility\Log::pr(Utility::identifierOle());
            $this->error = 1;
            return false;
        }
        $this->numBigBlockDepotBlocks = Utility::GetInt4d($this->data, Utility::$NUM_BIG_BLOCK_DEPOT_BLOCKS_POS);
        $this->sbdStartBlock = Utility::GetInt4d($this->data, Utility::$SMALL_BLOCK_DEPOT_BLOCK_POS);
        $this->rootStartBlock = Utility::GetInt4d($this->data, Utility::$ROOT_START_BLOCK_POS);
        $this->extensionBlock = Utility::GetInt4d($this->data, Utility::$EXTENSION_BLOCK_POS);
        $this->numExtensionBlocks = Utility::GetInt4d($this->data, Utility::$NUM_EXTENSION_BLOCK_POS);

        $bigBlockDepotBlocks = array();
        $pos = Utility::$BIG_BLOCK_DEPOT_BLOCKS_POS;
        $bbdBlocks = $this->numBigBlockDepotBlocks;
        if ($this->numExtensionBlocks != 0) {
            $bbdBlocks = (Utility::$BIG_BLOCK_SIZE - Utility::$BIG_BLOCK_DEPOT_BLOCKS_POS) / 4;
        }

        for ($i = 0; $i < $bbdBlocks; $i++) {
            $bigBlockDepotBlocks[$i] = Utility::GetInt4d($this->data, $pos);
            $pos += 4;
        }


        for ($j = 0; $j < $this->numExtensionBlocks; $j++) {
            $pos = ($this->extensionBlock + 1) * Utility::$BIG_BLOCK_SIZE;
            $blocksToRead = min($this->numBigBlockDepotBlocks - $bbdBlocks, Utility::$BIG_BLOCK_SIZE / 4 - 1);

            for ($i = $bbdBlocks; $i < $bbdBlocks + $blocksToRead; $i++) {
                $bigBlockDepotBlocks[$i] = Utility::GetInt4d($this->data, $pos);
                $pos += 4;
            }

            $bbdBlocks += $blocksToRead;
            if ($bbdBlocks < $this->numBigBlockDepotBlocks) {
                $this->extensionBlock = Utility::GetInt4d($this->data, $pos);
            }
        }

        // readBigBlockDepot
        $pos = 0;
        $index = 0;
        $this->bigBlockChain = array();

        for ($i = 0; $i < $this->numBigBlockDepotBlocks; $i++) {
            $pos = ($bigBlockDepotBlocks[$i] + 1) * Utility::$BIG_BLOCK_SIZE;
            //echo "pos = $pos";
            for ($j = 0; $j < Utility::$BIG_BLOCK_SIZE / 4; $j++) {
                $this->bigBlockChain[$index] = Utility::GetInt4d($this->data, $pos);
                $pos += 4;
                $index++;
            }
        }

        // readSmallBlockDepot();
        $pos = 0;
        $index = 0;
        $sbdBlock = $this->sbdStartBlock;
        $this->smallBlockChain = array();

        while ($sbdBlock != -2) {
            $pos = ($sbdBlock + 1) * Utility::$BIG_BLOCK_SIZE;
            for ($j = 0; $j < Utility::$BIG_BLOCK_SIZE / 4; $j++) {
                $this->smallBlockChain[$index] = Utility::GetInt4d($this->data, $pos);
                $pos += 4;
                $index++;
            }
            $sbdBlock = $this->bigBlockChain[$sbdBlock];
        }


        // readData(rootStartBlock)
        $block = $this->rootStartBlock;
        $pos = 0;
        $this->entry = $this->__readData($block);
        $this->__readPropertySets();
    }

    function __readData($bl) {
        $block = $bl;
        $pos = 0;
        $data = '';
        while ($block != -2) {
            $pos = ($block + 1) * Utility::$BIG_BLOCK_SIZE;
            $data = $data . substr($this->data, $pos, Utility::$BIG_BLOCK_SIZE);
            $block = $this->bigBlockChain[$block];
        }
        return $data;
    }

    function __readPropertySets() {
        $offset = 0;
        while ($offset < strlen($this->entry)) {
            $d = substr($this->entry, $offset, Utility::$PROPERTY_STORAGE_BLOCK_SIZE);
            $nameSize = ord($d[Utility::$SIZE_OF_NAME_POS]) | (ord($d[Utility::$SIZE_OF_NAME_POS + 1]) << 8);
            $type = ord($d[Utility::$TYPE_POS]);
            $startBlock = Utility::GetInt4d($d, Utility::$START_BLOCK_POS);
            $size = Utility::GetInt4d($d, Utility::$SIZE_POS);
            $name = '';
            for ($i = 0; $i < $nameSize; $i++) {
                $name .= $d[$i];
            }
            $name = str_replace("\x00", "", $name);
            $this->props[] = array(
                'name' => $name,
                'type' => $type,
                'startBlock' => $startBlock,
                'size' => $size);
            if ((strtolower($name) == "workbook") || ( strtolower($name) == "book")) {
                $this->wrkbook = count($this->props) - 1;
            }
            if ($name == "Root Entry") {
                $this->rootentry = count($this->props) - 1;
            }
            $offset += Utility::$PROPERTY_STORAGE_BLOCK_SIZE;
        }
    }

    function getWorkBook() {
        if ($this->props[$this->wrkbook]['size'] < Utility::$SMALL_BLOCK_THRESHOLD) {
            $rootdata = $this->__readData($this->props[$this->rootentry]['startBlock']);
            $streamData = '';
            $block = $this->props[$this->wrkbook]['startBlock'];
            $pos = 0;
            while ($block != -2) {
                $pos = $block * Utility::$SMALL_BLOCK_SIZE;
                $streamData .= substr($rootdata, $pos, Utility::$SMALL_BLOCK_SIZE);
                $block = $this->smallBlockChain[$block];
            }
            return $streamData;
        } else {
            $numBlocks = $this->props[$this->wrkbook]['size'] / Utility::$BIG_BLOCK_SIZE;
            if ($this->props[$this->wrkbook]['size'] % Utility::$BIG_BLOCK_SIZE != 0) {
                $numBlocks++;
            }

            if ($numBlocks == 0)
                return '';
            $streamData = '';
            $block = $this->props[$this->wrkbook]['startBlock'];
            $pos = 0;
            while ($block != -2) {
                $pos = ($block + 1) * Utility::$BIG_BLOCK_SIZE;
                $streamData .= substr($this->data, $pos, Utility::$BIG_BLOCK_SIZE);
                $block = $this->bigBlockChain[$block];
            }
            return $streamData;
        }
    }

}
