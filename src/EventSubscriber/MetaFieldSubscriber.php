<?php

namespace App\EventSubscriber;

use App\Entity\ActivityMeta;
use App\Entity\CustomerMeta;
use App\Entity\EntityWithMetaFields;
use App\Entity\InvoiceMeta;
use App\Entity\MetaTableTypeInterface;
use App\Entity\ProjectMeta;
use App\Entity\TimesheetMeta;
use App\Event\ActivityMetaDefinitionEvent;
use App\Event\CustomerMetaDefinitionEvent;
use App\Event\InvoiceMetaDefinitionEvent;
use App\Event\ProjectMetaDefinitionEvent;
use App\Event\TimesheetMetaDefinitionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;

/*
* 
* https://www.kimai.org/documentation/meta-fields.html
* Add editable custom fields
* The following example adds a custom field to each entity type, which can be edited in the 
* “edit” and “create” forms:
* 
* Timesheet via TimesheetMeta
* Customer via CustomerMeta
* Project via ProjectMeta
* Activity via ActivityMeta
*
* This example supports all possible entity types and adds the new Location field. See in prepareEntity()
* what needs to be done to setup new custom fields, which can be edited by the user.
*/
class MetaFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            TimesheetMetaDefinitionEvent::class => ['loadTimesheetMeta', 200],
            //CustomerMetaDefinitionEvent::class => ['loadCustomerMeta', 200],
            //ProjectMetaDefinitionEvent::class => ['loadProjectMeta', 200],
            //ActivityMetaDefinitionEvent::class => ['loadActivityMeta', 200],
            //InvoiceMetaDefinitionEvent::class => ['loadInvoiceMeta', 200],
        ];
    }

    public function loadTimesheetMeta(TimesheetMetaDefinitionEvent $event): void
    {
        $this->prepareEntity($event->getEntity(), new TimesheetMeta());
    }

    public function loadCustomerMeta(CustomerMetaDefinitionEvent $event): void
    {
        $this->prepareEntity($event->getEntity(), new CustomerMeta());
    }

    public function loadProjectMeta(ProjectMetaDefinitionEvent $event): void
    {
        $this->prepareEntity($event->getEntity(), new ProjectMeta());
    }

    public function loadActivityMeta(ActivityMetaDefinitionEvent $event): void
    {
        $this->prepareEntity($event->getEntity(), new ActivityMeta());
    }

    public function loadInvoiceMeta(InvoiceMetaDefinitionEvent $event): void
    {
        $this->prepareEntity($event->getEntity(), new InvoiceMeta());
    }

    private function prepareEntity(EntityWithMetaFields $entity, MetaTableTypeInterface $definition): void
    {
        $definition->setLabel('External ID');
        // $definition->setOptions(['help' => 'Enter the place you work from here']);
        $definition->setName('externalId');
        $definition->setType(TextType::class);
        $definition->addConstraint(new Length(['max' => 255]));
        $definition->setIsVisible(true);

        $entity->setMetaField($definition);
    }
}