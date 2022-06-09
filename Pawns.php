<?php

abstract class Piece
{

    public $name;
    public $value;
    public $movable;
    public $defeatsMarshal;
    public $movesMore;

    // TODO: These parameters are not final
    public function canMove($board, $move){}
    // General hit method
    public function canHit(Piece $piece)
    {
        if (is_numeric($this->value)) {
            if (is_numeric($piece->getValue())) {
                if ($this->value > $piece->getValue()) {
                    return true;
                } elseif ($this->value == $piece->getValue()) {
                    // TODO: Make sure the other piece also dies!
                    return true;
                } else {
                    return false;
                }
            } else {
                // The current piece is numeric so always lower than the other (str) pawns.
                // Note: there are some edge cases with this but that is implemented in their own class (see Miner)
                return false;
            }
        } else {
            // Both are strings
            if(is_string($piece->getValue())) {
               $own = $this->value;
               $other = $piece->getValue();

               if ($own === 'F') {
                   // TODO: Win condition, make sure that this
                   // is handled properly
                   return false;
               }

               // Nobody can hit the bomb expect minor.
               // This is implemented on the minor class.
               if ($other == "B") {
                   return false;
               }

           } else {
               // Current piece is string value and the other one is numeric so we can hit.
               return true;
           }
        }

    }
    public function getValue(){
        return $this->value;
    }


}

class Flag extends Piece {

    public $name = 'Flag';
    public $value = 'F';
    public $movable = false;
    public $defeatsMarshal = false;
    public $movesMore = false;

}

class Bomb extends Piece {

    public $name = 'Bomb';
    public $value = 'B';
    public $movable = false;
    public $defeatsMarshal = true;
    public $movesMore = false;

}
class Spy extends Piece {

    public $name = 'Spy';
    public $value = '1';
    public $movable = true;
    public $defeatsMarshal = true;
    public $movesMore = false;

}

class Scout extends Piece {

    public $name = 'Scout';
    public $value = '2';
    public $movable = true;
    public $defeatsMarshal = false;
    public $movesMore = true;

}

class Miner extends Piece {

    public $name = 'Miner';
    public $value = '3';
    public $movable = true;
    public $defeatsMarshal = false;
    public $movesMore = false;

    function canHit(Piece $piece)
    {
        if ($piece->getValue() === 'B') {
            return true;
        }
        // Let the parent handle the default cases.
        return parent::canHit($piece);
    }

}
class Sergeant extends Piece {

    public $name = 'Sergeant';
    public $value = '4';
    public $movable = true;
    public $defeatsMarshal = false;
    public $movesMore = false;

}

class Lieutenant extends Piece {

    public $name = 'Lieutenant';
    public $value = '5';
    public $movable = true;
    public $defeatsMarshal = false;
    public $movesMore = false;

}

class Captain extends Piece {

    public $name = 'Captain';
    public $value = '6';
    public $movable = true;
    public $defeatsMarshal = false;
    public $movesMore = false;

}

class Major extends Piece {

    public $name = 'Major';
    public $value = '7';
    public $movable = true;
    public $defeatsMarshal = false;
    public $movesMore = false;

}

class Colonel extends Piece {

    public $name = 'Colonel';
    public $value = '8';
    public $movable = true;
    public $defeatsMarshal = false;
    public $movesMore = false;

}

class General extends Piece {

    public $name = 'General';
    public $value = '9';
    public $movable = true;
    public $defeatsMarshal = false;
    public $movesMore = false;

}

class Marshal extends Piece {

    public $name = 'Marshal';
    public $value = '10';
    public $movable = true;
    public $defeatsMarshal = true;
    public $movesMore = false;

}