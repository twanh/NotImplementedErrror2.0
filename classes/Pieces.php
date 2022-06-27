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
        $this->ownerId = $ownerId;
    }


    /**
     * Checks if the Piece can move a certain distance.
     * @param number $move_distance The distance the piece wants to move.
     * @return bool If the piece can move the wanted distance.
     */
    public function canMoveDistance($move_distance) : bool
    {

        if (!$this->movable) {
            return false;
        }

        if ($move_distance < 2) {
            return true;
        }

        // Only the scout can move more than 1.
        // this is implemented on the scouts own class.
        return false;

    }


    /**
     * Checks if the current piece (the current instance of the class) can hit another piece.
     * @param Piece $piece The piece to hit.
     * @return bool If the other piece can be hit.
     */
    public function canHit(Piece $piece): bool
    {
        // Players cannot hit their own pieces.
        if ($this->ownerId === $piece->getOwnerId()) {
            return false;
        }
        // Pieces can have a numeric or string value (e.g: Flag -> 'F')
        // pieces that have a numeric value lose from pieces with a string value (exceptions to this rule
        // are handled on the specific subclasses).
        // When both pieces their value is numeric the one with the higher value wins (exceptions are again handled in
        //the specific subclasses (e.g: see Spy).
        if (is_numeric($this->value)) {
            if (is_numeric($piece->getValue())) {
                if ($this->value > $piece->getValue()) {
                    return true;
                } elseif ($this->value == $piece->getValue()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            // Both are strings
            if (is_string($piece->getValue())) {
                $other = $piece->getValue();

                if ($other === 'F') {
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

        return false;

    }


    // GETTERS and SETTERS

    /**
     * Returns the owner id of the piece.
     * @return string The owner id.
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

    /**
     * Returns the value of the piece.
     * @return string The value of the piece.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns the name of the piece.
     * @return string The name of the piece.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns if the piece can move.
     * @return bool If the piece can move.
     */
    public function getMovable(): bool
    {
        return $this->movable;
    }

    /**
     * Set the name of the piece.
     * @param $name string The name of the piece.
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Takes a piece name and creates an instance of the piece with that name.
     * @param $name string The name of the piece (e.g.: Miner)
     * @param $ownerId string The owner id to assign to the new piece.
     * @return Piece The newly created piece instance that corresponds to the given name of the piece.
     */
    public static function fromPieceName($name, $ownerId) : Piece
    {

        $subclasses = [
            new Bomb($ownerId),
            new Captain($ownerId),
            new Colonel($ownerId),
            new Flag($ownerId),
            new General($ownerId),
            new Lieutenant($ownerId),
            new Major($ownerId),
            new Marshal($ownerId),
            new Miner($ownerId),
            new Scout($ownerId),
            new Sergeant($ownerId),
            new Spy($ownerId),
        ];

        $piece = NULL;
        foreach ($subclasses as $subclass) {
            if ($subclass->getName() === $name) {
                $piece = $subclass;
            }
        }

        return $piece;

    }

    /**
     * Creates a piece based on the given json array.
     *
     * This functions main purpose is to be able to load the database (a json file) and be able to regenerate
     * all classes stored as json so that all functionality of these classes can be used (think of hitting, moving, etc).
     *
     * @param $json array The array returned from the json database. The array should at least contain the piece name and
     *  owner id.
     * @return Piece The new instance piece created from the given (json) array.
     */
    public static function fromJson($json)
    {
        $piece = Piece::fromPieceName($json['name'], $json['ownerId']);
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

    /**
     * Custom implementation of the spy's hitting method because it can hit the Marshal.
     *
     * @param Piece $piece The piece to hit.
     * @return bool If the spy can hit this piece.
     */
    public function canHit(Piece $piece) : bool
    {
        // Spies can hit Marshals.
        if ($piece->getValue() == '10') {
            return true;
        }
        // If the other piece is not the marshal normal rules apply.
        return parent::canHit($piece);
    }

}

class Scout extends Piece
{

    public $name = 'Scout';
    public $value = '2';
    public $movable = true;

    /**
     * Scouts can move as far as they want so this always returns true.
     * @param $move_distance int The distance wanting to move.
     * @return bool Always true -- scouts can move as far as they want.
     */
    public function canMoveDistance($move_distance): bool
    {
        // Scouts can move as far as they want!
        return true;
    }


}

class Miner extends Piece
{

    public $name = 'Miner';
    public $value = '3';
    public $movable = true;

    /**
     * Custom implementation of canHit method for the Miner because it can hit bombs.
     * @param Piece $piece The piece to hit.
     * @return bool If the Miner can hit the other piece.
     */
    function canHit(Piece $piece) : bool
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
