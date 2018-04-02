<?php

/**
 * This file is part of the Propel package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Propel\Generator\Builder\Om;

use Propel\Common\Types\BuildableFieldTypeInterface;
use Propel\Generator\Builder\Om\Component\NamingTrait;

/**
 * Generates a POPO.
 *
 * This class produces the actual entity object class (e.g. MyEntity) which contains
 * all the accessor and setter methods as well as fields as class properties.
 *
 * @author Marc J. Schmidt <marc@marcjschmidt.de>
 * @author David Weston <westie@typefish.co.uk>
 */
class ObjectBuilder extends AbstractBuilder
{
    use NamingTrait;

    public function buildClass()
    {
        if ($this->getEntity()->isActiveRecord()) {
            $this->getDefinition()->declareUse($this->getActiveRecordTraitName(true));
            $this->getDefinition()->addTrait($this->getActiveRecordTraitName());
        }

        if ($this->getEntity()->isTraitable()) {
            $this->getDefinition()->declareUse($this->getObjectTraitName(true));
            $this->getDefinition()->addTrait($this->getObjectTraitName());
            
            if (!$this->getDefinition()->getDescription())
                $this->getDefinition()->setDescription('Generated code for model is located within the '.$this->getObjectTraitName().' trait');
        }

        if (!$this->getEntity()->isTraitable()) {
            $this->applyComponents();
        }
    }
    
    protected function applyComponents()
    {
        $this->applyComponent('Object\\Properties');
        $this->applyComponent('Object\\MagicToStringMethod');
        $this->applyComponent('Object\\RelationProperties');
        $this->applyComponent('Object\\ReferrerRelationProperties');
        $this->applyComponent('Object\\CrossRelationProperties');

        $this->applyComponent('Object\\PropertyGetterMethods');
        $this->applyComponent('Object\\RelationGetterMethods');
        $this->applyComponent('Object\\CrossRelationGetterMethods');
        $this->applyComponent('Object\\CrossRelationSetterMethods');

        if ($this->getEntity()->isActiveRecord()) {
            $this->applyComponent('Object\\CrossRelationCountMethods');
            $this->applyComponent('Object\\ReferrerRelationCountMethods');
        }
        if ($this->getEntity()->isReadOnly() === false) {
            $this->applyComponent('Object\\PropertySetterMethods');
        }
        $this->applyComponent('Object\\RelationSetterMethods');
        $this->applyComponent('Object\\ReferrerRelationAddMethods');
        $this->applyComponent('Object\\ReferrerRelationRemoveMethods');
        $this->applyComponent('Object\\ReferrerRelationGetMethods');
        $this->applyComponent('Object\\ReferrerRelationSetMethods');
        $this->applyComponent('Object\\CrossRelationAdderMethods');
        $this->applyComponent('Object\\CrossRelationPostAddMethod');
        $this->applyComponent('Object\\CrossRelationRemoverMethods');
        $this->applyComponent('Object\\CrossRelationPostRemoveMethod');

        $this->applyComponent('Object\\ConstructorMethod');
    }
}
