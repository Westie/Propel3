<?php

/**
 * This file is part of the Propel package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Propel\Generator\Builder\Om;

use gossi\codegen\generator\CodeGenerator;
use Propel\Generator\Builder\PhpModel\TraitDefinition;
use Propel\Runtime\Exception\PropelException;

/**
 * Generates a POPO.
 *
 * This class produces the actual entity object trait (e.g. MyEntityTrait) which contains
 * all the accessor and setter methods as well as fields as class properties.
 *
 * @author David Weston <westie@typefish.co.uk>
 */
class ObjectTraitBuilder extends ObjectBuilder
{
    public function getFullClassName($injectNamespace = '', $classPrefix = '')
    {
        return parent::getFullClassName('Base', '').'Trait';
    }
    
    public function buildClass()
    {
        $this->applyComponents();
    }
    
    public function build()
    {
        $this->validateModel();
        $this->definition = new TraitDefinition($this->getFullClassName());

        if (!$this->getEntity()->getPrimaryKey()) {
            throw new PropelException(sprintf('The entity %s does not have a primary key.', $this->getEntity()->getFullClassName()));
        }

        if (false === $this->buildClass()) {
            return null;
        }

        foreach ($this->getEntity()->getFields() as $field) {
            if ($field->getFieldType() instanceof BuildableFieldTypeInterface) {
                $field->getFieldType()->build($this, $field);
            }
        }

        $this->applyBehaviorModifier();

        $generator = new CodeGenerator();

        $code = "<?php\n\n" . $generator->generate($this->getDefinition());

        return $code;
    }
}