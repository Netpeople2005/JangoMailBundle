<?php

namespace Netpeople\JangoMailBundle\Recipients;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of RecipientsCollection
 *
 * @author maguirre
 */
class RecipientsCollection extends ArrayCollection
{

    public function contains($recipient)
    {
        if (!$recipient->getEmail()) {
            foreach ($this->_elements as $element) {
                if ($recipient->getEmail() === $element->getEmail()) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

}