<?php

class CardValidation {
    const RANKS = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
    const SUITS = ['S', 'H', 'D', 'C'];
    
    protected static $existedCard = array();
    
    public static function isValid($suit, $rank) {
        if (in_array($rank, self::RANKS) &&
            in_array($suit, self::SUITS) &&
            ! in_array($suit.$rank, self::$existedCard)) {
            self::$existedCard[] = $suit.$rank;
            return true;
        }
        return false;
    }
}

class Card {
    public $suit;
    public $rank;
    
    function __construct($suit, $rank) {
        if (CardValidation::isValid($suit, $rank)) {
            $this->suit = $suit;
            $this->rank = $rank;
        }else {
            throw new Exception('Duplicated Card');
        }
    }
}

class Hand {
    protected $cardList = array();
    
    protected $cardRankList = array();

    public function isValid() {
        return count($this->cardList) == 5;
    }
    
    public function addCard($card) {
        $this->cardList[] = $card;
        $this->cardRankList[] = $card->rank;
        return true;
    }
    
    public function getResult() {
        $res = '--';
        
        if ($this->is4C()) {
            $res = '4C';
        } else if ($this->isFH()) {
            $res = 'FH';
        } else if ($this->is3C()) {
            $res = '3C';
        } else if ($this->is2P()) {
            $res = '2P';
        } else if ($this->is1P()) {
            $res = '1P';
        }
        
        return $res;
    }
    
    protected function is4C() {
        $res = false;
        $countRank = array_count_values($this->cardRankList);
        
        foreach($countRank as $value)
        {
            if ($value === 4){
                $res = true;
                break;
            }
        }
        
        return $res;
    }
    
    protected function isFH() {
        $res = false;
        $countRank = array_count_values($this->cardRankList);
        
        foreach($countRank as $value)
        {
            if (count($countRank) == 2) {
                $ranks = array_keys($countRank);
                if (($countRank[$ranks[0]] == 2 && $countRank[$ranks[1]] == 3) ||
                    ($countRank[$ranks[0]] == 3 && $countRank[$ranks[1]] == 2))
                    $res = true;
            }
        }
        return $res;
    }
    
    protected function is3C() {
        $res = false;
        $countRank = array_count_values($this->cardRankList);
        
        foreach($countRank as $value)
        {
            if ($value === 3){
                $res = true;
                break;
            }
        }
        return $res;
    }
    
    protected function is2P() {
        $res = false;
        $countRank = array_count_values($this->cardRankList);
        
        foreach($countRank as $value)
        {
            if (count($countRank) == 3) {
                if ($value === 2){
                    $res = true;
                    break;
                }
            }
        }
        return $res;
    }
    
    protected function is1P() {
        $res = false;
        $countRank = array_count_values($this->cardRankList);
        
        foreach($countRank as $value)
        {
            if ($value === 2){
                $res = true;
                break;
            }
        }
        return $res;
    }
}

$input = 'D10C10C8D8S10';

$len = strlen($input);
$hand = new Hand();
for($i = 0; $i < $len; $i = $i + 2) {
    try {
        if($input[$i+1] == 1){
            if($input[$i+2] == 0){
                $rank = $input[$i+1].$input[$i+2];
                $hand->addCard(new Card($input[$i], $rank));
                $i++;
            }
        }else{
            $hand->addCard(new Card($input[$i], $input[$i+1]));   
        }
    } catch (Exception $e) {
        echo 'Invalid card';
        break;
    }
}

if ($hand->isValid()) {
    echo $hand->getResult();
}


