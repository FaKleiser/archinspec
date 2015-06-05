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

use ArchInspec\Policy\Evaluation\EvaluationResult;

class InspectorTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleNamespace()
    {
        $yaml = <<<EOT
ArchInspec:
    allow:
        - ArchInspec
    deny:
        - Symfony
EOT;
        $inspector = new Inspector();
        $inspector->load($yaml);
        $this->assertTrue($inspector->isAllowed('ArchInspec', 'ArchInspec')->equals(EvaluationResult::allowed()));
        $this->assertTrue($inspector->isAllowed('ArchInspec', 'Symfony')->equals(EvaluationResult::denied()));
        $this->assertTrue($inspector->isAllowed('ArchInspec', 'UnknownNamespace')->equals(EvaluationResult::undefined()));
    }

    public function testNestedPackages()
    {
        $yaml = <<<EOT
ArchInspec\Inspector:
    allow:
        - ArchInspec
        - Symfony\Yaml
    deny:
        - Company
        - Denied\Package
EOT;
        $inspector = new Inspector();
        $inspector->load($yaml);

        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'ArchInspec')->equals(EvaluationResult::allowed()));
        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'Symfony\Yaml')->equals(EvaluationResult::allowed()));

        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'Company')->equals(EvaluationResult::denied()));
        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'Denied\Package')->equals(EvaluationResult::denied()));
    }

    public function testOneLevelPolicyInheritance()
    {
        $yaml = <<<EOT
ArchInspec:
    allow:
        - ArchInspec
        - Symfony\Yaml
    deny:
        - Company
        - Denied\Package
EOT;
        $inspector = new Inspector();
        $inspector->load($yaml);

        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'ArchInspec\Inspector')->equals(EvaluationResult::allowed()));
        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'ArchInspec')->equals(EvaluationResult::allowed()));
        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'Symfony\Yaml')->equals(EvaluationResult::allowed()));

        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'Company')->equals(EvaluationResult::denied()));
        $this->assertTrue($inspector->isAllowed('ArchInspec\Inspector', 'Denied\Package')->equals(EvaluationResult::denied()));
    }

    public function testMultiLevelPolicyInheritance()
    {
        $yaml = <<<EOT
ArchInspec:
    allow:
        - First\Package
ArchInspec\Sub:
    allow:
        - Second\Package
EOT;
        $inspector = new Inspector();
        $inspector->load($yaml);

        $this->assertTrue($inspector->isAllowed('ArchInspec\Sub', 'Second\Package')->equals(EvaluationResult::allowed()));
        $this->assertTrue($inspector->isAllowed('ArchInspec\Sub', 'First\Package')->equals(EvaluationResult::allowed()));

        $this->assertTrue($inspector->isAllowed('ArchInspec\Sub\Deeply\Nested\Package', 'Second\Package')->equals(EvaluationResult::allowed()));
        $this->assertTrue($inspector->isAllowed('ArchInspec\Sub\Deeply\Nested\Package', 'First\Package')->equals(EvaluationResult::allowed()));

        $this->assertTrue($inspector->isAllowed('ArchInspec\Sub\Deeply\Nested\Package', 'Second')->equals(EvaluationResult::undefined()));
        $this->assertTrue($inspector->isAllowed('ArchInspec\Sub\Deeply\Nested\Package', 'First')->equals(EvaluationResult::undefined()));
    }
}