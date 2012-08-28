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
        if ($recipient->getEmail()) {
            foreach ($this->toArray() as $element) {
                if ($recipient->getEmail() === $element->getEmail()) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function removeIfExistInCollection(RecipientsCollection $recipients)
    {
        foreach ($this->toArray() as $recipient) {
            if ($recipients->contains($recipient)) {
                $this->removeElement($recipient);
            }
        }
    }

}