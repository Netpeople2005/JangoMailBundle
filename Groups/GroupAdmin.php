<?php

namespace Netpeople\JangoMailBundle\Groups;

use Exception;
use Netpeople\JangoMailBundle\Exception\JangoMailException;
use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\JangoMail;
use Netpeople\JangoMailBundle\Recipients\Recipient;
use SoapFault;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;

/**
 * Description of GroupManagement
 *
 * @author manuel
 */
class GroupAdmin implements ChoiceListInterface
{

    /**
     * Contiene el servicio JangoMail
     *
     * @var JangoMail 
     */
    protected $jangoMail;

    public function __construct(JangoMail $jangoMail)
    {
        $this->jangoMail = $jangoMail;
    }

    /**
     * Agrega un Grupo a jango
     * @param Group $group
     * @return boolean|Group el grupo creado ó false si hay error
     */
    public function addGroup(Group $group)
    {
        try {
            $response = $this->jangoMail->call('AddGroup', array(
                'GroupName' => $group->getName(),
            ));
            $response = preg_split('/\n/m', $response->AddGroupResult);
            if (0 == $response[0]) {
                return $group->setGroupID($response[2]);
            } else {
                $this->jangoMail->setError("No se pudo crear el Grupo");
            }
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
        return false;
    }

    /**
     * Este metodo no funciona del todo bien, ya que está creando un nuevo grupo
     * en ves de editar. No usar por ahora
     * 
     * @deprecated
     *
     * @param Group $group
     * @param type $newName
     * @return boolean 
     */
    public function editGroup(Group $group, $newName)
    {
        try {
            $response = $this->jangoMail->call('Groups_Rename', array(
                'OldGroupName' => $group->getName(),
                'NewGroupName' => $newName,
            ));
            $response = preg_split('/\n/m', $response->AddGroupResult);
            if (0 == $response[0]) {
                return $response[2]; //el id del grupo
            }
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
        return false;
    }

    /**
     * Obtiene un grupo a travez del ID del mismo.
     * @param string $groupID
     * @return Group|null el grupo encontrado ó nulo si no existe.
     */
    public function getGroupByGroupID($groupID)
    {
        foreach ($this->getGroups() as $group) {
            if ($groupID == $group->getGroupID()) {
                return $group;
            }
        }
        return null;
    }

    /**
     * Agrega Miembros a un grupo.
     * @param Group $group clase grupo con los miembros establecidos.
     * @return boolean 
     */
    public function addMembers(Group $group)
    {
        $params = array(
            'GroupName' => $group->getName(),
            'FieldNames' => array('Name'),
        );

        $result = true;

        /* @var $groupJango Group */
        $groupJango = $this->getMembers(new Group($group->getName()));
        if ($groupJango !== false) {
            $group->getRecipients()->removeIfExistInCollection($groupJango->getRecipients());
            foreach ($group->getRecipients() as $e) {
                $params['EmailAddress'] = $e->getEmail();
                $params['FieldValues'] = array($e->getName());
                try {
                    $response = $this->jangoMail->call('AddGroupMember', $params);
                    $response = preg_split('/\n/m', $response->AddGroupMemberResult);
                    if (!0 == $response[0]) {
                        $result = false;
                    }
                } catch (SoapFault $e) {
                    throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
                }
            }
            foreach ($groupJango->getRecipients() as $e) {
                $group->addRecipient($e); // insertamos los miembros que estan en jango al objeto.
            }
            if ($result) {
                return $group;
            } else {
                $this->jangoMail->setError("No se Pudieron Guardar todos los Destinatarios");
                return false;
            }
        } else {
            $this->jangoMail->setError("No se pudo obtener la Información del Grupo en Jango");
            return false;
        }
    }

    /**
     * Devuelve el numero de miebros que posee un grupo
     * @param Group $group
     * @return boolean 
     */
    public function countMembers(Group $group)
    {
        try {
            $response = $this->jangoMail->call('GetGroupMemberCount', array(
                'GroupName' => $group->getName(),
            ));
            return (int) $response->GetGroupMemberCountResult;
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
        return false;
    }

    /**
     *
     * @return boolean 
     */
    public function getGroups()
    {
        try {
            $response = $this->jangoMail->call('Groups_GetList_XML');
            $xml = $response->Groups_GetList_XMLResult->any;
            $groups = array();
            foreach (simplexml_load_string($xml)->Groups as $e) {
                $group = new Group();
                $groups[] = $group->setName((string) $e->GroupName)
                        ->setGroupID((string) $e->GroupID)
                        ->setMemberCount((string) $e->MemberCount);
            }
            return $groups;
        } catch (Exception $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
        return false;
    }

    /**
     *
     * @return boolean 
     */
    public function getMembers(Group $group)
    {
        try {
            $response = $this->jangoMail
                    ->call('Groups_GetMembers_XML', array(
                'GroupName' => $group->getName()
            ));
            $xml = $response->Groups_GetMembers_XMLResult->any;
            foreach (simplexml_load_string($xml)->GroupMembers as $e) {
                $recipient = new Recipient((string) $e->emailaddress);
                $group->addRecipient($recipient);
            }
            return $group;
        } catch (Exception $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
        return false;
    }

    /**
     * Metodo Usado para devolver un arreglo con los grupos disponibles en
     * JangoMail.
     * 
     * @return array 
     */
    public function getChoices()
    {
        $choices = array();
//        foreach ($this->getGroups() as $group) {
//            $choices[$group->getName()] = $group->getName();
//        }
        $choices['hola'] = 'hola';
        $choices['chao'] = 'chao';
        return $choices;
    }

    public function getChoicesForValues(array $values)
    {
        
    }

    public function getIndicesForChoices(array $choices)
    {
        
    }

    public function getIndicesForValues(array $values)
    {
        
    }

    public function getPreferredViews()
    {
        
    }

    public function getRemainingViews()
    {
        
    }

    public function getValues()
    {
        
    }

    public function getValuesForChoices(array $choices)
    {
        
    }

}
