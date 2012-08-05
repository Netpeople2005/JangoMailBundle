<?php

namespace Netpeople\JangoMailBundle\Recipients;

/**
 * Description of RecipientInterface
 *
 * @author manuel
 */
interface RecipientInterface
{

    public function getEmail();

    public function getName();
    
    public function setGroup(\Netpeople\JangoMailBundle\Groups\Group $group);
    
    public function getGroup();
}