<?php

namespace pieces;

class Piece
{

    public $name;
    public $value;
    public $movable;
    public $ownerId;


    public function __construct(string $ownerId)
    {
        $this->$ownerId = $ownerId;
    }


    // TODO: These parameters are not final
    public function canMoveDistance($move_distance) : bool
    {

        if (!$this->movable) {
            return false;
        }

        if ($move_distance < 2) {
            return true;
        }

        return false;

    }

    // General hit method
    public function canHit(Piece $piece)
    {
        // Players cannot hit their own pieces.
        if ($this->ownerId === $piece->getOwnerId()) {
            return false;
        }
        // But we first need Player classes and then assign them to the pieces.
        if (is_numeric($this->value)) {
            if (is_numeric($piece->getValue())) {
                // TODO: Should this use intval?
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
            if (is_string($piece->getValue())) {
                $own = $this->value;
                $other = $piece->getValue();

                if ($other === 'F') {
                    // TODO: Win condition, make sure that this is handled properly
                    return true;
                }

                // Nobody can hit the bomb expect minor.
                // This is implemented on the minor class.
                if ($other == "B") {
                    return false;
                }

            } else {
                // Current piece is string value and the other one is numeric so we can hit (although
                // bombs and flags should never be the hitting one!).
                return true;
            }
        }

    }


    // GETTERS and SETTERS

    /**
     * @return string
     */
    public function getOwnerId() : string
    {
        return $this->ownerId;
    }

    /**
     * Set the id of the owner of the piece.
     * @param string $ownerId
     */
    public function setOwnerId(string $ownerId)
    {
        $this->ownerId = $ownerId;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMovable(): bool
    {
        return $this->movable;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public static function fromJson($json)
    {

        $subclasses = array();
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, 'Piece'))
                $subclasses[] = $class;
        }

        $jsonName = $json['name'];

        $piece = NULL;
        foreach ($subclasses as $subclass) {
            $t = new $subclass();
            if ($t->getName() === $jsonName) {
                $piece = $t;
            }
        }

        return $piece;
    }

}

class Flag extends Piece
{

    public $name = 'Flag';
    public $value = 'F';
    public $movable = false;

    public function canHit(Piece $piece) : bool
    {
        // Flags cannot hit anything.
        return false;
    }

}


class Bomb extends Piece
{

    public $name = 'Bomb';
    public $value = 'B';
    public $movable = false;

}

class Spy extends Piece
{

    public $name = 'Spy';
    public $value = '1';
    public $movable = true;

    public function canHit(Piece $piece)
    {
        // Spies can hit Marshals.
        if ($piece->getValue() == '10') {
            return true;
        }
        return parent::canHit($piece);
    }

}

class Scout extends Piece
{

    public $name = 'Scout';
    public $value = '2';
    public $movable = true;

    public function canMoveDistance($move_distance): bool
    {
        // Scouts can move as far as they want!
        // TODO: Find out if scouts can hit in the same turn as they moved.
        //       If not, handle it!
        return true;
    }


}

class Miner extends Piece
{

    public $name = 'Miner';
    public $value = '3';
    public $movable = true;

    function canHit(Piece $piece)
    {
        // Miners can hit bombs.
        if ($piece->getValue() === 'B') {
            return true;
        }
        // Let the parent handle the default cases.
        return parent::canHit($piece);
    }

}

class Sergeant extends Piece
{

    public $name = 'Sergeant';
    public $value = '4';
    public $movable = true;

}

class Lieutenant extends Piece
{

    public $name = 'Lieutenant';
    public $value = '5';
    public $movable = true;

}

class Captain extends Piece
{

    public $name = 'Captain';
    public $value = '6';
    public $movable = true;

}

class Major extends Piece
{

    public $name = 'Major';
    public $value = '7';
    public $movable = true;

}

class Colonel extends Piece
{

    public $name = 'Colonel';
    public $value = '8';
    public $movable = true;

}

class General extends Piece
{

    public $name = 'General';
    public $value = '9';
    public $movable = true;

}

class Marshal extends Piece
{

    public $name = 'Marshal';
    public $value = '10';
    public $movable = true;

}