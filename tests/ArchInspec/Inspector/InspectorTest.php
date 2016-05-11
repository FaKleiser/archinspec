<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace ArchInspec\Inspector;

class InspectorTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleNamespace()
    {
        $yaml = <<<EOT
ArchInspec:
    - allow: ArchInspec
    - deny: Symfony
EOT;
        $inspector = new Inspector();
        $inspector->load($yaml);
        $this->assertTrue($inspector->isAllowed('ArchInspec', 'ArchInspec')->isAllowed());
        $this->assertTrue($inspector->isAllowed('ArchInspec', 'Symfony')->isDenied());
        $this->assertTrue($inspector->isAllowed('ArchInspec', 'UnknownNamespace')->isUndefined());
    }

    public function testNestedPackages()
    {
        $yaml = <<<EOT
ArchInspec\Inspector:
    - allow: [ArchInspec, Symfony\Yaml]
    - deny: [Company, Denied\Package]
EOT;
        $inspector = new Inspector();
        $inspector->load($yaml);

        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'ArchInspec')->isAllowed());
        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'Symfony\Yaml')->isAllowed());

        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'Company')->isDenied());
        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'Denied\Package')->isDenied());
    }

    public function testOneLevelPolicyInheritance()
    {
        $yaml = <<<EOT
ArchInspec:
    - allow: [ ArchInspec, Symfony\Yaml]
    - deny: [ Company, Denied\Package]
EOT;
        $inspector = new Inspector();
        $inspector->load($yaml);

        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'ArchInspec\Inspector')->isAllowed());
        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'ArchInspec')->isAllowed());
        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'Symfony\Yaml')->isAllowed());

        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'Company')->isDenied());
        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'Denied\Package')->isDenied());
    }

    public function testMultiLevelPolicyInheritance()
    {
        $yaml = <<<EOT
ArchInspec:
    - allow: First\Package
ArchInspec\Sub:
    - allow: Second\Package
EOT;
        $inspector = new Inspector();
        $inspector->load($yaml);

        $this->assertTrue($inspector->isAllowed('ArchInspec\Sub', 'Second\Package')->isAllowed());
        $this->assertTrue($inspector->isAllowed('ArchInspec\Sub', 'First\Package')->isAllowed());

        $this->assertTrue($inspector->isAllowed('ArchInspec\Sub\Deeply\Nested\Package', 'Second\Package')->isAllowed());
        $this->assertTrue($inspector->isAllowed('ArchInspec\Sub\Deeply\Nested\Package', 'First\Package')->isAllowed());

        $this->assertTrue($inspector->isAllowed('ArchInspec\Sub\Deeply\Nested\Package', 'Second')->isUndefined());
        $this->assertTrue($inspector->isAllowed('ArchInspec\Sub\Deeply\Nested\Package', 'First')->isUndefined());
    }
}