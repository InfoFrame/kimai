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
* 
* Display and export custom fields
* Custom-fields can be shown as new columns in the data-tables for timesheets, customers,
* projects and activities. Additionally, these fields will be added to HTML and Spreadsheet 
* exports.
* As Kimai cannot query all existing records for possible custom fields, you need to listen 
* to new events and register the desired fields. 
*
*/
class MetaFieldDisplaySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            TimesheetMetaDisplayEvent::class => ['loadTimesheetField', 200],
            //CustomerMetaDisplayEvent::class => ['loadCustomerField', 200],
            //ProjectMetaDisplayEvent::class => ['loadProjectField', 200],
            //ActivityMetaDisplayEvent::class => ['loadActivityField', 200],
        ];
    }

    public function loadTimesheetField(TimesheetMetaDisplayEvent $event): void
    {
        $event->addField($this->prepareField(new TimesheetMeta()));
    }

    public function loadCustomerField(CustomerMetaDisplayEvent $event): void
    {
        $event->addField($this->prepareField(new CustomerMeta()));
    }

    public function loadProjectField(ProjectMetaDisplayEvent $event): void
    {
        $event->addField($this->prepareField(new ProjectMeta()));
    }

    public function loadActivityField(ActivityMetaDisplayEvent $event): void
    {
        $event->addField($this->prepareField(new ActivityMeta()));
    }

    private function prepareField(MetaTableTypeInterface $definition): MetaTableTypeInterface
    {
        $definition->setLabel('External ID');
        $definition->setName('externalId');
        $definition->setType(TextType::class);
        $definition->setIsVisible(true);

        return $definition;
    }
}