<?php

namespace Netpeople\JangoMailBundle\Groups;

/**
 * Description of Group
 *
 * @author manuel
 */
class Group
{

    protected $name = '';

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
        return $this;
    }
}