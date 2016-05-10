<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Fabian Keller
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
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